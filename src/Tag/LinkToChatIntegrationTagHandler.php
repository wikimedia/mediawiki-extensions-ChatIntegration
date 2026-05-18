<?php

namespace MediaWiki\Extension\ChatIntegration\Tag;

use MediaWiki\Context\RequestContext;
use MediaWiki\Html\TemplateParser;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\PPFrame;
use MWStake\MediaWiki\Component\GenericTagHandler\ITagHandler;

class LinkToChatIntegrationTagHandler implements ITagHandler {

	/** @var TemplateParser */
	private TemplateParser $templateParser;

	public function __construct(
		private readonly string $iconFile,
		private readonly string $iconAltText,
		private readonly string $integrationName
	) {
		$this->templateParser = new TemplateParser(
			dirname( __DIR__, 2 ) . '/resources/templates'
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getRenderedContent( string $input, array $params, Parser $parser, PPFrame $frame ): string {
		global $wgExtensionAssetsPath;
		$imageUrl = $wgExtensionAssetsPath . '/ChatIntegration/resources/images/logos/' . $this->iconFile;

		$parser->getOutput()->setPageProperty( "bs-tag-linkto-$this->integrationName", 1 );

		$url = $params['url'] ?? '';
		$label = $params['label'] ?? '';
		$showIcon = $params['show-icon'] ?? true;
		$showInTitle = $params['show-in-title'] ?? false;
		$iconSize = $params['icon-size'] ?? LinkToChatIntegrationTag::DEFAULT_ICON_SIZE;

		if ( !$showIcon && empty( $label ) ) {
			$label = $url;
		}

		$html = $this->templateParser->processTemplate(
			'LinkToChat',
			[
				'url' => $url,
				'label' => $label,
				'icon' => $showIcon ? $imageUrl : false,
				'iconAlt' => $this->iconAltText,
				'iconSize' => $iconSize,
			]
		);

		if ( $showInTitle ) {
			$outputPage = $parser->getOutput();
			$outputPage->setIndicator( "$this->integrationName-indicator", $html );

			if ( !$this->isVeEdit() ) {
				return "";
			}
		}

		return $html;
	}

	/**
	 * Check if the current action is a ve edit
	 *
	 * @return bool
	 */
	private function isVeEdit(): bool {
		$request = RequestContext::getMain()->getRequest();
		$action = $request->getVal( 'action', $request->getVal( 'veaction' ) );

		return $action === 'edit' || $action === 'visualeditor';
	}
}
