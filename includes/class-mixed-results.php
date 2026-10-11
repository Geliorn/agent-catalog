<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Search results and tag archives mix github_repo/contractor/post content
 * together (shared `post_tag` taxonomy, sitewide search) — the generic
 * blog-style listing (title + excerpt) that search.html/archive.html used
 * for everything made a repo's raw README dump the same shape as a one-line
 * blog teaser, which read as broken rather than "a lot of content".
 *
 * In search all three blocks are shown together (repositories, partners,
 * articles), each as a capped preview list with a result count — no
 * pagination, so the whole picture is visible at once; an empty block shows
 * its count (0) and a short note rather than disappearing, so the three-block
 * layout stays stable across queries. Tag archives show repositories +
 * articles (contractors don't use post_tag). Repositories reuse the plugin's
 * own card partial (minimal list style); partners and articles are plain
 * title+excerpt rows. Lives in the theme since it has to know all three types.
 */
class Agent_Catalog_Mixed_Results {

	const REPO_CAP    = 8;
	const PARTNER_CAP = 6;
	const BLOG_CAP    = 6;

	public static function render() {
		if ( is_search() ) {
			$context  = array( 'search' => get_search_query() );
			$sections = array( 'repo', 'partner', 'blog' );
		} elseif ( is_tag() ) {
			$tag = get_queried_object();
			if ( ! ( $tag instanceof WP_Term ) ) {
				return do_blocks( self::fallback_markup() );
			}
			$context = array( 'taxQuery' => array( 'post_tag' => array( $tag->term_id ) ) );
			// contractor doesn't use post_tag — a partner block here could never
			// have results, so the tag archive is repositories + articles only.
			$sections = array( 'repo', 'blog' );
		} else {
			// Any other archive this generic template still has to cover
			// (category, date, author) — same plain blog listing as before.
			return do_blocks( self::fallback_markup() );
		}

		$markup = '';
		foreach ( $sections as $section ) {
			if ( 'repo' === $section ) {
				$markup .= self::repo_section( $context );
			} elseif ( 'partner' === $section ) {
				$markup .= self::partner_section( $context );
			} else {
				$markup .= self::blog_section( $context );
			}
		}

		// Native search (augmented to match taxonomy terms, see
		// Agent_Catalog_Search) — the wp:query blocks search by `s` directly, no
		// index or result-rewriting needed.
		return do_blocks( $markup );
	}

	private static function fallback_markup() {
		return '<!-- wp:pattern {"slug":"agent-catalog/template-query-loop"} /-->';
	}

	private static function count( $post_type, $context ) {
		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => false,
		);
		if ( ! empty( $context['taxQuery'] ) ) {
			foreach ( $context['taxQuery'] as $taxonomy => $terms ) {
				$args['tax_query'][] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
					'taxonomy' => $taxonomy,
					'terms'    => $terms,
				);
			}
		}

		if ( ! empty( $context['search'] ) ) {
			$args['s'] = $context['search'];
		}
		return (int) ( new WP_Query( $args ) )->found_posts;
	}

	/** Same shape core's Query block itself expects — see build_query_vars_from_query_block() — so we can hand it straight to a real wp:query block instead of re-implementing query resolution. */
	private static function query_json( $post_type, $per_page, $context ) {
		$query = array(
			'perPage'  => $per_page,
			'pages'    => 0,
			'offset'   => 0,
			'postType' => $post_type,
			'order'    => 'desc',
			'orderBy'  => 'date',
			'inherit'  => false,
		);
		if ( ! empty( $context['search'] ) ) {
			$query['search'] = $context['search'];
		}
		if ( ! empty( $context['taxQuery'] ) ) {
			$query['taxQuery'] = $context['taxQuery'];
		}
		return wp_json_encode( $query );
	}

	/** Section heading carrying the total match count, e.g. "Репозитории 29". */
	private static function heading( $label, $count ) {
		return '<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">' . esc_html( $label )
			. ' <span class="lagr-result-count">' . (int) $count . '</span></h2><!-- /wp:heading -->';
	}

	private static function empty_note() {
		return '<!-- wp:paragraph {"className":"lagr-result-empty"} --><p class="lagr-result-empty">По этому запросу ничего не найдено.</p><!-- /wp:paragraph -->';
	}

	/** Repositories — minimal single-column list (reuses the plugin's card partial), capped, no pagination. */
	private static function repo_section( $context ) {
		$count  = self::count( 'github_repo', $context );
		$markup = self::heading( 'Репозитории', $count );
		if ( $count < 1 ) {
			return $markup . self::empty_note();
		}

		return $markup
			. '<!-- wp:query {"align":"wide","query":' . self::query_json( 'github_repo', self::REPO_CAP, $context ) . ',"displayLayout":{"type":"flex","columns":1},"className":"lagr-tile-grid-wrap is-style-lagr-cards-minimal"} -->'
			. '<div class="wp-block-query alignwide lagr-tile-grid-wrap is-style-lagr-cards-minimal">'
			. '<!-- wp:post-template {"className":"lagr-tile-grid"} --><!-- wp:pattern {"slug":"lagr/repo-card"} /--><!-- /wp:post-template -->'
			. '</div><!-- /wp:query -->';
	}

	/** Suppliers — plain title + excerpt + type/specialization list, capped, no pagination. */
	private static function partner_section( $context ) {
		$count  = self::count( 'contractor', $context );
		$markup = self::heading( 'Поставщики', $count );
		if ( $count < 1 ) {
			return $markup . self::empty_note();
		}

		return $markup
			. '<!-- wp:query {"align":"wide","query":' . self::query_json( 'contractor', self::PARTNER_CAP, $context ) . ',"className":"lagr-result-list"} -->'
			. '<div class="wp-block-query alignwide lagr-result-list">'
			. '<!-- wp:post-template -->'
			. self::list_row(
				'<!-- wp:post-title {"isLink":true,"fontSize":"medium"} /-->'
				. '<!-- wp:post-excerpt {"excerptLength":22,"moreText":"Подробнее →","fontSize":"small"} /-->'
				. '<!-- wp:post-terms {"term":"contractor_type","className":"pc-type-pill","fontSize":"small"} /-->'
				. '<!-- wp:post-terms {"term":"contractor_specialization","className":"lagr-plain-term","fontSize":"small"} /-->'
			)
			. '<!-- /wp:post-template -->'
			. '</div><!-- /wp:query -->';
	}

	/** Articles — plain title + excerpt + date list, capped, no pagination. */
	private static function blog_section( $context ) {
		$count  = self::count( 'post', $context );
		$markup = self::heading( 'Статьи', $count );
		if ( $count < 1 ) {
			return $markup . self::empty_note();
		}

		return $markup
			. '<!-- wp:query {"align":"wide","query":' . self::query_json( 'post', self::BLOG_CAP, $context ) . ',"className":"lagr-result-list"} -->'
			. '<div class="wp-block-query alignwide lagr-result-list">'
			. '<!-- wp:post-template -->'
			. self::list_row(
				'<!-- wp:post-terms {"term":"category","fontSize":"small"} /-->'
				. '<!-- wp:post-title {"isLink":true,"fontSize":"medium"} /-->'
				. '<!-- wp:post-excerpt {"excerptLength":28,"moreText":"Читать далее →","fontSize":"small"} /-->'
				. '<!-- wp:post-date {"fontSize":"small"} /-->'
			)
			. '<!-- /wp:post-template -->'
			. '</div><!-- /wp:query -->';
	}

	/** One list row: a constrained group with a hairline bottom divider wrapping the given inner blocks. */
	private static function list_row( $inner ) {
		return '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"bottom":{"color":"var:preset|color|light","width":"1px"}}},"layout":{"type":"constrained"}} -->'
			. '<div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--light);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)">'
			. $inner
			. '</div><!-- /wp:group -->';
	}
}
