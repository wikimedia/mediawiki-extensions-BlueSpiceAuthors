<?php

namespace BlueSpice\Authors\Hook\SkinTemplateOutputPageBeforeExec;

use BlueSpice\Hook\SkinTemplateOutputPageBeforeExec;
use BlueSpice\DynamicFileDispatcher\UrlBuilder;
use BlueSpice\DynamicFileDispatcher\Params;
use BlueSpice\DynamicFileDispatcher\UserProfileImage;
use BlueSpice\SkinData;

class AddAuthors extends SkinTemplateOutputPageBeforeExec {
	protected function doProcess() {

		if( \Authors::checkContext( $this->getContext()->getOutput() ) === false ) {
			return true;
		}

		$icon = \Html::element( 'i', array(), '' );

		$html = \Html::rawElement(
			'a',
			[
				'href' => '#',
				'title' => wfMessage( 'bs-authors-navigation-link-title' )->text(),
				'data-graphicallist-callback' => 'authors-list',
				'data-graphicallist-direction' => 'west'
			],
			$icon . wfMessage( 'bs-authors-navigation-link-text' )->text()
		);

		$this->mergeSkinDataArray(
			SkinData::PAGE_INFOS_PANEL,
			[
				'bs-authors' => [
					'position' => 10,
					'label' => 'bs-authors',
					'type' => 'html',
					'content' => $html
				]
			]
		);

		return true;
	}
}