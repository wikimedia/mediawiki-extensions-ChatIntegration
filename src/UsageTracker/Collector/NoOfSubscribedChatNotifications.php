<?php

namespace MediaWiki\Extension\ChatIntegration\UsageTracker\Collector;

use BS\UsageTracker\CollectorResult;
use BS\UsageTracker\Collectors\Base;
use MediaWiki\Config\ConfigException;
use MediaWiki\User\User;

class NoOfSubscribedChatNotifications extends Base {

	/**
	 * @return CollectorResult
	 * @throws ConfigException
	 */
	public function getUsageData(): CollectorResult {
		$channelName = $this->config['config']['channel'];
		$res = new CollectorResult( $this );
		$userOptionsManager = $this->services->getUserOptionsManager();
		$allUsers = $this->getAllUsers();
		foreach ( $allUsers as $user ) {
			$userSubscriptions = $userOptionsManager->getOption( $user, 'ext-notification-subscriptions' );

			if ( !$userSubscriptions ) {
				continue;
			}

			$userSubscriptions = json_decode( $userSubscriptions, true );

			if ( empty( $userSubscriptions['subscriptions'] ) ) {
				continue;
			}

			$res->count += count(
				array_filter(
					array_merge(
						...array_values(
							array_column( $userSubscriptions['subscriptions'], 'channels' )
						)
					),
					fn ( $item ) => $item === $channelName
				)
			);
		}

		return $res;
	}

	/**
	 * @return User[]
	 */
	private function getAllUsers(): array {
		$userFactory = $this->services->getUserFactory();
		$dbr = $this->loadBalancer->getConnection( DB_REPLICA );
		$res = $dbr->select(
			'user',
			'user_id',
			[],
			__METHOD__
		);
		$users = [];
		foreach ( $res as $row ) {
			$users[] = $userFactory->newFromId( $row->user_id );
		}

		return $users;
	}
}
