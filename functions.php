<?php
/**
 * Agent Catalog theme functions and definitions.
 */

require_once get_theme_file_path( 'includes/class-mixed-results.php' );
require_once get_theme_file_path( 'includes/class-catalog-stats.php' );
require_once get_theme_file_path( 'includes/class-term-grid.php' );
require_once get_theme_file_path( 'includes/class-term-links.php' );
require_once get_theme_file_path( 'includes/class-search.php' );
Agent_Catalog_Search::init();

require_once get_theme_file_path( 'includes/class-provision.php' );
Agent_Catalog_Provision::init();

require_once get_theme_file_path( 'includes/class-legal.php' );
Agent_Catalog_Legal::init();

require_once get_theme_file_path( 'includes/class-blog-loop.php' );
Agent_Catalog_Blog_Loop::init();

if ( ! function_exists( 'agent_catalog_register_blocks' ) ) :
	function agent_catalog_register_blocks() {
		register_block_type( get_theme_file_path( 'blocks/mixed-results' ) );
		register_block_type( get_theme_file_path( 'blocks/catalog-stats' ) );
		register_block_type( get_theme_file_path( 'blocks/term-grid' ) );
		register_block_type( get_theme_file_path( 'blocks/term-links' ) );
		register_block_type( get_theme_file_path( 'blocks/nav-categories' ) );
	}
endif;
add_action( 'init', 'agent_catalog_register_blocks' );

/**
 * Card look variants for the 10-site rollout — restyle the plugin's shared
 * .lagr-card partial without forking it. Registered on core/query so a site
 * picks the look from the Query block's Styles panel; the CSS lives in
 * layouts.css keyed on the is-style-* class WP adds to the query wrapper.
 * "Default" stays unregistered (the plugin's own card styling is the default).
 */
/**
 * Emit a github_repo Query Loop block that reuses the plugin's own card partial
 * (the `lagr/repo-card` pattern) as its post template — the homepage variant
 * patterns build their grids through this so they can vary columns / ordering /
 * card look / leaderboard numbering without duplicating card markup or touching
 * the plugin. `order: score` rides the plugin's lagrOrderBy smuggle key (see
 * LAGR_Patterns::filter_score_order); the is-style-* class drives the card
 * variants registered above.
 */
if ( ! function_exists( 'agent_catalog_repo_query' ) ) :
	function agent_catalog_repo_query( $opts = array() ) {
		$o = wp_parse_args(
			$opts,
			array(
				'perPage'     => 6,
				'columns'     => 3,
				'order'       => 'score',
				'style'       => '',
				'leaderboard' => false,
				'pagination'  => false,
			)
		);

		$query = array(
			'perPage'  => (int) $o['perPage'],
			'pages'    => 1,
			'offset'   => 0,
			'postType' => 'github_repo',
			'order'    => 'desc',
			'orderBy'  => 'date',
			'inherit'  => false,
		);
		if ( 'score' === $o['order'] ) {
			$query['lagrOrderBy'] = 'score';
		}

		$classes = array( 'lagr-tile-grid-wrap' );
		if ( in_array( $o['style'], array( 'compact', 'minimal', 'featured' ), true ) ) {
			$classes[] = 'is-style-lagr-cards-' . $o['style'];
		}
		if ( $o['leaderboard'] ) {
			$classes[] = 'lagr-leaderboard';
		}
		$class_attr = esc_attr( implode( ' ', $classes ) );

		$markup  = '<!-- wp:query {"query":' . wp_json_encode( $query ) . ',"align":"wide","displayLayout":{"type":"flex","columns":' . (int) $o['columns'] . '},"className":"' . $class_attr . '"} -->';
		$markup .= '<div class="wp-block-query alignwide ' . $class_attr . '">';
		$markup .= '<!-- wp:post-template {"className":"lagr-tile-grid"} --><!-- wp:pattern {"slug":"lagr/repo-card"} /--><!-- /wp:post-template -->';
		if ( $o['pagination'] ) {
			$markup .= '<!-- wp:query-pagination --><!-- wp:query-pagination-previous /--><!-- wp:query-pagination-numbers /--><!-- wp:query-pagination-next /--><!-- /wp:query-pagination -->';
		}
		$markup .= '<!-- wp:query-no-results --><!-- wp:paragraph --><p>Пока ничего не собрано.</p><!-- /wp:paragraph --><!-- /wp:query-no-results -->';
		$markup .= '</div><!-- /wp:query -->';
		return $markup;
	}
endif;

if ( ! function_exists( 'agent_catalog_register_card_styles' ) ) :
	function agent_catalog_register_card_styles() {
		$styles = array(
			'lagr-cards-compact'  => 'Карточки: компактные',
			'lagr-cards-minimal'  => 'Карточки: список',
			'lagr-cards-featured' => 'Карточки: крупные',
		);
		foreach ( $styles as $name => $label ) {
			register_block_style(
				'core/query',
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}
	}
endif;
add_action( 'init', 'agent_catalog_register_card_styles' );

if ( ! function_exists( 'agent_catalog_editor_style' ) ) :
	function agent_catalog_editor_style() {
		add_editor_style( 'assets/css/editor-style.css' );
		add_editor_style( 'assets/css/layouts.css' );
	}
endif;
add_action( 'after_setup_theme', 'agent_catalog_editor_style' );

if ( ! function_exists( 'agent_catalog_enqueue_styles' ) ) :
	function agent_catalog_enqueue_styles() {
		wp_enqueue_style(
			'agent-catalog-style',
			get_theme_file_uri( 'style.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
		wp_style_add_data( 'agent-catalog-style', 'path', get_theme_file_path( 'style.css' ) );

		wp_enqueue_style(
			'agent-catalog-overrides',
			get_theme_file_uri( 'assets/css/theme-overrides.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);

		wp_enqueue_style(
			'agent-catalog-layouts',
			get_theme_file_uri( 'assets/css/layouts.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'agent_catalog_enqueue_styles' );

if ( ! function_exists( 'agent_catalog_block_styles' ) ) :
	function agent_catalog_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => 'Checkmark',
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'agent_catalog_block_styles' );

if ( ! function_exists( 'agent_catalog_pattern_categories' ) ) :
	function agent_catalog_pattern_categories() {
		register_block_pattern_category(
			'agent_catalog_page',
			array(
				'label'       => 'Agent Catalog',
				'description' => 'Header, footer and utility patterns for the Agent Catalog theme.',
			)
		);
	}
endif;
add_action( 'init', 'agent_catalog_pattern_categories' );
