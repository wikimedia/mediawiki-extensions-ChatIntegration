<?php

namespace MediaWiki\Extension\ChatIntegration\ConfigDefinition\IntegrationUrls;

use MediaWiki\Extension\ChatIntegration\Tag\LinkToGoogleMeet;

class GoogleMeetUrl extends IntegrationUrl {

	/**
	 * @return string
	 */
	public function getLabelMessageKey(): string {
		$integrationName = LinkToGoogleMeet::INTEGRATION_NAME;

		return "pref-chatintegration-$integrationName-url";
	}

	/**
	 * @inheritDoc
	 */
	public function getGlobalName(): string {
		return "wgChatIntegrationGoogleMeetUrl";
	}
}
