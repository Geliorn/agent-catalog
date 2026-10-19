<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A plain list of taxonomy-term links (optionally with counts) — the compact,
 * text-link counterpart to term-grid's cards. Used for footer "sitemap"
 * columns and anywhere a simple list of top topics/tags is wanted. Real term
 * data per render, so the links track the catalog instead of freezing.
 */
class Agent_Catalog_Term_Links {

	public static function render( $attributes = array() ) {
		$taxonomy   = isset( $attributes['taxonomy'] ) ? sanitize_key( $attributes['taxonomy'] ) : 'repo_topic';
		$number     = isset( $attributes['number'] ) ? absint( $attributes['number'] ) : 8;
		$show_count = ! empty( $attributes['showCount'] );
		$order_by   = isset( $attributes['orderBy'] ) ? (string) $attributes['orderBy'] : 'count';
		$pills      = isset( $attributes['display'] ) && 'pills' === $attributes['display'];
		$all_label  = isset( $attributes['allLabel'] ) ? trim( (string) $attributes['allLabel'] ) : '';

		$class   = 'lagr-term-links' . ( $pills ? ' is-pills' : '' );
		$wrapper = function_exists( 'get_block_wrapper_attributes' )
			? get_block_wrapper_attributes( array( 'class' => $class ) )
			: 'class="' . esc_attr( $class ) . '"';

		return self::list_html( $taxonomy, $number, $show_count, $wrapper, $order_by, $all_label );
	}

	/**
	 * The bare `<ul class="lagr-term-links">…</ul>`, reusable outside block
	 * render (e.g. the nav-categories dropdown). `$ul_attrs` is the attribute
	 * string for the `<ul>`. `$order_by` is 'count' (default) or 'likes' — the
	 * latter ranks terms by the total votes (_lagr_rating_count) of the repos
	 * carrying them, falling back to count when there are no votes; 'posts'
	 * counts blog posts only (post_tag is shared with github_repo, so its plain
	 * count would rank repo tags in a blog sidebar). A non-empty `$all_label`
	 * prepends an "all" link to the blog index; the term being viewed (or "all"
	 * on the blog index) gets `is-current`.
	 */
	public static function list_html( $taxonomy, $number = 8, $show_count = false, $ul_attrs = 'class="lagr-term-links"', $order_by = 'count', $all_label = '' ) {
		$taxonomy = sanitize_key( $taxonomy );
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return '';
		}

		if ( 'likes' === $order_by ) {
			$terms = self::terms_by_likes( $taxonomy, (int) $number );
		} elseif ( 'posts' === $order_by ) {
			$terms = self::terms_by_post_type( $taxonomy, (int) $number, 'post' );
		} else {
			$terms = self::terms_by_count( $taxonomy, (int) $number );
		}

		if ( empty( $terms ) ) {
			return '';
		}

		$current = ( is_category() || is_tag() || is_tax() ) ? (int) get_queried_object_id() : 0;

		$items = '';
		if ( '' !== $all_label ) {
			$blog_page = (int) get_option( 'page_for_posts' );
			$all_url   = $blog_page ? get_permalink( $blog_page ) : home_url( '/' );
			$all_count = $show_count
				? ' <span class="lagr-term-links-count">' . esc_html( number_format_i18n( (int) wp_count_posts( 'post' )->publish ) ) . '</span>'
				: '';
			$items    .= '<li' . ( is_home() ? ' class="is-current"' : '' ) . '><a href="' . esc_url( $all_url ) . '"' . ( is_home() ? ' aria-current="page"' : '' ) . '>' . esc_html( $all_label ) . $all_count . '</a></li>';
		}
		foreach ( $terms as $term ) {
			$link = get_term_link( (int) $term->term_id, $taxonomy );
			if ( is_wp_error( $link ) ) {
				continue;
			}
			$count = $show_count
				? ' <span class="lagr-term-links-count">' . esc_html( number_format_i18n( $term->cnt ) ) . '</span>'
				: '';
			$is_cur = ( (int) $term->term_id === $current );
			$items .= '<li' . ( $is_cur ? ' class="is-current"' : '' ) . '><a href="' . esc_url( $link ) . '"' . ( $is_cur ? ' aria-current="page"' : '' ) . '>' . esc_html( $term->name ) . $count . '</a></li>';
		}

		if ( '' === $items ) {
			return '';
		}

		return '<ul ' . $ul_attrs . '>' . $items . '</ul>';
	}

	/** Normalised {term_id, name, cnt} rows ordered by term count. */
	private static function terms_by_count( $taxonomy, $number ) {
		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'DESC',
		);
		if ( $number > 0 ) {
			$args['number'] = $number;
		}
		$terms = get_terms( $args );
		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}
		$rows = array();
		foreach ( $terms as $term ) {
			$rows[] = (object) array(
				'term_id' => $term->term_id,
				'name'    => $term->name,
				'cnt'     => $term->count,
			);
		}
		return $rows;
	}

	/**
	 * {term_id, name, cnt} rows where cnt counts published posts of one type.
	 * The default category ("Без рубрики") is left out — it is a fallback
	 * bucket, not a rubric worth a link.
	 */
	private static function terms_by_post_type( $taxonomy, $number, $post_type ) {
		global $wpdb;
		$limit   = $number > 0 ? $number : 100;
		$exclude = 'category' === $taxonomy ? (int) get_option( 'default_category' ) : 0;

		// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT t.term_id AS term_id, t.name AS name, COUNT(DISTINCT p.ID) AS cnt
				 FROM {$wpdb->terms} t
				 INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id AND tt.taxonomy = %s
				 INNER JOIN {$wpdb->term_relationships} tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
				 INNER JOIN {$wpdb->posts} p ON p.ID = tr.object_id AND p.post_status = 'publish' AND p.post_type = %s
				 WHERE t.term_id <> %d
				 GROUP BY t.term_id, t.name
				 ORDER BY cnt DESC, t.name ASC
				 LIMIT %d",
				$taxonomy,
				$post_type,
				$exclude,
				$limit
			)
		);
		// phpcs:enable

		return is_array( $rows ) ? $rows : array();
	}

	/** {term_id, name, cnt} rows ordered by total repo votes (likes), then count. */
	private static function terms_by_likes( $taxonomy, $number ) {
		global $wpdb;
		$limit = $number > 0 ? $number : 100;

		// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT t.term_id AS term_id, t.name AS name, tt.count AS cnt,
				        COALESCE(SUM(CAST(pm.meta_value AS UNSIGNED)),0) AS likes
				 FROM {$wpdb->terms} t
				 INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id AND tt.taxonomy = %s
				 INNER JOIN {$wpdb->term_relationships} tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
				 INNER JOIN {$wpdb->posts} p ON p.ID = tr.object_id AND p.post_status = 'publish'
				 LEFT JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID AND pm.meta_key = '_lagr_rating_count'
				 WHERE tt.count > 0
				 GROUP BY t.term_id, t.name, tt.count
				 ORDER BY likes DESC, cnt DESC
				 LIMIT %d",
				$taxonomy,
				$limit
			)
		);
		// phpcs:enable

		return is_array( $rows ) ? $rows : array();
	}
}
