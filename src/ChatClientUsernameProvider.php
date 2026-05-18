<?php

namespace MediaWiki\Extension\ChatIntegration;

use MediaWiki\Extension\UserProfile\ProfileManager;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\ILoadBalancer;

class ChatClientUsernameProvider {

	/**
	 * @param ProfileManager $profileManager
	 * @param UserFactory $userFactory
	 * @param ILoadBalancer $lb
	 */
	public function __construct(
		private readonly ProfileManager $profileManager,
		private readonly UserFactory $userFactory,
		private readonly ILoadBalancer $lb
	) {
	}

	/**
	 * @param IChatClient $client
	 * @param UserIdentity $user
	 * @param bool $withHandle
	 * @return string|null
	 */
	public function getChatUsername( IChatClient $client, UserIdentity $user, bool $withHandle = false ): ?string {
		$profile = $this->profileManager->getProfileData(
			$user,
			User::newSystemUser( 'MediaWiki default', [ 'steal' => true ] )
		);
		$key = $client->getUsernamePreferenceKey();
		if ( !isset( $profile[$key] ) ) {
			return null;
		}
		return $this->process( $profile[$key], $client, $withHandle );
	}

	/**
	 * @param IChatClient $client
	 * @param string $username
	 * @return User|null
	 */
	public function getWikiUserFromChatUsername( IChatClient $client, string $username ): ?User {
		$username = $this->process( $username, $client, false );
		if ( !$username ) {
			return null;
		}

		$row = $this->lb->getConnection( DB_REPLICA )->newSelectQueryBuilder()
			->fields( [ 'u.user_id', 'u.user_name' ] )
			->table( 'user_properties', 'up' )
			->table( 'user', 'u' )
			->join( 'user', 'u', [ 'u.user_id = up.up_user' ] )
			->where( [ 'up.up_property' => $client->getUsernamePreferenceKey() ] )
			->where( [ 'up.up_value' => $username ] )
			->caller( __METHOD__ )
			->fetchRow();

		if ( !$row ) {
			return null;
		}

		return $this->userFactory->newFromRow( $row );
	}

	/**
	 * @param string $username
	 * @param IChatClient $client
	 * @param bool $withHandle
	 * @return string|null
	 */
	private function process( string $username, IChatClient $client, bool $withHandle ): ?string {
		// Process the username, e.g., trim whitespace or validate format
		$username = trim( $username );
		// Trim handle char
		$handleChar = $client->getHandleIdentifier();
		if ( str_starts_with( $username, $handleChar ) ) {
			$username = substr( $username, strlen( $handleChar ) );
		}
		if ( !$username ) {
			return null;
		}
		return $withHandle ? $client->getHandleIdentifier() . $username : $username;
	}
}
