<?php

namespace MediaWiki\Extension\ChatIntegration\Hooks;

use MediaWiki\Extension\ChatIntegration\Tag\LinkToGoogleMeet;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToMatrix;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToMSTeams;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToRocketChat;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToSlack;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToZoom;
use MWStake\MediaWiki\Component\GenericTagHandler\Hook\MWStakeGenericTagHandlerInitTagsHook;

class RegisterTags implements MWStakeGenericTagHandlerInitTagsHook {

	/**
	 * @inheritDoc
	 */
	public function onMWStakeGenericTagHandlerInitTags( array &$tags ): void {
		$tags[] = new LinkToMSTeams();
		$tags[] = new LinkToSlack();
		$tags[] = new LinkToRocketChat();
		$tags[] = new LinkToZoom();
		$tags[] = new LinkToGoogleMeet();
		$tags[] = new LinkToMatrix();
	}
}
