<?php

namespace MediaWiki\Extension\ChatIntegration;

interface IChatService {

	/**
	 * Get the unique key for this chat client
	 *
	 * @return string
	 */
	public function getKey(): string;
}
