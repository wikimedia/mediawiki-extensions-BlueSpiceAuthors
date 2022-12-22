<?php

namespace BlueSpice\Authors\Data\PageAuthors;

use BlueSpice\Authors\AuthorsList;
use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\DataStore\IPrimaryDataProvider;
use ReaderParams;
use Title;
use Wikimedia\Rdbms\LoadBalancer;

class PrimaryDataProvider implements IPrimaryDataProvider {

	/**
	 *
	 * @var Record[]
	 */
	protected $data = [];

	/**
	 *
	 * @var Title
	 */
	protected $title = null;

	/**
	 *
	 * @var string[]
	 */
	protected $authorsBlacklist = [];

	/**
	 *
	 * @var LoadBalancer
	 */
	protected $loadBalancer = null;

	/**
	 *
	 *
	 * @param Title $title
	 * @param string[] $blacklist
	 * @param LoadBalancer $loadBalancer
	 */
	public function __construct( $title, $blacklist, $loadBalancer ) {
		$this->title = $title;
		$this->loadBalancer = $loadBalancer;
		$this->authorsBlacklist = $blacklist;
	}

	/**
	 *
	 * @param ReaderParams $params
	 * @return Record[]
	 */
	public function makeData( $params ) {
		$list = new AuthorsList( $this->title, $this->authorsBlacklist );

		$services = MediaWikiServices::getInstance();
		$firstRev = $services->getRevisionLookup()
			->getFirstRevision( $this->title->toPageIdentity() );
		$originator = $list->getOriginator( $firstRev );
		$editors = $list->getEditors();

		if ( $originator !== '' ) {
			$user = $userFactory->newFromName( $originator );
			if ( $user instanceof \User ) {
				$this->appendRowToData( [
					'user_name' => $user->getName(),
					'author_type' => 'originator'
				] );
			}
		}

		foreach ( $editors as $editor ) {
			$user = $userFactory->newFromName( $editor );
			if ( $user instanceof \User === false ) {
				continue;
			}
			$this->appendRowToData( [
				'user_name' => $user->getName(),
				'author_type' => 'editor'
			] );
		}

		return $this->data;
	}

	/**
	 *
	 * @param array $data
	 */
	protected function appendRowToData( $data ) {
		$this->data[] = new Record( (object)[
			Record::USER_NAME => $data['user_name'],
			Record::AUTHOR_TYPE => $data['author_type']
		] );
	}
}
