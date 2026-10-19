<?php
/**
 * Render-time helpers for the blog archive patterns (blog-cards / blog-featured
 * / blog-timeline). Everything else in those patterns is plain core blocks; the
 * two things core cannot express are handled here, keyed on block classes so
 * they never touch other loops:
 *
 * - `ac-blog-cover` featured image: core prints nothing when a post has no
 *   image, which would collapse the horizontal card. Emit a theme-coloured
 *   placeholder link instead (the post's rubric as its label).
 * - `ac-blog-month` post date: a month divider for the timeline. Rendered only
 *   when the month differs from the previous item's, so a post-date block in
 *   every item becomes "one heading per month".
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Agent_Catalog_Blog_Loop {

	/** Month label of the previous ac-blog-month block in this request. */
	private static $last_month = null;

	public static function init() {
		add_filter( 'render_block_core/post-featured-image', array( __CLASS__, 'cover_placeholder' ), 10, 3 );
		add_filter( 'render_block_core/post-date', array( __CLASS__, 'month_divider' ), 10, 3 );
		add_filter( 'render_block_core/query', array( __CLASS__, 'reset_month' ), 10, 1 );
	}

	private static function has_class( $block, $class ) {
		$classes = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';
		return $classes && preg_match( '/(^|\s)' . preg_quote( $class, '/' ) . '(\s|$)/', $classes );
	}

	public static function cover_placeholder( $html, $block, $instance ) {
		if ( '' !== trim( $html ) || ! self::has_class( $block, 'ac-blog-cover' ) ) {
			return $html;
		}
		$post_id = isset( $instance->context['postId'] ) ? (int) $instance->context['postId'] : 0;
		if ( ! $post_id ) {
			return $html;
		}
		$cats  = get_the_category( $post_id );
		$label = $cats ? $cats[0]->name : get_bloginfo( 'name' );
		$ratio = isset( $block['attrs']['aspectRatio'] ) ? $block['attrs']['aspectRatio'] : '16/10';

		return sprintf(
			'<figure class="wp-block-post-featured-image %1$s is-placeholder" style="aspect-ratio:%2$s"><a href="%3$s" tabindex="-1" aria-hidden="true"><span>%4$s</span></a></figure>',
			esc_attr( $block['attrs']['className'] ),
			esc_attr( $ratio ),
			esc_url( get_permalink( $post_id ) ),
			esc_html( $label )
		);
	}

	public static function month_divider( $html, $block ) {
		if ( ! self::has_class( $block, 'ac-blog-month' ) ) {
			return $html;
		}
		$label = trim( wp_strip_all_tags( $html ) );
		if ( $label === self::$last_month ) {
			return '';
		}
		self::$last_month = $label;
		return $html;
	}

	/** A second timeline on the same page starts its own month run. */
	public static function reset_month( $html ) {
		self::$last_month = null;
		return $html;
	}
}
