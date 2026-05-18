<?php

namespace MediaWiki\Extension\ChatIntegration;

use MediaWiki\Message\Message;

interface IChatClient extends IChatService {

	/**
	 * @return Message
	 */
	public function getLabel(): Message;

	/**
	 * Get user handle identifier, usually `@`
	 *
	 * @return string
	 */
	public function getHandleIdentifier(): string;

	/**
	 * Get MW user preference key that holds username for this chat client
	 *
	 * @return string
	 */
	public function getUsernamePreferenceKey(): string;

	/**
	 * Convert HTML to a syntax suitable for the chat client.
	 *
	 * @param string $html
	 * @return string
	 */
	public function convertHTMLToChatSyntax( string $html ): string;

	/**
	 * Convert Wikitext to a syntax suitable for the chat client.
	 *
	 * @param string $wt
	 * @return string
	 */
	public function convertWikitextToChatSyntax( string $wt ): string;
}
