<?php

namespace MediaWiki\Extension\ChatIntegration\ChatService;

use MediaWiki\Extension\ChatIntegration\IChatService;

class AIChat implements IChatService {

	/**
	 * @return string
	 */
	public function getKey(): string {
		return 'ai-chat';
	}
}
