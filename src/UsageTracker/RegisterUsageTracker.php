<?php

namespace MediaWiki\Extension\ChatIntegration\UsageTracker;

use BS\UsageTracker\Hook\BSUsageTrackerRegisterCollectors;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToGoogleMeet;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToMatrix;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToMSTeams;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToRocketChat;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToSlack;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToZoom;
use MediaWiki\Extension\ChatIntegration\UsageTracker\Collector\NoOfSubscribedChatNotifications;

class RegisterUsageTracker extends BSUsageTrackerRegisterCollectors {

	/** @var string[] */
	private const LINK_TO_CHAT_INTEGRATION_TAGS = [
		LinkToGoogleMeet::class,
		LinkToMatrix::class,
		LinkToMSTeams::class,
		LinkToRocketChat::class,
		LinkToSlack::class,
		LinkToZoom::class,
	];

	/** @var string[] */
	private const CHAT_NOTIFICATION_CLIENTS = [
		'ms-teams',
		'rocket-chat',
		'slack',
	];

	/**
	 * @return void
	 */
	protected function doProcess(): void {
		foreach ( self::LINK_TO_CHAT_INTEGRATION_TAGS as $tagClass ) {
			$tagPrefix = $tagClass::TAG_PREFIX;
			$integrationName = $tagClass::INTEGRATION_NAME;

			$this->collectorConfig["$tagPrefix$integrationName"] = [
				'class' => 'Property',
				'config' => [
					'identifier' => "bs-tag-linkto-$integrationName"
				]
			];
		}

		foreach ( self::CHAT_NOTIFICATION_CLIENTS as $clientKey ) {
			$this->collectorConfig["no-of-subscribed-$clientKey-notifications"] = [
				'class' => NoOfSubscribedChatNotifications::class,
				'config' => [
					'identifier' => "no-of-subscribed-$clientKey-notifications",
					'channel' => "chatintegration-$clientKey"
				]
			];
		}
	}
}
