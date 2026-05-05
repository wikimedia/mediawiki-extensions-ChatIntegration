<?php

namespace MediaWiki\Extension\ChatIntegration\ChatClient;

use MediaWiki\Extension\ChatIntegration\IChatClient;
use MediaWiki\Message\Message;

class MSTeams implements IChatClient {

	/**
	 * @inheritDoc
	 */
	public function getKey(): string {
		return 'ms-teams';
	}

	/**
	 * @inheritDoc
	 */
	public function getLabel(): Message {
		return Message::newFromKey( 'chatintegration-client-ms-teams' );
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
		return 'chat-intergration-ms-teams-username';
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
		$text = preg_replace( '/<br\s*\/?>/i', ' ', $wt );
		$text = preg_replace( "/'''''(.*?)'''''/", '***$1***', $text );
		$text = preg_replace( "/'''(.*?)'''/", '**$1**', $text );
		$text = preg_replace( "/''(.*?)''/", '_$1_', $text );
		$text = preg_replace( '/<b>(.*?)<\/b>/', '**$1**', $text );
		return preg_replace_callback( '/\[(.+?)(\s.+?|)\]/', static function ( $matches ) {
			$url = $matches[1];
			$label = isset( $matches[2] ) && trim( $matches[2] ) !== '' ? trim( $matches[2] ) : $url;
			return "[$label]($url)";
		}, $text );
	}
}
