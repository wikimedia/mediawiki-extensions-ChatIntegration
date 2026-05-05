<?php

namespace MediaWiki\Extension\ChatIntegration\ContentDroplets;

use MediaWiki\Config\Config;
use MediaWiki\Extension\ChatIntegration\Tag\LinkToChatIntegrationTag;
use MediaWiki\Extension\ContentDroplets\Droplet\TagDroplet;
use MediaWiki\Message\Message;

abstract class InsertLinkToChatIntegrationDroplet extends TagDroplet {

	/** @var string */
	protected string $dropletName = '';

	/**
	 * @param Config $config
	 */
	public function __construct( protected readonly Config $config ) {
	}

	/**
	 * @inheritDoc
	 */
	public function getName(): Message {
		return Message::newFromKey( "chatintegration-$this->dropletName-droplet-name" );
	}

	/**
	 * @inheritDoc
	 */
	public function getDescription(): Message {
		return Message::newFromKey( "chatintegration-$this->dropletName-droplet-desc" );
	}

	/**
	 * @inheritDoc
	 */
	public function getIcon(): string {
		return "droplet-$this->dropletName";
	}

	/**
	 * @inheritDoc
	 */
	public function getRLModules(): array {
		return [ 'ext.chatintegration.tag' ];
	}

	/**
	 * @inheritDoc
	 */
	public function getCategories(): array {
		return [ 'navigation' ];
	}

	/**
	 * @inheritDoc
	 */
	public function getVeCommand(): ?string {
		return "linkto{$this->dropletName}Command";
	}

	/**
	 * @inheritDoc
	 */
	protected function getTagName(): string {
		return "linkto{$this->dropletName}";
	}

	/**
	 * @inheritDoc
	 */
	protected function getAttributes(): array {
		return [
			"url" => '',
			"label" => '',
			"icon-size" => LinkToChatIntegrationTag::DEFAULT_ICON_SIZE,
			"show-icon" => true,
			"show-in-title" => false,
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function hasContent(): bool {
		return false;
	}
}
