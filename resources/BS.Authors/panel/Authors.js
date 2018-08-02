Ext.define( 'BS.Authors.panel.Authors', {
	extend: 'Ext.Panel',
	cls: 'bs-authors-flyout-authors',
	minHeight: 180,
	htmlForSitetools: [],
	title: mw.message( 'bs-authors-flyout-title' ).plain(),
	initComponent: function () {
		var editors = this.htmlForSitetools['editors'];

		var html = '<div class="flyout-authors-body">';

		if( 'originator' in this.htmlForSitetools ) {
			html += '<div class="originator">'
				+ this.htmlForSitetools['originator'] + '</div>';

		}

		if( editors.length > 0 ) {
			html += '<div class="authors">';

			editors.forEach( function( value, index, array ){
				html += value;
			});

			html += '</div>';

			if( this.htmlForSitetools['more'] === true ) {
				html += '<div class="authors-more">'
					+ '<a href="' + mw.util.getUrl( this, { 'action': 'history' } ) + '">'
					+ '<i class="bs-authors-more-icon"></i>'
					+ '</a></div>';
			}
		}
		html += '</div>';

		this.html = html;

		this.callParent( arguments );
	}
});
