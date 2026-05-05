<?php

namespace MediaWiki\Extension\ChatIntegration\Tag;

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;

class LinkToZoom extends LinkToChatIntegrationTag {

	/** @var string */
	public const INTEGRATION_NAME = 'zoom';

	/**
	 * @inheritDoc
	 */
	public function getHandler( MediaWikiServices $services ): ITagHandler {
		return new LinkToChatIntegrationTagHandler(
			'zoom.svg',
			'Zoom Icon',
			static::INTEGRATION_NAME
		);
	}
}
