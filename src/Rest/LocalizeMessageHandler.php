<?php

namespace MediaWiki\Extension\ChatIntegration\Rest;

use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Message\Message;
use MediaWiki\Rest\SimpleHandler;
use Wikimedia\ParamValidator\ParamValidator;

class LocalizeMessageHandler extends SimpleHandler {

	/**
	 * @param LanguageFactory $languageFactory
	 */
	public function __construct(
		private readonly LanguageFactory $languageFactory
	) {
	}

	/**
	 * @return \MediaWiki\Rest\Response|mixed
	 */
	public function execute() {
		$params = $this->getValidatedParams();
		// Retrieve all `arg{number}` params from query string
		$args = [];
		foreach ( $this->getRequest()->getQueryParams() as $key => $value ) {
			if ( preg_match( '/^arg(\d+)$/', $key ) ) {
				$args[] = $value;
			}
		}
		$msg = Message::newFromKey( $params['message_key'] );
		if ( $args ) {
			$msg->params( ...$args );
		}
		if ( $params['language'] ) {
			$language = $this->languageFactory->getLanguage( $params['language'] );
			$msg->inLanguage( $language );
		}
		return $msg->text();
	}

	/**
	 * @return true
	 */
	public function needsReadAccess() {
		return true;
	}

	/**
	 * @return array[]
	 */
	public function getParamSettings() {
		return [
			'language' => [
				static::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_TYPE => 'string'
			],
			'message_key' => [
				static::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_TYPE => 'string'
			]
		];
	}
}
