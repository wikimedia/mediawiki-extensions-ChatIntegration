<?php

namespace MediaWiki\Extension\ChatIntegration\Hooks;

use MediaWiki\Extension\ChatIntegration\Bridge\BridgeClient;
use MediaWiki\Extension\ChatIntegration\ChatClientUsernameProvider;
use MediaWiki\Extension\ChatIntegration\NotifyMe\ChatClientChannel;
use MediaWiki\Extension\NotifyMe\ChannelFactory;
use MediaWiki\Extension\NotifyMe\Hook\NotifyMeRegisterChannelHook;
use MediaWiki\Extension\NotifyMe\NotificationSerializer;
use MediaWiki\Hook\BeforePageDisplayHook;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\User\UserOptionsLookup;

class InitializeChatClients implements NotifyMeRegisterChannelHook, BeforePageDisplayHook {

	/**
	 * @param BridgeClient $bridge
	 * @param ChatClientUsernameProvider $chatClientUsernameProvider
	 * @param NotificationSerializer $notificationSerializer
	 * @param Language $contentLang
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param LanguageFactory $languageFactory
	 */
	public function __construct(
		private readonly BridgeClient $bridge,
		private readonly ChatClientUsernameProvider $chatClientUsernameProvider,
		private readonly NotificationSerializer $notificationSerializer,
		private readonly Language $contentLang,
		private readonly UserOptionsLookup $userOptionsLookup,
		private readonly LanguageFactory $languageFactory,
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function onNotifyMeRegisterChannel( ChannelFactory $channelFactory ) {
		$availableClients = $this->bridge->getAvailableClients();
		foreach ( $availableClients as $client ) {
			// Register notification channels
			$channelFactory->registerChannel( new ChatClientChannel(
				$this->chatClientUsernameProvider,
				$this->bridge,
				$this->notificationSerializer,
				$this->contentLang,
				$this->userOptionsLookup,
				$this->languageFactory,
				$client
			) );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		if ( $out->getTitle() && $out->getTitle()->isSpecialPage() ) {
			$out->addModuleStyles( [ 'ext.chatIntegration.chatIcons' ] );
		}
	}
}
