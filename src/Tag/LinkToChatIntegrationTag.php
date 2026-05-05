<?php

namespace MediaWiki\Extension\ChatIntegration\Tag;

use MediaWiki\Message\Message;
use MWStake\MediaWiki\Component\FormEngine\StandaloneFormSpecification;
use MWStake\MediaWiki\Component\GenericTagHandler\ClientTagSpecification;
use MWStake\MediaWiki\Component\GenericTagHandler\GenericTag;
use MWStake\MediaWiki\Component\GenericTagHandler\MarkerType;
use MWStake\MediaWiki\Component\InputProcessor\Processor\BooleanValue;
use MWStake\MediaWiki\Component\InputProcessor\Processor\IntValue;
use MWStake\MediaWiki\Component\InputProcessor\Processor\StringValue;

abstract class LinkToChatIntegrationTag extends GenericTag {

	/** @var string */
	public const INTEGRATION_NAME = '';

	/** @var int */
	public const DEFAULT_ICON_SIZE = 16;

	/** @var string */
	public const TAG_PREFIX = 'linkto';

	/**
	 * @inheritDoc
	 */
	public function getTagNames(): array {
		$tagPrefix = self::TAG_PREFIX;
		$integrationName = static::INTEGRATION_NAME;

		return [
			"$tagPrefix$integrationName",
		];
	}

	/**
	 * @return bool
	 */
	public function hasContent(): bool {
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function getMarkerType(): MarkerType {
		return new MarkerType\NoWiki();
	}

	/**
	 * @inheritDoc
	 */
	public function getResourceLoaderModules(): ?array {
		return [ 'ext.chatintegration.tag' ];
	}

	/**
	 * @inheritDoc
	 */
	public function getParamDefinition(): ?array {
		$url = new StringValue();
		$url->setRequired( true );
		$url->setDefaultValue( "" );

		$label = new StringValue();
		$label->setRequired( false );
		$label->setDefaultValue( "" );

		$iconSize = new IntValue();
		$iconSize->setRequired( false );
		$iconSize->setDefaultValue( self::DEFAULT_ICON_SIZE );

		$showIcon = new BooleanValue();
		$showIcon->setRequired( false );
		$showIcon->setDefaultValue( true );

		$showInTitle = new BooleanValue();
		$showInTitle->setRequired( false );
		$showInTitle->setDefaultValue( false );

		return [
			'url' => $url,
			'label' => $label,
			'icon-size' => $iconSize,
			'show-icon' => $showIcon,
			'show-in-title' => $showInTitle,
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getClientTagSpecification(): ClientTagSpecification|null {
		$iconSizes = [
			'small' => self::DEFAULT_ICON_SIZE,
			'medium' => 32,
			'large' => 64
		];

		$options = [];
		foreach ( $iconSizes as $sizeName => $sizeValue ) {
			$options[] = [
				'data' => (string)$sizeValue,
				'label' => Message::newFromKey( "chatintegration-tag-icon-size-$sizeName" )->text(),
			];
		}

		$formSpec = new StandaloneFormSpecification();
		$formSpec->setItems( [
			[
				'type' => 'text',
				'name' => 'url',
				'required' => true,
				'label' => Message::newFromKey( 'chatintegration-tag-attr-url-label' )->text(),
				'help' => Message::newFromKey( 'chatintegration-tag-attr-url-help' )->text(),
			],
			[
				'type' => 'text',
				'name' => 'label',
				'label' => Message::newFromKey( 'chatintegration-tag-attr-label-label' )->text(),
				'help' => Message::newFromKey( 'chatintegration-tag-attr-label-help' )->text(),
			],
			[
				'type' => 'dropdown',
				'name' => 'icon-size',
				'options' => $options,
				'label' => Message::newFromKey( 'chatintegration-tag-attr-icon-size-label' )->text(),
				'help' => Message::newFromKey( 'chatintegration-tag-attr-icon-size-help' )->text(),
			],
			[
				'type' => 'checkbox',
				'name' => 'show-icon',
				'label' => Message::newFromKey( 'chatintegration-tag-attr-show-icon-label' )->text(),
				'help' => Message::newFromKey( 'chatintegration-tag-attr-show-icon-help' )->text(),
				'labelAlign' => 'inline',
				'value' => true
			],
			[
				'type' => 'checkbox',
				'name' => 'show-in-title',
				'label' => Message::newFromKey( 'chatintegration-tag-attr-show-in-title-label' )->text(),
				'help' => Message::newFromKey( 'chatintegration-tag-attr-show-in-title-help' )->text(),
				'labelAlign' => 'inline',
				'value' => false
			],
		] );

		$integrationName = static::INTEGRATION_NAME;
		return new ClientTagSpecification(
			$integrationName,
			Message::newFromKey( "chatintegration-inspector-$integrationName-description" ),
			$formSpec,
			Message::newFromKey( "chatintegration-inspector-$integrationName-title" )
		);
	}
}
