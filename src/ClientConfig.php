<?php

namespace MediaWiki\Extension\ChatIntegration;

use MediaWiki\Config\Config;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToChatIntegrationTag;
use MediaWiki\ResourceLoader\Context as ResourceLoaderContext;

class ClientConfig {

	private const CHAT_INTEGRATIONS = [
		'MSTeams',
		'Slack',
		'RocketChat',
		'Zoom',
		'GoogleMeet',
		'Matrix'
	];

	/**
	 * @param ResourceLoaderContext $context
	 * @param Config $config
	 *
	 * @return array
	 */
	public static function makeConfigJson(
		ResourceLoaderContext $context,
		Config $config
	) {
		$chatIntegrationTagToBaseUrls = [];
		foreach ( self::CHAT_INTEGRATIONS as $integration ) {
			$tag = LinkToChatIntegrationTag::TAG_PREFIX . strtolower( $integration );
			$chatIntegrationTagToBaseUrls[ $tag ] = $config->get( 'ChatIntegration' . $integration . 'Url' );
		}

		return [
			'tagToBaseUrls' => $chatIntegrationTagToBaseUrls
		];
	}
}
