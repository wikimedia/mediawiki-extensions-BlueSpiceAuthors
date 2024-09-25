(function( mw, $, bs, undefined ) {
	bs.util.registerNamespace( 'bs.authors.info' );

	bs.authors.info.AuthorsInformationPage = function AuthorsInformationPage( name, config ) {
		this.authorGrid = null;
		bs.authors.info.AuthorsInformationPage.super.call( this, name, config );
	};

	OO.inheritClass( bs.authors.info.AuthorsInformationPage, StandardDialogs.ui.BasePage );

	bs.authors.info.AuthorsInformationPage.prototype.setupOutlineItem = function () {
		bs.authors.info.AuthorsInformationPage.super.prototype.setupOutlineItem.apply( this, arguments );

		if ( this.outlineItem ) {
			this.outlineItem.setLabel( mw.message( 'bs-authors-info-dialog' ).plain() );
		}
	};

	bs.authors.info.AuthorsInformationPage.prototype.setup = function () {
		return;
	};

	bs.authors.info.AuthorsInformationPage.prototype.onInfoPanelSelect = function () {
		var me = this;
		if ( me.authorGrid === null ){
			mw.loader.using( [ 'ext.oOJSPlus.data', 'oojs-ui.styles.icons-user' ] ).done( function () {
				bs.api.store.getData( 'pageauthors' ).done( function ( data ) {
					me.authorGrid = new OOJSPlus.ui.data.GridWidget( {
						columns: {
							user_name: {
								headerText: mw.message( 'bs-authors-info-dialog-grid-column-author' ).text(),
								type: 'user',
								showImage: true
							},
							author_type: {
								headerText: mw.message( 'bs-authors-info-dialog-grid-column-type' ).text(),
								type: "text"
							}
						},
						data: data.results
					} );
					me.$element.append( me.authorGrid.$element );
				} )
		 } )
		}
	}

	registryPageInformation.register( 'authors_infos', bs.authors.info.AuthorsInformationPage );

})( mediaWiki, jQuery, blueSpice );
