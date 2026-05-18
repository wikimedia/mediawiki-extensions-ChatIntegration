<?php

namespace MediaWiki\Extension\ChatIntegration\ContentDroplets;

use MediaWiki\Extension\ChatIntegration\Tag\LinkToMSTeams;

class MSTeamsDroplet extends InsertLinkToChatIntegrationDroplet {
	protected string $dropletName = LinkToMSTeams::INTEGRATION_NAME;

	/**
	 * Hide droplet if no base url is configured
	 *
	 * @inheritDoc
	 */
	public function listDroplet(): bool {
		return (bool)$this->config->get( 'ChatIntegrationMatrixUrl' );
	}
}
