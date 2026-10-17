<?php
/**
 * First-run site provisioning.
 *
 * On theme activation, stand the site up so it looks and behaves like the
 * reference install out of the box: a static "Главная" front page carrying the
 * home-standard pattern, a "Блог" posts page, the static-front options wired,
 * and rewrite rules flushed so the plugin-registered CPT archives
 * (/repos/, /services/) resolve.
 *
 * The repository / partner / article *listings* are CPT archives and the blog
 * index — they render from the theme's own templates (archive-github_repo.html,
 * archive-contractor.html, home.html), so they need no page records, only the
 * CPTs (registered + flushed by the plugins) and the posts page set here.
 *
 * Everything is idempotent: pages are tracked by a marker meta so re-activating
 * never duplicates them, and the static-front options are only set when the site
 * is still unconfigured, so a site that was later customised is left alone.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Agent_Catalog_Provision {

	/** Post meta that marks a page this theme created, keyed by a stable role. */
	const MARKER = '_agent_catalog_page';

	public static function init() {
		add_action( 'after_switch_theme', array( __CLASS__, 'run' ) );
	}

	/** Create the structural pages and wire the reading settings. */
	public static function run() {
		$front = self::ensure_page(
			'home',
			'Каталог AI-агентов, MCP и Agent Skills',
			'<!-- wp:pattern {"slug":"agent-catalog/home-standard"} /-->',
			'page-no-title',
			'home'
		);
		$blog = self::ensure_page( 'blog', 'Блог', '', '', 'blog' );

		// Only wire the static front page when the site has not been set up yet,
		// so we never stomp a choice an existing site already made.
		$current_front = (int) get_option( 'page_on_front' );
		if ( $front && ( 'page' !== get_option( 'show_on_front' ) || ! get_post( $current_front ) ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front );
		}

		$current_blog = (int) get_option( 'page_for_posts' );
		if ( $blog && ! get_post( $current_blog ) ) {
			update_option( 'page_for_posts', $blog );
		}

		// CPT rewrite rules come from the plugins; flush so their archives and
		// the fresh pages resolve without a manual Permalinks save.
		flush_rewrite_rules();
	}

	/**
	 * Return the id of the theme-owned page for $role, creating it once.
	 *
	 * @param string $role     Stable key stored in the marker meta.
	 * @param string $title    Page title.
	 * @param string $content  Block markup for the page body.
	 * @param string $template Block template slug to assign (''=default).
	 * @param string $slug     Explicit ASCII post_name, so the URL is clean and
	 *                         predictable (e.g. /blog/) instead of a Cyrillic
	 *                         slug auto-derived from the title. ''=let WP derive.
	 */
	private static function ensure_page( $role, $title, $content, $template = '', $slug = '' ) {
		$existing = get_posts(
			array(
				'post_type'        => 'page',
				'post_status'      => 'any',
				'numberposts'      => 1,
				'fields'           => 'ids',
				'meta_key'         => self::MARKER,
				'meta_value'       => $role,
				'suppress_filters' => false,
			)
		);
		if ( ! empty( $existing ) ) {
			return (int) $existing[0];
		}

		$postarr = array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $content,
		);
		if ( '' !== $slug ) {
			$postarr['post_name'] = $slug;
		}
		$id = wp_insert_post( $postarr, true );
		if ( is_wp_error( $id ) || ! $id ) {
			return 0;
		}

		update_post_meta( $id, self::MARKER, $role );
		if ( '' !== $template ) {
			update_post_meta( $id, '_wp_page_template', $template );
		}
		return (int) $id;
	}
}
