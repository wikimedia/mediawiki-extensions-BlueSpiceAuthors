<?php
/**
 * Renders the profile image frame on the users page.
 *
 * Part of BlueSpice MediaWiki
 *
 * @author     Robert Vogel <vogel@hallowelt.com>

 * @package    BlueSpice_Extensions
 * @subpackage Authors
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v3
 * @filesource
 */

// Last review MRG (30.06.11 10:25)

/**
 * This view renders the profile image frame on the users page.
 * @package    BlueSpice_Extensions
 * @subpackage Authors
 */
class ViewAuthorsUserPageProfileImageSetting extends ViewBaseElement {
	/*
	 * @var User $oUser The User object of the current user.
	 */
	protected $oUser = null;

	/**
	 * This method actually generates the output
	 * @param mixed $params Comes from base class definition. Not used in this implementation.
	 * @return string HTML output
	 */
	public function  execute( $params = false ) {

		$factory = \BlueSpice\Services::getInstance()->getBSRendererFactory();
		$renderer = $factory->get( 'userimage', new \BlueSpice\Renderer\Params([
			\BlueSpice\Renderer\UserImage::PARAM_USER => $this->oUser,
			\BlueSpice\Renderer\UserImage::PARAM_WIDTH => 64,
			\BlueSpice\Renderer\UserImage::PARAM_HEIGHT => 64,
		]));
		$out = \Html::openElement( 'div', [
			'id' => 'bs-authors-imageform',
			'class' => 'bs-userpagesettings-item',
		]);
		$out .= $renderer->render();
		$out .= \Html::element(
			'div',
			[ 'class' => 'bs-user-label' ],
			wfMessage( 'bs-authors-profileimage-change' )->plain()
		);
		$out .= \Html::closeElement( 'div' );
		return $out;
	}

	/**
	 * Setter for internal User object.
	 * @param User $oUser The MediaWiki User object the profile image frame should be rendered for.
	 */
	public function setCurrentUser( $oUser ) {
		$this->oUser = $oUser;
	}
}
