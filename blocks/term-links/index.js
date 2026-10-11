( function( blocks, element, blockEditor, components ) {
	'use strict';
	var el = element.createElement;

	blocks.registerBlockType( 'agent-catalog/term-links', {
		edit: function( props ) {
			var blockProps = blockEditor.useBlockProps();
			var a = props.attributes;
			var controls = el(
				blockEditor.InspectorControls,
				null,
				el(
					components.PanelBody,
					{ title: 'Ссылки на термины' },
					el( components.SelectControl, {
						label: 'Таксономия',
						value: a.taxonomy,
						options: [
							{ label: 'Темы (repo_topic)', value: 'repo_topic' },
							{ label: 'Теги (post_tag)', value: 'post_tag' },
							{ label: 'Языки (repo_language)', value: 'repo_language' },
							{ label: 'Лицензии (repo_license)', value: 'repo_license' }
						],
						onChange: function( v ) { props.setAttributes( { taxonomy: v } ); }
					} ),
					el( components.RangeControl, {
						label: 'Сколько показать',
						value: a.number,
						min: 3,
						max: 30,
						onChange: function( v ) { props.setAttributes( { number: v } ); }
					} ),
					el( components.ToggleControl, {
						label: 'Показывать счётчик',
						checked: a.showCount,
						onChange: function( v ) { props.setAttributes( { showCount: v } ); }
					} )
				)
			);
			return el( element.Fragment, null, controls, el( 'p', blockProps, el( 'em', null, '☰ Ссылки на термины «' + a.taxonomy + '» (данные на странице)' ) ) );
		},
		save: function() {
			return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components );
