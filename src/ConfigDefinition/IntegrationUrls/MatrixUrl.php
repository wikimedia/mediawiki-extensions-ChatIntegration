<?php

namespace MediaWiki\Extension\ChatIntegration\ConfigDefinition\IntegrationUrls;

use MediaWiki\Extension\ChatIntegration\Tag\LinkToMatrix;

class MatrixUrl extends IntegrationUrl {

	/**
	 * @return string
	 */
	public function getLabelMessageKey(): string {
		$integrationName = LinkToMatrix::INTEGRATION_NAME;

		return "pref-chatintegration-$integrationName-url";
	}

	/**
	 * @inheritDoc
	 */
	public function getGlobalName(): string {
		return "wgChatIntegrationMatrixUrl";
	}
}
