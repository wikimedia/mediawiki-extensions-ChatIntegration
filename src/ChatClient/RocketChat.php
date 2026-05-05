<?php

namespace MediaWiki\Extension\ChatIntegration\ChatClient;

use MediaWiki\Extension\ChatIntegration\IChatClient;
use MediaWiki\Message\Message;

class RocketChat implements IChatClient {

	/**
	 * @inheritDoc
	 */
	public function getKey(): string {
		return 'rocket-chat';
	}

	/**
	 * @inheritDoc
	 */
	public function getLabel(): Message {
		return Message::newFromKey( 'chatintegration-client-rocket-chat' );
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
		return 'chat-intergration-rocket-chat-username';
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
