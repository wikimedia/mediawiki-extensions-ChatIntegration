<?php

namespace MediaWiki\Extension\ChatIntegration\Tag;

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;

class LinkToSlack extends LinkToChatIntegrationTag {

	/** @var string */
	public const INTEGRATION_NAME = 'slack';

	/**
	 * @inheritDoc
	 */
	public function getHandler( MediaWikiServices $services ): ITagHandler {
		return new LinkToChatIntegrationTagHandler(
			'slack.svg',
			'Slack Icon',
			static::INTEGRATION_NAME
		);
	}
}
