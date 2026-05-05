<?php

namespace MediaWiki\Extension\ChatIntegration\ConfigDefinition\IntegrationUrls;

use BlueSpice\ConfigDefinition\IOverwriteGlobal;
use BlueSpice\ConfigDefinition\StringSetting;

abstract class IntegrationUrl extends StringSetting implements IOverwriteGlobal {
	/**
	 * @return array
	 */
	public function getPaths(): array {
		return [
			static::MAIN_PATH_FEATURE . '/' . static::FEATURE_COMMUNICATION . '/ChatIntegration',
			static::MAIN_PATH_EXTENSION . '/ChatIntegration/' . static::FEATURE_COMMUNICATION,
			static::MAIN_PATH_PACKAGE . '/' . static::PACKAGE_FREE . '/ChatIntegration',
		];
	}
}
