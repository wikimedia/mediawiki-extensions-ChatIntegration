<?php

namespace MediaWiki\Extension\ChatIntegration\Rest;

use Exception;
use MediaWiki\Extension\ChatIntegration\Bridge\BridgeClient;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;

class PingHandler extends SimpleHandler {

	/**
	 * @param BridgeClient $bridgeClient
	 */
	public function __construct(
		private readonly BridgeClient $bridgeClient
	) {
	}

	/**
	 * @return Response|mixed
	 * @throws Exception
	 */
	public function execute() {
		$bridgeConnection = false;
		try {
			$this->bridgeClient->status();
			$bridgeConnection = true;
		} catch ( Exception $e ) {
		}

		return $this->getResponseFactory()->createJson( [
			'online' => true,
			'bridgeConnection' => $bridgeConnection,
		] );
	}

	public function needsReadAccess() {
		return true;
	}
}
