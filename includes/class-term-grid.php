<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A grid of taxonomy-term cards (name + how many repos carry it), each linking
 * to that term's archive — the "browse by category" entry point Glama and
 * MCP-Catalog lead with. Real term data pulled fresh per render, so counts
 * never freeze the way a get_terms() snapshot baked into a pattern would.
 *
 * Can merge two taxonomies into ONE grid (appendTaxonomy): the few curated
 * topics and the top tags flow together without a dividing heading — the
 * topics are marked `lagr-term-cat` so they still read as primary. Used on the
 * homepage, where there are only ~3-5 categories and a separate heading for
 * them would look empty.
 */
class Agent_Catalog_Term_Grid {

	public static function render( $attributes = array() ) {
		$taxonomy   = isset( $attributes['taxonomy'] ) ? sanitize_key( $attributes['taxonomy'] ) : 'repo_topic';
		$number     = isset( $attributes['number'] ) ? absint( $attributes['number'] ) : 12;
		$append_tax = isset( $attributes['appendTaxonomy'] ) ? sanitize_key( $attributes['appendTaxonomy'] ) : '';
		$append_num = isset( $attributes['appendNumber'] ) ? absint( $attributes['appendNumber'] ) : 0;
		// Hard cap on the total number of cards across both taxonomies — lets the
		// grid stay a fixed size (e.g. 5×5 = 25) no matter how many categories
		// exist. 0 = no cap.
		$max_total  = isset( $attributes['maxTotal'] ) ? absint( $attributes['maxTotal'] ) : 0;

		// When appending a second taxonomy, mark the primary (topic) cards so
		// they stand out among the many tag cards without needing a heading.
		list( $cards, $count ) = self::term_cards( $taxonomy, $number, $append_tax ? 'lagr-term-cat' : '' );
		if ( '' !== $append_tax ) {
			// Fill the remaining slots up to the cap with tags; the categories
			// always come first, so they are never crowded out. When the cap is
			// already reached, skip the append — passing 0 would mean "all".
			$append_slots = $max_total > 0 ? max( 0, $max_total - $count ) : $append_num;
			if ( 0 === $max_total || $append_slots > 0 ) {
				list( $append_cards, ) = self::term_cards( $append_tax, $append_slots, '' );
				$cards                .= $append_cards;
			}
		}

		if ( '' === $cards ) {
			return '';
		}

		$wrapper = function_exists( 'get_block_wrapper_attributes' )
			? get_block_wrapper_attributes( array( 'class' => 'lagr-term-grid' ) )
			: 'class="lagr-term-grid"';

		return '<div ' . $wrapper . '>' . $cards . '</div>';
	}

	/**
	 * Term cards for one taxonomy. `$number` of 0 means all terms.
	 * Returns array( html_string, card_count ) so a caller can cap the total.
	 */
	private static function term_cards( $taxonomy, $number, $extra_class ) {
		if ( '' === $taxonomy || ! taxonomy_exists( $taxonomy ) ) {
			return array( '', 0 );
		}

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
			return array( '', 0 );
		}

		$class = 'lagr-term-card' . ( '' !== $extra_class ? ' ' . $extra_class : '' );
		$cards = '';
		$count = 0;
		foreach ( $terms as $term ) {
			$link = get_term_link( $term );
			if ( is_wp_error( $link ) ) {
				continue;
			}
			++$count;
			$cards .= sprintf(
				'<a class="%1$s" href="%2$s"><span class="lagr-term-name">%3$s</span><span class="lagr-term-count">%4$s</span></a>',
				esc_attr( $class ),
				esc_url( $link ),
				esc_html( $term->name ),
				esc_html( number_format_i18n( $term->count ) )
			);
		}

		return array( $cards, $count );
	}
}
