<?php

namespace MediaWiki\Extension\ChatIntegration\Bridge;

use Config;
use Exception;
use MediaWiki\Extension\ChatIntegration\IChatClient;
use MediaWiki\Http\HttpRequestFactory;
use Psr\Log\LoggerInterface;

class BridgeClient {

	private array $implementedClients = [];

	private ?array $availableClients = null;

	/**
	 * @param Config $config
	 * @param HttpRequestFactory $requestFactory
	 * @param array $possibleClients
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		private readonly Config $config,
		private readonly HttpRequestFactory $requestFactory,
		array $possibleClients,
		private readonly LoggerInterface $logger
	) {
		foreach ( $possibleClients as $client ) {
			$this->implementedClients[$client->getKey()] = $client;
		}
	}

	/**
	 * @param BridgeMessage $message
	 * @return void
	 * @throws Exception
	 */
	public function sendMessage( BridgeMessage $message ) {
		$this->request( 'POST', '/receive', [
			'postData' => json_encode( $message ),
		] );
	}

	/**
	 * @param string $name
	 * @return IChatClient|null
	 * @throws Exception
	 */
	public function getClient( string $name ): ?IChatClient {
		if ( !isset( $this->getAvailableClients()[$name] ) ) {
			return null;
		}
		return $this->availableClients[$name];
	}

	/**
	 * @return IChatClient[]
	 * @throws Exception
	 */
	public function getAvailableClients(): array {
		if ( $this->availableClients === null ) {
			$bridgeConfig = $this->config->get( 'ChatIntegrationBridge' );
			if ( !is_array( $bridgeConfig ) ) {
				return [];
			}
			try {
				$response = $this->status();
			} catch ( Exception $ex ) {
				return [];
			}
			$this->availableClients = [];
			$keys = array_intersect(
				array_keys( $this->implementedClients ),
				$response['connections'] ?? []
			);
			foreach ( $keys as $key ) {
				if ( isset( $this->implementedClients[$key] ) ) {
					$this->availableClients[$key] = $this->implementedClients[$key];
				}
			}
		}

		return $this->availableClients;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function status(): array {
		return $this->request( 'GET', '/status' );
	}

	/**
	 * @param string $method
	 * @param string $path
	 * @param array $options
	 * @return array
	 * @throws Exception
	 */
	public function request( string $method, string $path, array $options = [] ): array {
		$bridgeConfig = $this->config->get( 'ChatIntegrationBridge' );
		if ( !is_array( $bridgeConfig ) ) {
			throw new Exception(
				'ChatIntegrationBridge configuration is not set or invalid.'
			);
		}
		$options['method'] = $method;
		$url = $bridgeConfig['url'] ?? '';
		if ( !$url ) {
			return [];
		}
		if ( $path ) {
			$url = rtrim( $url, '/' ) . '/' . ltrim( $path, '/' );
		}
		$request = $this->requestFactory->create( $url, $options );
		$request->setHeader( 'Authorization', 'Bearer ' . $bridgeConfig['token'] );
		$request->setHeader( 'x-platform', 'wiki' );
		$request->setHeader( 'Content-Type', 'application/json' );
		$request->execute();

		if ( $request->getStatus() !== 200 || empty( $request->getContent() ) ) {
			$this->logger->error( 'Connection to the bridge failed' );
			throw new Exception( 'Connection to the bridge failed' );
		}
		if ( $request->getResponseHeader( 'Content-Type' ) === 'application/json' ) {
			$response = json_decode( $request->getContent(), true );
			if ( json_last_error() !== JSON_ERROR_NONE ) {
				$this->logger->error( 'Failed to retrieve response from bridge: {error}', [
					'error' => json_last_error_msg()
				] );
				return [];
			}
			return $response;
		}
		return [];
	}
}
