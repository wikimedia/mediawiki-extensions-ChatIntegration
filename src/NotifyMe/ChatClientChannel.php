<?php

namespace MediaWiki\Extension\ChatIntegration\NotifyMe;

use Exception;
use MediaWiki\Extension\ChatIntegration\Bridge\BridgeClient;
use MediaWiki\Extension\ChatIntegration\Bridge\BridgeMessage;
use MediaWiki\Extension\ChatIntegration\ChatClientUsernameProvider;
use MediaWiki\Extension\ChatIntegration\IChatClient;
use MediaWiki\Extension\NotifyMe\NotificationSerializer;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Message\Message;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserOptionsLookup;
use MWStake\MediaWiki\Component\Events\Delivery\IChannel;
use MWStake\MediaWiki\Component\Events\Delivery\IExternalChannel;
use MWStake\MediaWiki\Component\Events\INotificationEvent;
use MWStake\MediaWiki\Component\Events\Notification;

class ChatClientChannel implements IChannel, IExternalChannel {

	/**
	 * @param ChatClientUsernameProvider $usernameProvider
	 * @param BridgeClient $bridge
	 * @param NotificationSerializer $serializer
	 * @param Language $contentLang
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param LanguageFactory $languageFactory
	 * @param IChatClient $client
	 */
	public function __construct(
		private readonly ChatClientUsernameProvider $usernameProvider,
		private readonly BridgeClient $bridge,
		private readonly NotificationSerializer $serializer,
		private readonly Language $contentLang,
		private readonly UserOptionsLookup $userOptionsLookup,
		private readonly LanguageFactory $languageFactory,
		private readonly IChatClient $client
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function getKey(): string {
		return 'chatintegration-' . $this->client->getKey();
	}

	/**
	 * @inheritDoc
	 */
	public function getLabel(): Message {
		return Message::newFromKey( 'chatintegration-channel-label', $this->client->getLabel()->getKey() );
	}

	/**
	 * @inheritDoc
	 */
	public function shouldSkip( INotificationEvent $event, UserIdentity $user ): bool {
		// TODO: We can do notification blacklist/whitelist here
		$username = $this->usernameProvider->getChatUsername( $this->client, $user );
		return $username === null;
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultConfiguration(): array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function onNotificationPersisted( Notification $notification, bool $created ): void {
		// NOOP
	}

	/**
	 * @inheritDoc
	 */
	public function onNotificationOutputSerialized( Notification $notification, array &$data ): void {
		// NOOP
	}

	/**
	 * @inheritDoc
	 */
	public function deliver( Notification $notification ): bool {
		$user = $notification->getTargetUser();
		$userLang = $this->getUserLanguage( $user );
		$username = $this->usernameProvider->getChatUsername( $this->client, $user );
		if ( !$username ) {
			// No username found, cannot deliver
			return false;
		}
		$serialized = $this->serializer->serializeForOutput( $notification, $user );
		$agent = !$serialized['agent_is_bot'] ?
			$this->getAgentUsername( $serialized['agent'], $notification->getEvent()->getAgent() ) :
			'';
		$message = $notification->getEvent()->getMessage( $this )->inLanguage( $userLang )->text();
		if ( $agent ) {
			$message = "$agent $message";
		}
		$links = $serialized['links'];
		$linkArray = [];
		foreach ( $links as $link ) {
			$linkText = "[{$link['url']} {$link['label']}]";
			if ( $link['primary'] ) {
				$linkText = "<b>$linkText</b>";
			}
			$linkArray[] = $linkText;
		}
		if ( $linkArray ) {
			$message .= ' ' . implode( ' ', $linkArray );
		}
		try {
			$this->bridge->sendMessage( new BridgeMessage(
				$this->client,
				$this->convertSyntax( $message ),
				[
					'_chatTarget' => [
						'type' => 'user',
						'id' => $username,
					]
				]
			) );

			return true;
		} catch ( \Throwable $e ) {
			return false;
		}
	}

	/**
	 * @param string $wt
	 * @return string
	 */
	private function convertSyntax( string $wt ): string {
		return $this->client->convertWikitextToChatSyntax( $wt );
	}

	/**
	 * @param array $agent
	 * @param UserIdentity $agentUser
	 * @return string
	 */
	private function getAgentUsername( array $agent, UserIdentity $agentUser ): string {
		$chatUsername = $this->usernameProvider->getChatUsername( $this->client, $agentUser, true );
		if ( $chatUsername ) {
			return $chatUsername;
		}
		return $agent['display_name'];
	}

	/**
	 * @param UserIdentity $user
	 *
	 * @return Language
	 * @throws Exception
	 */
	private function getUserLanguage( UserIdentity $user ): Language {
		$langCode = $this->userOptionsLookup->getOption( $user, 'language' );
		if ( $langCode === null ) {
			$langCode = $this->contentLang;
		}
		return $this->languageFactory->getLanguage( $langCode );
	}
}
