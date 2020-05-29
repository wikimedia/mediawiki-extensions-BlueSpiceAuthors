<?php
/**
 * Authors extension for BlueSpice
 *
 * Displays authors of an article with image.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * This file is part of BlueSpice MediaWiki
 * For further information visit http://www.bluespice.com
 *
 * @author     Markus Glaser <glaser@hallowelt.com>
 * @author     Robert Vogel <vogel@hallowelt.com>
 * @author     Peter Böhm <boehm@hallowelt.com>
 * @package    BlueSpice_Extensions
 * @subpackage Authors
 * @copyright  Copyright (C) 2018 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GPL-3.0-only
 * @filesource
 */

namespace BlueSpice\Authors;

use BlueSpice\Services;
use IContextSource;

class Extension extends \BlueSpice\Extension {

	/**
	 * Checks if the Readers segment should be added to the flyout
	 *
	 * @param IContextSource $context
	 * @return bool
	 */
	public static function flyoutCheckPermissions( IContextSource $context ) {
		$currentTitle = $context->getTitle();

		if ( $currentTitle->isSpecialPage() ) {
			return false;
		}

		$config = Services::getInstance()->getConfigFactory()->makeConfig( 'bsg' );
		$excludeNS = $config->get( 'AuthorsNamespaceBlacklist' );
		if ( in_array( $currentTitle->getNamespace(), $excludeNS ) ) {
			return false;
		}

		if ( $currentTitle->userCan( 'read' ) == false ) {
			return false;
		}

		return true;
	}

}
