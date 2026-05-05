<?php

namespace MediaWiki\Extension\ChatIntegration\Hooks;

use MediaWiki\Config\Config;
use MediaWiki\Hook\BeforePageDisplayHook;
use MediaWiki\Hook\SidebarBeforeOutputHook;

class AddSidebarShareMsTeams implements SidebarBeforeOutputHook, BeforePageDisplayHook {

	/** @var string */
	private const SHARE_BY_MSTEAMS_ID = 't-sharebymsteams';

	/** @var string */
	private const SHARE_BY_MSTEAMS_SCRIPT_SRC = 'https://teams.microsoft.com/share/launcher.js';

	/**
	 * @param Config $config
	 */
	public function __construct( private readonly Config $config ) {
	}

	/**
	 * @param \BlueSpice\Discovery\ITemplateDataProvider $registry
	 * @return void
	 */
	public function onBlueSpiceDiscoveryTemplateDataProviderAfterInit( $registry ): void {
		if ( !$this->config->get( 'ChatIntegrationSidebarShareMsTeams' ) ) {
			return;
		}

		$registry->register( 'panel/share', self::SHARE_BY_MSTEAMS_ID );
	}

	/**
	 * @inheritDoc
	 */
	public function onSidebarBeforeOutput( $skin, &$sidebar ): void {
		if ( !$this->config->get( 'ChatIntegrationSidebarShareMsTeams' ) ) {
			return;
		}

		$sidebar['TOOLBOX'][ self::SHARE_BY_MSTEAMS_ID ] = [
			'id' => self::SHARE_BY_MSTEAMS_ID,
			'text' => $skin->msg( 'chatintegration-sidebar-share-sharebymsteams-text' ),
			'title' => $skin->msg( 'chatintegration-sidebar-share-sharebymsteams-title' ),
			'class' => 'teams-share-button',
			'data' => [
				'href' => $skin->getTitle()->getFullURL()
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		if ( !$this->config->get( 'ChatIntegrationSidebarShareMsTeams' ) ) {
			return;
		}

		$scriptSrc = self::SHARE_BY_MSTEAMS_SCRIPT_SRC;
		$out->getCSP()->addScriptSrc( $scriptSrc );
		$out->addModuleStyles( 'ext.chatIntegration.sidebar.msTeamsShare' );
		$out->addScript(
			"<script async defer src='$scriptSrc'></script>"
		);
	}
}
