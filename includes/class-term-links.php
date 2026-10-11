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

		$wrapper = function_exists( 'get_block_wrapper_attributes' )
			? get_block_wrapper_attributes( array( 'class' => 'lagr-term-links' ) )
			: 'class="lagr-term-links"';

		return self::list_html( $taxonomy, $number, $show_count, $wrapper, $order_by );
	}

	/**
	 * The bare `<ul class="lagr-term-links">…</ul>`, reusable outside block
	 * render (e.g. the nav-categories dropdown). `$ul_attrs` is the attribute
	 * string for the `<ul>`. `$order_by` is 'count' (default) or 'likes' — the
	 * latter ranks terms by the total votes (_lagr_rating_count) of the repos
	 * carrying them, falling back to count when there are no votes.
	 */
	public static function list_html( $taxonomy, $number = 8, $show_count = false, $ul_attrs = 'class="lagr-term-links"', $order_by = 'count' ) {
		$taxonomy = sanitize_key( $taxonomy );
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return '';
		}

		$terms = ( 'likes' === $order_by )
			? self::terms_by_likes( $taxonomy, (int) $number )
			: self::terms_by_count( $taxonomy, (int) $number );

		if ( empty( $terms ) ) {
			return '';
		}

		$items = '';
		foreach ( $terms as $term ) {
			$link = get_term_link( (int) $term->term_id, $taxonomy );
			if ( is_wp_error( $link ) ) {
				continue;
			}
			$count = $show_count
				? ' <span class="lagr-term-links-count">' . esc_html( number_format_i18n( $term->cnt ) ) . '</span>'
				: '';
			$items .= '<li><a href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . $count . '</a></li>';
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
