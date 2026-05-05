<?php

namespace MediaWiki\Extension\ChatIntegration\Rest;

use MediaWiki\Extension\ChatIntegration\Bridge\BridgeClient;
use MediaWiki\Extension\ChatIntegration\ChatClientUsernameProvider;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\SimpleHandler;
use MWStake\MediaWiki\Component\TokenAuthenticator\UserTokenAuthenticator;
use Wikimedia\ParamValidator\ParamValidator;

class AuthInfoHandler extends SimpleHandler {

	/**
	 * @param BridgeClient $bridgeClient
	 * @param ChatClientUsernameProvider $chatClientUsernameProvider
	 * @param UserTokenAuthenticator $userTokenAuthenticator
	 */
	public function __construct(
		private readonly BridgeClient $bridgeClient,
		private readonly ChatClientUsernameProvider $chatClientUsernameProvider,
		private readonly UserTokenAuthenticator $userTokenAuthenticator
	) {
	}

	/**
	 * @return \MediaWiki\Rest\Response|mixed
	 * @throws HttpException
	 */
	public function execute() {
		$params = $this->getValidatedParams();

		$client = $this->bridgeClient->getClient( $params['chat_client'] );
		if ( !$client ) {
			throw new HttpException( 'Chat client not found', 404 );
		}
		$username = $params['username'];
		$wikiUser = $this->chatClientUsernameProvider->getWikiUserFromChatUsername( $client, $username );
		if ( !$wikiUser ) {
			throw new HttpException( 'Chat username not found', 404 );
		}
		$authInfo = $this->userTokenAuthenticator->getAuthInfo( $wikiUser );
		if ( !$authInfo ) {
			throw new HttpException( 'Could not get auth info for user', 500 );
		}
		return $this->getResponseFactory()->createJson( $authInfo );
	}

	/**
	 * @return array[]
	 */
	public function getParamSettings() {
		return [
			'chat_client' => [
				static::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_TYPE => 'string'
			],
			'username' => [
				static::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_TYPE => 'string'
			],
		];
	}

	public function needsReadAccess() {
		return true;
	}
}
