<?php

namespace MediaWiki\Extension\ChatIntegration\Hooks;

use MediaWiki\Extension\ChatIntegration\Bridge\BridgeClient;
use MediaWiki\Extension\ChatIntegration\UserProfile\ChatClientUsernameField;
use MediaWiki\Extension\UserProfile\ProfileFieldRegistry;
use MediaWiki\Hook\SetupAfterCacheHook;
use MediaWiki\MediaWikiServices;

class RegisterChatUsernames implements SetupAfterCacheHook {

	/**
	 * @param BridgeClient $bridge
	 * @param ProfileFieldRegistry|null $profileFieldRegistry
	 */
	public function __construct(
		private readonly BridgeClient $bridge,
		private ?ProfileFieldRegistry $profileFieldRegistry = null
	) {
		$this->profileFieldRegistry = $profileFieldRegistry;
	}

	/**
	 * @inheritDoc
	 */
	public function onSetupAfterCache() {
		$availableClients = $this->bridge->getAvailableClients();
		if ( !$this->profileFieldRegistry ) {
			// Unfortunately, we can not have this service injected in the constructor
			// by the ObjectFactory of the HookContainer, as it would prematurely
			// initialize the `NamespaceInfo` service. This would break custom
			// namespaces that are initialized later.
			$this->profileFieldRegistry = MediaWikiServices::getInstance()
					->getService( 'UserProfile.FieldRegistry' );
		}
		foreach ( $availableClients as $client ) {
			$preferenceKey = $client->getUsernamePreferenceKey();
			// Set fields on user profile for users to enter their chat client usernames
			$this->profileFieldRegistry->registerField(
				$preferenceKey,
					new ChatClientUsernameField(
						$preferenceKey,
					$client->getLabel()->getKey(),
					true,
					[ 'type' => 'text' ]
				)
			);
		}
	}

}
