<?php

namespace MediaWiki\Extension\ChatIntegration\ContentDroplets;

use MediaWiki\Extension\ChatIntegration\Tag\LinkToZoom;

class ZoomDroplet extends InsertLinkToChatIntegrationDroplet {
	protected string $dropletName = LinkToZoom::INTEGRATION_NAME;

	/**
	 * Hide droplet if no base url is configured
	 *
	 * @inheritDoc
	 */
	public function listDroplet(): bool {
		return (bool)$this->config->get( 'ChatIntegrationZoomUrl' );
	}
}
