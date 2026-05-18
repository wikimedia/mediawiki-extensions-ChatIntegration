<?php

namespace MediaWiki\Extension\ChatIntegration\Tag;

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;

class LinkToMSTeams extends LinkToChatIntegrationTag {

	/** @var string */
	public const INTEGRATION_NAME = 'msteams';

	/**
	 * @inheritDoc
	 */
	public function getHandler( MediaWikiServices $services ): ITagHandler {
		return new LinkToChatIntegrationTagHandler(
			'msteams.svg',
			'MS Teams Icon',
			static::INTEGRATION_NAME
		);
	}
}
