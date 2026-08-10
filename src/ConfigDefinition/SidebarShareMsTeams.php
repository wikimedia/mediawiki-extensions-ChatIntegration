<?php

namespace MediaWiki\Extension\ChatIntegration\ConfigDefinition;

use BlueSpice\ConfigDefinition\BooleanSetting;
use BlueSpice\ConfigDefinition\IOverwriteGlobal;

class SidebarShareMsTeams extends BooleanSetting implements IOverwriteGlobal {

	/**
	 * @return array
	 */
	public function getPaths(): array {
		return [
			static::MAIN_PATH_FEATURE . '/' . static::FEATURE_SKINNING . '/ChatIntegration',
			static::MAIN_PATH_EXTENSION . '/ChatIntegration/' . static::FEATURE_SKINNING,
			static::MAIN_PATH_PACKAGE . '/' . static::PACKAGE_FREE . '/ChatIntegration',
		];
	}

	/**
	 * @return string
	 */
	public function getGlobalName() {
		return "wgChatIntegrationSidebarShareMsTeams";
	}

	/**
	 * @return string|null
	 */
	public function getLabelMessageKey() {
		return "pref-chatintegration-sidebar-share-via-chat";
	}

	/**
	 * @return string|null
	 */
	public function getHelpMessageKey() {
		return "pref-chatintegration-sidebar-share-via-chat-help";
	}
}
