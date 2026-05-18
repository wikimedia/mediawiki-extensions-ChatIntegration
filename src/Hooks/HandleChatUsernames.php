<?php

namespace MediaWiki\Extension\ChatIntegration\Hooks;

use MediaWiki\Extension\ChatIntegration\Bridge\BridgeClient;
use MediaWiki\Extension\UserProfile\Hooks\Interface\UserProfileBeforeSetProfileDataHook;
use MediaWiki\Extension\UserProfile\Hooks\Interface\UserProfileGetProfileDataHook;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\Authority;
use MediaWiki\Preferences\Hook\GetPreferencesHook;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserOptionsManager;

class HandleChatUsernames implements
	UserProfileBeforeSetProfileDataHook,
	UserProfileGetProfileDataHook,
	GetPreferencesHook
{

	/**
	 * @param UserOptionsManager $userOptionsManager
	 * @param BridgeClient $bridge
	 */
	public function __construct(
		private readonly UserOptionsManager $userOptionsManager,
		private readonly BridgeClient $bridge
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function onUserProfileBeforeSetProfileData( array &$data, UserIdentity $forUser, Authority $actor ): void {
		// For each available chat client, check if the user has set a username
		$availableClients = $this->bridge->getAvailableClients();
		foreach ( $availableClients as $client ) {
			$preferenceKey = $client->getUsernamePreferenceKey();
			if ( !isset( $data[$preferenceKey] ) || $data[$preferenceKey] === '' ) {
				$data[$preferenceKey] = null;
			}
			// Set the user preference for the chat client username
			$this->userOptionsManager->setOption( $forUser, $preferenceKey, $data[$preferenceKey] );
			unset( $data[$preferenceKey] );
		}
		$this->userOptionsManager->saveOptions( $forUser );
	}

	/**
	 * @inheritDoc
	 */
	public function onUserProfileGetProfileData( array &$data, User $user, Authority $requester ): void {
		$availableClients = $this->bridge->getAvailableClients();
		foreach ( $availableClients as $client ) {
			$preferenceKey = $client->getUsernamePreferenceKey();
			$value = $this->userOptionsManager->getOption( $user, $preferenceKey );
			if ( $value ) {
				$data[$preferenceKey] = $value;
			}

		}
	}

	/**
	 * @inheritDoc
	 */
	public function onGetPreferences( $user, &$preferences ) {
		// Declare user preference for each available chat client
		$availableClients = $this->bridge->getAvailableClients();
		foreach ( $availableClients as $client ) {
			$preferences[$client->getUsernamePreferenceKey()] = [
				// Should be `text` if no `UserProfile`
				'type' => 'hidden',
				'label' => Message::newFromKey(
					'chatintegration-userprofile-chatclientusername', $client->getLabel()->getKey()
				)->text(),
				'section' => 'personal/info',
				'hidden' => true,
			];
		}
	}

}
