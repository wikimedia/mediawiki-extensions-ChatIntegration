<?php

namespace MediaWiki\Extension\ChatIntegration\ChatClient;

use MediaWiki\Extension\ChatIntegration\IChatClient;
use MediaWiki\Message\Message;

class Slack implements IChatClient {

	/**
	 * @inheritDoc
	 */
	public function getKey(): string {
		return 'slack';
	}

	/**
	 * @inheritDoc
	 */
	public function getLabel(): Message {
		return Message::newFromKey( 'chatintegration-client-slack' );
	}

	/**
	 * @inheritDoc
	 */
	public function getHandleIdentifier(): string {
		return '@';
	}

	/**
	 * @inheritDoc
	 */
	public function getUsernamePreferenceKey(): string {
		return 'chat-intergration-slack-username';
	}

	/**
	 * @inheritDoc
	 */
	public function convertHTMLToChatSyntax( string $html ): string {
		return $html;
	}

	/**
	 * @inheritDoc
	 */
	public function convertWikitextToChatSyntax( string $wt ): string {
		$wt = preg_replace( '/<br\s*\/?>/i', ' ', $wt );
		// Replace <b> and <strong> tags with asterisks
		$wt = preg_replace( '/<(b|strong)>(.*?)<\/(b|strong)>/i', '*$2*', $wt );
		return preg_replace_callback( '/\[(.+?)(\s.+?|)\]/', static function ( $matches ) {
			$url = $matches[1];
			$label = isset( $matches[2] ) && trim( $matches[2] ) !== '' ? trim( $matches[2] ) : $url;
			return "<$url|$label>";
		}, $wt );
	}
}
