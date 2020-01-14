<?php

namespace BlueSpice\Authors;

use BlueSpice\Services;
use BlueSpice\Utility\PagePropHelper;
use Config;
use Title;
use WebRequest;

class SkipArticleInfoFlyoutModuleChecker {

	/**
	 *
	 * @var Config
	 */
	private $config = null;

	/**
	 *
	 * @var Title
	 */
	private $title = null;

	/**
	 *
	 * @var WebRequest
	 */
	private $request = null;

	/**
	 *
	 * @var PagePropHelper
	 */
	private $pagePropHelper = null;

	/**
	 *
	 * @param IContextSource $context
	 * @return bool
	 */
	public static function callback( $context ) {
		$services = Services::getInstance();
		$config = $services->getConfigFactory()->makeConfig( 'bsg' );
		$title = $context->getTitle();
		$request = $context->getRequest();
		$pagePropHelper = $services->getBSUtilityFactory()->getPagePropHelper( $title );

		$checker = new static( $config, $title, $request, $pagePropHelper );

		// This is a design issue in `BlueSpice\ArticleInfo\Panel\Flyout`. If the "skip-callback"
		//returns `false` the module is being skipped, not the other way round.
		$result = !$checker->shouldSkip();
		return $result;
	}

	/**
	 *
	 * @param Config $config
	 * @param Title $title
	 * @param WebRequest $request
	 * @param PagePropHelper $pagePropHelper
	 */
	public function __construct( $config, $title, $request, $pagePropHelper ) {
		$this->config = $config;
		$this->title = $title;
		$this->request = $request;
		$this->pagePropHelper = $pagePropHelper;
	}

	/**
	 *
	 * @return bool
	 */
	public function shouldSkip() {
		if ( $this->config->get( 'AuthorsShow' ) === false ) {
			return true;
		}

		if ( !$this->title->exists() || !$this->title->userCan( 'read' ) ) {
			return true;
		}

		if ( $this->request->getVal( 'action', 'view' ) != 'view' ) {
			return true;
		}

		if ( in_array( $this->title->getNamespace(), [ NS_SPECIAL, NS_CATEGORY, NS_FILE ] ) ) {
			return true;
		}

		// Do not display if __NOAUTHORS__ keyword is found
		$noAuthors = $this->pagePropHelper->getPageProp( 'bs_noauthors' );
		if ( $noAuthors === '' ) {
			return true;
		}
		return false;
	}
}
