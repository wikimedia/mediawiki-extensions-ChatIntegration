<?php

namespace MediaWiki\Extension\ChatIntegration\ConfigDefinition\IntegrationUrls;

use MediaWiki\Extension\ChatIntegration\Tag\LinkToMSTeams;

class MSTeamsUrl extends IntegrationUrl {

	/**
	 * @return string
	 */
	public function getLabelMessageKey(): string {
		$integrationName = LinkToMSTeams::INTEGRATION_NAME;

		return "pref-chatintegration-$integrationName-url";
	}

	/**
	 * @inheritDoc
	 */
	public function getGlobalName(): string {
		return "wgChatIntegrationMSTeamsUrl";
	}
}
