<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Live "credibility band" for the homepage — repos collected, topics+tags,
 * summed GitHub stars. Every mature catalog runs one (Smithery "uses", Glama
 * counts, MCP-Catalog totals); it's the cheapest strong trust signal.
 *
 * Counts are cheap (wp_count_posts / wp_count_terms are indexed lookups). The
 * star SUM scans the whole meta table, which is fine at 40 repos but grows
 * with the catalog, so it's cached in a transient and refreshed on repo save
 * rather than computed per request (see LAGR_Collector — the theme just reads
 * the cache and recomputes on miss).
 */
class Agent_Catalog_Catalog_Stats {

	const STAR_CACHE = 'agent_catalog_star_sum';

	public static function render() {
		$repos = self::repo_count();
		$terms = self::term_count();
		$stars = self::star_sum();

		if ( $repos < 1 ) {
			// Nothing collected yet — a band of zeros reads as broken, so omit it.
			return '';
		}

		$items = array(
			array( self::fmt( $repos ), 'репозиториев собрано с GitHub' ),
			array( self::fmt( $terms ), 'тем и тегов в каталоге' ),
			array( self::fmt( $stars ) . ' ★', 'суммарно звёзд GitHub' ),
		);

		$inner = '';
		foreach ( $items as $item ) {
			$inner .= '<div class="lagr-stat"><span class="lagr-stat-num">' . esc_html( $item[0] )
				. '</span><span class="lagr-stat-label">' . esc_html( $item[1] ) . '</span></div>';
		}

		$wrapper = function_exists( 'get_block_wrapper_attributes' )
			? get_block_wrapper_attributes( array( 'class' => 'lagr-catalog-stats' ) )
			: 'class="lagr-catalog-stats"';

		return '<div ' . $wrapper . '>' . $inner . '</div>';
	}

	private static function repo_count() {
		$counts = wp_count_posts( 'github_repo' );
		return $counts && isset( $counts->publish ) ? (int) $counts->publish : 0;
	}

	private static function term_count() {
		$count = wp_count_terms(
			array(
				'taxonomy'   => array( 'repo_topic', 'post_tag' ),
				'hide_empty' => true,
			)
		);
		return is_wp_error( $count ) ? 0 : (int) $count;
	}

	private static function star_sum() {
		$cached = get_transient( self::STAR_CACHE );
		if ( false !== $cached ) {
			return (int) $cached;
		}

		global $wpdb;
		$sum = (int) $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			"SELECT SUM(CAST(pm.meta_value AS UNSIGNED))
			 FROM {$wpdb->postmeta} pm
			 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			 WHERE pm.meta_key = '_gh_stars'
			   AND p.post_type = 'github_repo'
			   AND p.post_status = 'publish'"
		);

		set_transient( self::STAR_CACHE, $sum, 12 * HOUR_IN_SECONDS );
		return $sum;
	}

	/** Reuse the plugin's "203K/1.2M" formatter when present; plain grouped digits otherwise. */
	private static function fmt( $n ) {
		if ( class_exists( 'LAGR_Blocks' ) && method_exists( 'LAGR_Blocks', 'format_count' ) ) {
			return LAGR_Blocks::format_count( (int) $n );
		}
		return number_format_i18n( (int) $n );
	}
}
