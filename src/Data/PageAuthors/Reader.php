<?php

namespace BlueSpice\Authors\Data\PageAuthors;

use BlueSpice\Data\DatabaseReader;
use BlueSpice\Data\ReaderParams;
use BlueSpice\Services;
use Config;
use IContextSource;
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
	public function __construct( $loadBalancer, IContextSource $context = null,
			Config $config = null ) {
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
			Services::getInstance()->getBSRendererFactory()
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
