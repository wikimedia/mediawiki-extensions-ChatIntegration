<?php

namespace MediaWiki\Extension\ChatIntegration\ContentDroplets;

use MediaWiki\Extension\ChatIntegration\Tag\LinkToGoogleMeet;

class GoogleMeetDroplet extends InsertLinkToChatIntegrationDroplet {
	protected string $dropletName = LinkToGoogleMeet::INTEGRATION_NAME;

	/**
	 * Hide droplet if no base url is configured
	 *
	 * @inheritDoc
	 */
	public function listDroplet(): bool {
		return (bool)$this->config->get( 'ChatIntegrationGoogleMeetUrl' );
	}
}
