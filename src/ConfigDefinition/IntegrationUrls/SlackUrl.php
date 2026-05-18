<?php

namespace MediaWiki\Extension\ChatIntegration\ConfigDefinition\IntegrationUrls;

use MediaWiki\Extension\ChatIntegration\Tag\LinkToSlack;

class SlackUrl extends IntegrationUrl {

	/**
	 * @return string
	 */
	public function getLabelMessageKey(): string {
		$integrationName = LinkToSlack::INTEGRATION_NAME;

		return "pref-chatintegration-$integrationName-url";
	}

	/**
	 * @inheritDoc
	 */
	public function getGlobalName(): string {
		return "wgChatIntegrationSlackUrl";
	}
}
