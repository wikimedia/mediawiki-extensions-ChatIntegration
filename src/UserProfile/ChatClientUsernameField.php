<?php

namespace MediaWiki\Extension\ChatIntegration\UserProfile;

use MediaWiki\Extension\UserProfile\Field\ProfileField;
use MediaWiki\Language\Language;
use MediaWiki\Message\Message;

class ChatClientUsernameField extends ProfileField {

	/**
	 * @param Language $language
	 * @return Message
	 */
	public function getLabel( Language $language ): Message {
		return Message::newFromKey( 'chatintegration-userprofile-chatclientusername', $this->getLabelKey() );
	}
}
