( function( blocks, element, blockEditor, components ) {
	'use strict';
	var el = element.createElement;

	blocks.registerBlockType( 'agent-catalog/term-grid', {
		edit: function( props ) {
			var blockProps = blockEditor.useBlockProps();
			var a = props.attributes;
			var controls = el(
				blockEditor.InspectorControls,
				null,
				el(
					components.PanelBody,
					{ title: 'Сетка категорий' },
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
						min: 4,
						max: 40,
						onChange: function( v ) { props.setAttributes( { number: v } ); }
					} )
				)
			);
			return el( element.Fragment, null, controls, el( 'p', blockProps, el( 'em', null, '▦ Сетка категорий «' + a.taxonomy + '» — карточки терминов с числом репозиториев (данные на странице)' ) ) );
		},
		save: function() {
			return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components );
