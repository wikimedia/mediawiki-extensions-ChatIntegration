<?php

namespace MediaWiki\Extension\ChatIntegration\ContentDroplets;

use MediaWiki\Extension\ChatIntegration\Tag\LinkToRocketChat;

class RocketChatDroplet extends InsertLinkToChatIntegrationDroplet {
	protected string $dropletName = LinkToRocketChat::INTEGRATION_NAME;

	/**
	 * Hide droplet if no base url is configured
	 *
	 * @inheritDoc
	 */
	public function listDroplet(): bool {
		return (bool)$this->config->get( 'ChatIntegrationRocketChatUrl' );
	}
}
