<?php

namespace BlueSpice\Authors\ConfigDefinition;

use BlueSpice\ConfigDefinition\IntSetting;

class AuthorsLimit extends IntSetting {

	public function getPaths() {
		return [
			static::MAIN_PATH_FEATURE . '/' . static::FEATURE_PERSONALISATION . '/BlueSpiceAuthors',
			static::MAIN_PATH_EXTENSION . '/BlueSpiceAuthors/' . static::FEATURE_PERSONALISATION ,
			static::MAIN_PATH_PACKAGE . '/' . static::PACKAGE_FREE . '/BlueSpiceAuthors',
		];
	}

	public function getLabelMessageKey() {
		return 'bs-authors-pref-limit';
	}
}
