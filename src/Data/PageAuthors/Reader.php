<?php

namespace BlueSpice\Authors\Data\PageAuthors;

use MediaWiki\Config\Config;
use MediaWiki\Context\IContextSource;
use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\DataStore\DatabaseReader;
use MWStake\MediaWiki\Component\DataStore\ReaderParams;
use Wikimedia\Rdbms\LoadBalancer;

class Reader extends DatabaseReader {

	/**
	 *
	 * @var LoadBalancer
	 */
	protected $loadBalancer = null;

	/**
	 *
	 * @param LoadBalancer $loadBalancer
	 * @param IContextSource|null $context
	 * @param Config|null $config
	 */
	public function __construct( $loadBalancer, ?IContextSource $context = null, ?Config $config = null ) {
		parent::__construct( $loadBalancer, $context, $config );
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 *
	 * @param ReaderParams $params
	 * @return PrimaryDataProvider
	 */
	protected function makePrimaryDataProvider( $params ) {
		$blacklist = $this->config->get( 'AuthorsBlacklist' );
		return new PrimaryDataProvider(
			$this->context->getTitle(),
			$blacklist,
			$this->loadBalancer
		);
	}

	/**
	 *
	 * @return SecondaryDataProvider
	 */
	protected function makeSecondaryDataProvider() {
		return new SecondaryDataProvider(
			MediaWikiServices::getInstance()->getService( 'BSRendererFactory' )
		);
	}

	/**
	 *
	 * @return Schema
	 */
	public function getSchema() {
		return new Schema();
	}
}
