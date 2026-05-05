<?php

namespace MediaWiki\Extension\ChatIntegration\Bridge;

use MediaWiki\Extension\ChatIntegration\IChatClient;
use MediaWiki\Extension\ChatIntegration\IChatService;

class BridgeMessage implements \JsonSerializable {

	/**
	 * @param IChatClient $target
	 * @param string $message
	 * @param array $meta
	 * @param string $type
	 */
	public function __construct(
		public readonly IChatService $target,
		public readonly string $message,
		public readonly array $meta,
		public readonly string $type = 'message'
	) {
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array {
		return [
			'_t' => $this->type,
			'_src' => 'wiki',
			'_trg' => $this->target->getKey(),
			'txt' => $this->message,
			'mt' => $this->meta,
		];
	}
}
