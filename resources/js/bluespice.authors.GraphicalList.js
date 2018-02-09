bs.util.registerNamespace( 'bs.authors' );

bs.authors.GraphicalList = function() {};
bs.authors.GraphicalList.prototype.getTitle = function() {
	return '';
};

bs.authors.GraphicalList.prototype.getActions = function() {
	return [];
};

bs.authors.GraphicalList.prototype.getBody = function() {
	var dfd = $.Deferred();

	var htmlForSitetools = mw.config.get( 'bsgAuthorsSitetools' );

	dfd.resolve( function() {
		var editors = htmlForSitetools['editors'];

		var html = '<div class="grapicallist-authors-body">';

		if( 'originator' in htmlForSitetools ) {
				html += '<div class="originator">'
					+ htmlForSitetools['originator'] + '</div>';

		}

		if( editors.length > 0) {
			html += '<div class="authors">'

			editors.forEach( function( value, index, array ){
				html += value;
			});

			html += '</div>';

			if( htmlForSitetools['more'] === true ) {
				html += '<div class="authors-more">'
					+ '<a href="' + mw.util.getUrl( this, { 'action': 'history' } ) + '">'
					+ '<i class="bs-authors-more-icon"></i>'
					+ '</a></div>';
			}

			html += '</div>';
		}

		return html;
	} );


	return dfd;
};

bs.authors.GraphicalListFactory = function() {
	return new bs.authors.GraphicalList();
};


$( document ).on( 'click', "*[data-target]", function(e){
	var target = $( this ).data( "target" );
	if( target === 'graphical-list-action-preview' ){
		$( '.grapicallist-authors-body .list' ).css( 'display', 'none' );
		$( '.grapicallist-authors-body .preview' ).css( 'display', 'block' );
	}
	if( target === 'graphical-list-action-list' ){
		$( '.grapicallist-authors-body .list' ).css( 'display', 'block' );
		$( '.grapicallist-authors-body .preview' ).css( 'display', 'none' );
	}
});