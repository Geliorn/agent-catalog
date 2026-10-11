( function( blocks, element, blockEditor, components ) {
	'use strict';
	var el = element.createElement;

	blocks.registerBlockType( 'agent-catalog/nav-categories', {
		edit: function( props ) {
			var blockProps = blockEditor.useBlockProps();
			var a = props.attributes;
			var controls = el(
				blockEditor.InspectorControls,
				null,
				el(
					components.PanelBody,
					{ title: 'Меню категорий' },
					el( components.TextControl, {
						label: 'Заголовок',
						value: a.label,
						onChange: function( v ) { props.setAttributes( { label: v } ); }
					} ),
					el( components.SelectControl, {
						label: 'Таксономия',
						value: a.taxonomy,
						options: [
							{ label: 'Темы (repo_topic)', value: 'repo_topic' },
							{ label: 'Теги (post_tag)', value: 'post_tag' }
						],
						onChange: function( v ) { props.setAttributes( { taxonomy: v } ); }
					} ),
					el( components.RangeControl, {
						label: 'Сколько показать',
						value: a.number,
						min: 4,
						max: 30,
						onChange: function( v ) { props.setAttributes( { number: v } ); }
					} )
				)
			);
			return el( element.Fragment, null, controls, el( 'p', blockProps, el( 'em', null, '▾ ' + a.label + ' — выпадающее меню (данные на странице)' ) ) );
		},
		save: function() {
			return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components );
