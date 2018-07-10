Ext.onReady( function() {
	Ext.Loader.setPath(
		'BS.Authors',
		bs.em.paths.get( 'BlueSpiceAuthors' ) + '/resources/BS.Authors'
	);
});

(function( mw, $, bs, undefined ) {
	bs.util.registerNamespace( 'bs.authors.flyout' );

	bs.authors.flyout.makeItems = function() {
		if( mw.config.get( 'bsgAuthorsSitetools' ) !== null ) {
			return {
				centerRight: [
					Ext.create( 'BS.Authors.panel.Authors', {
						htmlForSitetools: mw.config.get( 'bsgAuthorsSitetools' )
					} )
				]
			}
		}

		return {};
	};

})( mediaWiki, jQuery, blueSpice );
