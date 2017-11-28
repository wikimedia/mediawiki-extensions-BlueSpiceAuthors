<?php

namespace BlueSpice\Authors\ConfigDefinition;

use BlueSpice\ConfigDefinition\ArraySetting;

class AuthorsLimit extends ArraySetting {
	public function getLabelMessageKey() {
		return 'bs-authors-pref-limit';
	}
}