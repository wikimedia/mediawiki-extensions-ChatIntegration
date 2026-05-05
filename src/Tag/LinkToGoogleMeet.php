<?php

namespace MediaWiki\Extension\ChatIntegration\Tag;

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;

class LinkToGoogleMeet extends LinkToChatIntegrationTag {

	/** @var string */
	public const INTEGRATION_NAME = 'googlemeet';

	/**
	 * @inheritDoc
	 */
	public function getHandler( MediaWikiServices $services ): ITagHandler {
		return new LinkToChatIntegrationTagHandler(
			'googlemeet.svg',
			'Google Meet Icon',
			static::INTEGRATION_NAME
		);
	}
}
