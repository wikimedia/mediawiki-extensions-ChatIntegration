<?php

use MediaWiki\Extension\ChatIntegration\Bridge\BridgeClient;
use MediaWiki\Extension\ChatIntegration\ChatClientUsernameProvider;
use MediaWiki\Extension\ChatIntegration\IChatClient;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Registration\ExtensionRegistry;

return [
	'ChatIntegration.BridgeClient' => static function ( MediaWikiServices $services ) {
		$possibleClients = ExtensionRegistry::getInstance()->getAttribute( 'ChatIntegrationChatClients' );
		$possibleClients = array_map(
			static function ( $client ) use ( $services ) {
				$instance = $services->getObjectFactory()->createObject( $client );
				if ( !$instance instanceof IChatClient ) {
					throw new Exception(
						'Invalid client instance provided: ' . get_class( $instance )
					);
				}
				return $instance;
			},
			$possibleClients
		);

		return new BridgeClient(
			$services->getMainConfig(),
			$services->getHttpRequestFactory(),
			$possibleClients,
			LoggerFactory::getInstance( 'ChatIntegration.BridgeClient' )
		);
	},
	'ChatIntegration.ChatClientUsernameProvider' => static function ( MediaWikiServices $services ) {
		return new ChatClientUsernameProvider(
			$services->getService( 'UserProfile.Manager' ),
			$services->getUserFactory(),
			$services->getDBLoadBalancer()
		);
	},
];
