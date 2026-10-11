<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Header "Категории" dropdown — a CSS-only <details> disclosure holding the
 * top topic links (no JavaScript). The topic list is live, so it tracks the
 * catalog rather than freezing into the header markup.
 */

$taxonomy = isset( $attributes['taxonomy'] ) ? sanitize_key( $attributes['taxonomy'] ) : 'repo_topic';
$number   = isset( $attributes['number'] ) ? absint( $attributes['number'] ) : 12;
$label    = ( isset( $attributes['label'] ) && '' !== $attributes['label'] ) ? $attributes['label'] : 'Категории';

$links = Agent_Catalog_Term_Links::list_html( $taxonomy, $number, true, 'class="lagr-term-links"' );
if ( '' === $links ) {
	return;
}

$repos_url = get_post_type_archive_link( 'github_repo' );
$repos_url = $repos_url ? $repos_url : home_url( '/' );

$wrapper = get_block_wrapper_attributes( array( 'class' => 'lagr-cat-dd' ) );
?>
<details <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput -- pre-escaped by get_block_wrapper_attributes(). ?>>
	<summary class="lagr-cat-dd-toggle"><?php echo esc_html( $label ); ?></summary>
	<div class="lagr-cat-dd-menu">
		<?php echo $links; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in Agent_Catalog_Term_Links::list_html(). ?>
		<a class="lagr-cat-dd-all" href="<?php echo esc_url( $repos_url ); ?>">Все репозитории →</a>
	</div>
</details>
