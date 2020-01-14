<?php

namespace BlueSpice\Authors\Tests;

use BlueSpice\Authors\SkipArticleInfoFlyoutModuleChecker;
use BlueSpice\Utility\PagePropHelper;
use Config;
use HashConfig;
use PHPUnit\Framework\TestCase;
use Title;
use WebRequest;

class SkipArticleInfoFlyoutModuleCheckerTest extends TestCase {

	/**
	 * @covers SkipArticleInfoFlyoutModuleChecker::__construct
	 */
	public function testConstruct() {
		$config = $this->createMock( Config::class );
		$title = $this->createMock( Title::class );
		$request  = $this->createMock( WebRequest::class );
		$pagePropHelper  = $this->createMock( PagePropHelper::class );

		$checker = new SkipArticleInfoFlyoutModuleChecker(
			$config,
			$title,
			$request,
			$pagePropHelper
		);

		$this->assertInstanceOf(
			'BlueSpice\\Authors\\SkipArticleInfoFlyoutModuleChecker',
			$checker
		);
	}

	/**
	 *
	 * @param array $authorsShowConfig
	 * @param boolean $titleExists
	 * @param boolean $userCanRead
	 * @param string $webAction
	 * @param int $titleNamespace
	 * @param string|null $noAuthorsPageProp
	 * @param boolean $expectation
	 * @param string $message
	 *
	 * @covers SkipArticleInfoFlyoutModuleChecker::shouldSkip
	 * @dataProvider provideShouldSkipData
	 */
	public function testShouldSkip( $authorsShowConfig, $titleExists, $userCanRead, $webAction,
			$titleNamespace, $noAuthorsPageProp, $expectation, $message ) {
		$config = new HashConfig( [
			'AuthorsShow' => $authorsShowConfig
		] );

		$title = $this->createMock( Title::class );
		$title->method( 'exists' )->willReturn( $titleExists );
		$title->method( 'userCan' )->willReturn( $userCanRead );
		$title->method( 'getNamespace' )->willReturn( $titleNamespace );

		$request = $this->createMock( 'WebRequest' );
		$request->method( 'getVal' )->willReturn( $webAction );

		$pagePropHelper = $this->createMock( 'BlueSpice\\Utility\\PagePropHelper' );
		$pagePropHelper->method( 'getPageProp' )->willReturn( $noAuthorsPageProp );

		$checker = new SkipArticleInfoFlyoutModuleChecker(
			$config,
			$title,
			$request,
			$pagePropHelper
		);

		$this->assertEquals( $expectation, $checker->shouldSkip(), $message );
	}

	/**
	 *
	 * @return array
	 */
	public function provideShouldSkipData() {
		return [
			[ true, true, true, 'view', NS_MAIN, null, false, 'Should succeed' ],
			[ false, true, true, 'view', NS_MAIN, null, true, 'Should bail out by config' ],
			[ true, false, true, 'view', NS_MAIN, null, true, 'Should bail out by not existing title' ],
			[ true, true, false, 'view', NS_MAIN, null, true, 'Should bail out by read permission' ],
			[ true, true, true, 'edit', NS_MAIN, null, true, 'Should bail out by web action' ],
			[ true, true, true, 'view', NS_CATEGORY, null, true, 'Should bail out by title namespace' ],
			[ true, true, true, 'view', NS_MAIN, '', true, 'Should bail out by magic word' ]
		];
	}
}
