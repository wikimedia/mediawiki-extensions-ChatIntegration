<?php

namespace MediaWiki\Extension\ChatIntegration\ConfigDefinition\IntegrationUrls;

use MediaWiki\Extension\ChatIntegration\Tag\LinkToRocketChat;

class RocketChatUrl extends IntegrationUrl {

	/**
	 * @return string
	 */
	public function getLabelMessageKey(): string {
		$integrationName = LinkToRocketChat::INTEGRATION_NAME;

		return "pref-chatintegration-$integrationName-url";
	}

	/**
	 * @inheritDoc
	 */
	public function getGlobalName(): string {
		return "wgChatIntegrationRocketChatUrl";
	}
}
