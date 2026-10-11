<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lightweight native-search enhancement — makes WordPress's built-in search
 * also match a post's taxonomy terms (tags, topics, licenses, partner
 * type/specialization/region), not just title + content. Replaces Relevanssi
 * for this catalog: core search alone misses a repo whose only mention of
 * "rag" is a tag, which is exactly the kind of hit a catalog needs.
 *
 * Implemented as search-only query clauses (join term tables, OR the term
 * name/slug into the title-search group, group by post ID to dedupe), so there
 * is no index to build or maintain — every site in the network gets it with no
 * per-site setup, unlike Relevanssi.
 */
class Agent_Catalog_Search {

	public static function init() {
		add_filter( 'posts_join', array( __CLASS__, 'join' ), 10, 2 );
		add_filter( 'posts_where', array( __CLASS__, 'where' ), 10, 2 );
		add_filter( 'posts_groupby', array( __CLASS__, 'groupby' ), 10, 2 );
	}

	/** Only front-end queries that actually carry a search term. */
	private static function applies( $query ) {
		return $query instanceof WP_Query
			&& ! is_admin()
			&& '' !== (string) $query->get( 's' );
	}

	public static function join( $join, $query ) {
		if ( ! self::applies( $query ) ) {
			return $join;
		}
		global $wpdb;
		if ( false === strpos( $join, 'acs_term_rel' ) ) {
			$join .= " LEFT JOIN {$wpdb->term_relationships} acs_term_rel ON {$wpdb->posts}.ID = acs_term_rel.object_id"
				. " LEFT JOIN {$wpdb->term_taxonomy} acs_term_tax ON acs_term_rel.term_taxonomy_id = acs_term_tax.term_taxonomy_id"
				. " LEFT JOIN {$wpdb->terms} acs_term ON acs_term_tax.term_id = acs_term.term_id";
		}
		return $join;
	}

	public static function where( $where, $query ) {
		if ( ! self::applies( $query ) ) {
			return $where;
		}
		global $wpdb;

		// Core builds each search word as a group:
		//   ((wp_posts.post_title LIKE 'x') OR (post_excerpt LIKE 'x') OR (post_content LIKE 'x'))
		// Re-use the same already-escaped bound value ($1) and OR the term
		// name/slug into that group so taxonomy matches sit at the same
		// precedence as a title match.
		$pattern     = '/\(\s*' . preg_quote( $wpdb->posts, '/' ) . '\.post_title\s+LIKE\s*(\'[^\']+\')/i';
		$replacement = '( acs_term.name LIKE $1 OR acs_term.slug LIKE $1 OR ' . $wpdb->posts . '.post_title LIKE $1';

		return preg_replace( $pattern, $replacement, $where );
	}

	public static function groupby( $groupby, $query ) {
		if ( ! self::applies( $query ) ) {
			return $groupby;
		}
		global $wpdb;
		// The term joins multiply rows (one per term) — collapse back to one row
		// per post. Keep any existing group-by WordPress already set.
		$id = "{$wpdb->posts}.ID";
		if ( '' === trim( (string) $groupby ) ) {
			return $id;
		}
		return false === strpos( $groupby, $id ) ? $groupby . ', ' . $id : $groupby;
	}
}
