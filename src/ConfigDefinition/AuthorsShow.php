<?php

namespace BlueSpice\Authors\ConfigDefinition;

use BlueSpice\ConfigDefinition\BooleanSetting;

class AuthorsShow extends BooleanSetting {
	public function getLabelMessageKey() {
		return 'bs-authors-pref-show';
	}
}