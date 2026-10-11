( function( blocks, element, blockEditor ) {
	'use strict';
	var el = element.createElement;

	blocks.registerBlockType( 'agent-catalog/mixed-results', {
		edit: function() {
			var blockProps = blockEditor.useBlockProps();
			return el( 'p', blockProps, el( 'em', null, '☰ Результаты поиска/тега — репозитории, поставщики, блог (собственный виджет, содержимое — на реальной странице)' ) );
		},
		// Dynamic block — actual markup comes from render.php on the server.
		save: function() {
			return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );
