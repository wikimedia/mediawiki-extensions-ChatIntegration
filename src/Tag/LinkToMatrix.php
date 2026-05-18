<?php

namespace MediaWiki\Extension\ChatIntegration\Tag;

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;

class LinkToMatrix extends LinkToChatIntegrationTag {

	/** @var string */
	public const INTEGRATION_NAME = 'matrix';

	/**
	 * @inheritDoc
	 */
	public function getHandler( MediaWikiServices $services ): ITagHandler {
		return new LinkToChatIntegrationTagHandler(
			'matrix.svg',
			'Matrix Icon',
			static::INTEGRATION_NAME
		);
	}
}
