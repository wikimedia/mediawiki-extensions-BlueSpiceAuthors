<?php

namespace BlueSpice\Authors\Hook\BeforePageDisplay;

use BlueSpice\Hook\BeforePageDisplay;
use BlueSpice\DynamicFileDispatcher\UrlBuilder;
use BlueSpice\DynamicFileDispatcher\Params;
use BlueSpice\DynamicFileDispatcher\UserProfileImage;

class FetchAuthors extends BeforePageDisplay {

	protected function skipProcessing() {
		$config = $this->getConfig();
		if ( $config->get( 'AuthorsShow' ) === false ) {
			return true;
		}
		$title = $this->out->getTitle();
		if ( !$title->exists() || !$title->userCan( 'read' ) ) {
			return true;
		}

		if ( $this->out->getRequest()->getVal( 'action', 'view' ) != 'view' ) {
			return true;
		}

		if ( in_array( $title->getNamespace(), array( NS_SPECIAL, NS_CATEGORY, NS_FILE ) ) ) {
			return true;
		}

		// Do not display if __NOAUTHORS__ keyword is found
		$noAuthors = \BsArticleHelper::getInstance( $title )->getPageProp( 'bs_noauthors' );
		if( $noAuthors === '' ) {
			return true;
		}

	}

	protected function doProcess() {

		$list = new \BlueSpice\Authors\AuthorsList(
			$this->out->getTitle(),
			$this->getConfig()->get( 'AuthorsBlacklist' ),
			$this->getConfig()->get( 'AuthorsLimit' )
		);

		$revision = $this->out->getTitle()->getFirstRevision();
		$originator = $list->getOriginator(
			$revision
		);

		$editors = $list->getEditors();

		$authors = [];

		if ( $originator !== '') {
			$user = \User::newFromName( $originator );
			$authors['originator'] = $this->makeEntry( $user );
		}

		$authors['editors'] = [];

		foreach ( $editors as $editor ) {
			$user = \User::newFromName( $editor );
			$authors['editors'][] = $this->makeEntry( $user );
		}

		$authors['more'] = $list->moreEditors();


		$this->out->addJsConfigVars( [
			'bsgAuthorsSitetools' => $authors
		] );

		return true;
	}

	/**
	 *
	 * @param \User $user
	 */
	protected function makeEntry( $user ) {
		$factory = \BlueSpice\Services::getInstance()->getBSRendererFactory();
		$image = $factory->get( 'userimage', new \BlueSpice\Renderer\Params( [
			'user' => $user,
			'width' => "48",
			'height' => "48"
		] ) );

		return $image->render();
	}
}
