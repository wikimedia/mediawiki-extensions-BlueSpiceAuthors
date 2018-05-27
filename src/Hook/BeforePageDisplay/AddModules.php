<?php

namespace BlueSpice\Authors\Hook\BeforePageDisplay;

use BlueSpice\Hook\BeforePageDisplay;


class AddModules extends BeforePageDisplay {
	protected function doProcess() {

		if( \Authors::checkContext( $this->out ) === false ) {
			return true;
		}

		$this->out->addModules( 'ext.bluespice.authors' );
		return true;
	}
}
