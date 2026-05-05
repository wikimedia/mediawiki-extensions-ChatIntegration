<?php

namespace MediaWiki\Extension\ChatIntegration\Tag;

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;

class LinkToRocketChat extends LinkToChatIntegrationTag {

	/** @var string */
	public const INTEGRATION_NAME = 'rocketchat';

	/**
	 * @inheritDoc
	 */
	public function getHandler( MediaWikiServices $services ): ITagHandler {
		return new LinkToChatIntegrationTagHandler(
			'rocketchat.svg',
			'Rocket.Chat Icon',
			static::INTEGRATION_NAME
		);
	}
}
