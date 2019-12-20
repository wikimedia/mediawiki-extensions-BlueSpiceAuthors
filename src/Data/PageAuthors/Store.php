<?php

namespace BlueSpice\Authors\Data\PageAuthors;

use BlueSpice\Data\NoWriterException;
use BlueSpice\Services;
use IContextSource;

class Store implements \BlueSpice\Data\IStore {

	/**
	 *
	 * @var IContextSource
	 */
	protected $context = null;

	/**
	 *
	 * @param IContextSource $context
	 */
	public function __construct( $context ) {
		$this->context = $context;
	}

	/**
	 *
	 * @return Reader
	 */
	public function getReader() {
		return new Reader(
			\MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer(),
			$this->context,
			Services::getInstance()->getConfigFactory()->makeConfig( 'bsg' )
		);
	}

	/**
	 *
	 * @throws NoWriterException
	 */
	public function getWriter() {
		throw new NoWriterException();
	}
}
