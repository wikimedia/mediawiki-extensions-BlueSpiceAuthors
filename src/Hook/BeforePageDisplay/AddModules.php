<?php

namespace BlueSpice\Authors\Hook\BeforePageDisplay;

use BlueSpice\Hook\BeforePageDisplay;


class AddModules extends BeforePageDisplay {
	protected function doProcess() {

		if( \Authors::checkContext( $this->getContext()->getOutput() ) === false ) {
			return true;
		}

		$this->getContext()->getOutput()->addModules( 'ext.bluespice.authors' );
		return true;
	}
}
