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
 * @package    BlueSpice_Extensions
 * @subpackage Authors
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v3
 * @filesource
 */

//Last review MRG (30.06.11 10:25)

/**
 * Base class for Authors extension
 * @package BlueSpice_Extensions
 * @subpackage Authors
 */
class Authors extends BsExtensionMW {

	/**
	 * Initialization of Authors extension
	 */
	protected function initExt() {
		// Hooks
		$this->setHook( 'BSInsertMagicAjaxGetData' );
		$this->setHook( 'BS:UserPageSettings', 'onUserPageSettings' );
		$this->setHook( 'PageContentSave' );
	}

	/**
	 * Inject tags into InsertMagic
	 * @param Object $oResponse reference
	 * $param String $type
	 * @return always true to keep hook running
	 */
	public function onBSInsertMagicAjaxGetData( &$oResponse, $type ) {
		if( $type != 'switches' ) return true;

		$oDescriptor = new stdClass();
		$oDescriptor->id = 'bs:authors';
		$oDescriptor->type = 'switch';
		$oDescriptor->name = 'NOAUTHORS';
		$oDescriptor->desc = wfMessage( 'bs-authors-switch-description' )->plain();
		$oDescriptor->code = '__NOAUTHORS__';
		$oDescriptor->previewable = false;
		$oResponse->result[] = $oDescriptor;

		return true;
	}

	/**
	 * Hook-handler for 'BS:UserPageSettings'
	 * @param User $oUser The current MediaWiki User object
	 * @param Title $oTitle The current MediaWiki Title object
	 * @param array $aSettingViews A list of View objects
	 * @return array The SettingsViews array with an andditional View object
	 */
	public function onUserPageSettings( $oUser, $oTitle, &$aSettingViews ){
		$oUserPageSettingsView = new ViewAuthorsUserPageProfileImageSetting();
		$oUserPageSettingsView->setCurrentUser( $oUser );
		$aSettingViews[] = $oUserPageSettingsView;
		return true;
	}

	/**
	 * Checks whether to show Authors or not.
	 * @return bool
	 */
	public static function checkContext( $out ) {
		$config = \MediaWiki\MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'bsg' );
		if ( $config->get( 'AuthorsShow' ) === false ) {
			return false;
		}

		$oTitle = $out->getTitle();
		if ( !is_object( $oTitle ) ) {
			return false;
		}

		if ( !$oTitle->exists() ) {
			return false;
		}

		// Do only display when user is allowed to read
		if ( !$oTitle->userCan( 'read' ) ) {
			return false;
		}

		// Do only display in view mode
		if ( $out->getRequest()->getVal( 'action', 'view' ) != 'view' ) {
			return false;
		}

		// Do not display on SpecialPages, CategoryPages or ImagePages
		if ( in_array( $oTitle->getNamespace(), array( NS_SPECIAL, NS_CATEGORY, NS_FILE ) ) ) {
			return false;
		}

		// Do not display if __NOAUTHORS__ keyword is found
		$vNoAuthors = BsArticleHelper::getInstance( $oTitle )->getPageProp( 'bs_noauthors' );
		if( $vNoAuthors === '' ) {
			return false;
		}

		return true;
	}

	/**
	 * Invalidates cache for authors
	 * @param WikiPage $wikiPage
	 * @param User $user
	 * @param Content $content
	 * @param type $summary
	 * @param type $isMinor
	 * @param type $isWatch
	 * @param type $section
	 * @param type $flags
	 * @param Status $status
	 * @return boolean
	 */
	public static function onPageContentSave( $wikiPage, $user, $content, $summary, $isMinor, $isWatch, $section, $flags, $status ) {
		BsCacheHelper::invalidateCache( BsCacheHelper::getCacheKey( 'BlueSpice', 'Authors', $wikiPage->getTitle()->getArticleID() ) );
		return true;
	}
}
