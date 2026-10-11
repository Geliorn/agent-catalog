<?php
/**
 * Title: Header
 * Slug: agent-catalog/header
 * Categories: header
 * Block Types: core/template-part/header
 * Description: Site header with logo, primary navigation and a search field.
 *
 * "Каталог" points at the repository archive (/repos/), not the front page —
 * the front page is reached via the logo. A compact search sits in the header
 * so it's reachable from every page (the hero search only exists on the
 * homepage). URLs resolve live so the same header drops onto any site.
 */

$repos_url    = get_post_type_archive_link( 'github_repo' );
$repos_url    = $repos_url ? $repos_url : home_url( '/' );
$partners_url = get_post_type_archive_link( 'contractor' );
$partners_url = $partners_url ? $partners_url : home_url( '/' );
$blog_page    = get_option( 'page_for_posts' );
$blog_url     = $blog_page ? get_permalink( $blog_page ) : home_url( '/' );
?>
<!-- wp:group {"align":"full","className":"lagr-header","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}},"border":{"bottom":{"color":"var:preset|color|light","width":"1px"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1220px"}} -->
<div class="wp-block-group alignfull lagr-header has-base-background-color has-background" style="border-bottom-color:var(--wp--preset--color--light);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
	<!-- wp:group {"align":"wide","className":"lagr-header-row","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide lagr-header-row">
		<!-- wp:site-title {"level":0,"style":{"typography":{"fontStyle":"normal","fontWeight":"700"},"color":{"text":"var:preset|color|accent"}},"isLink":true} /-->

		<!-- wp:group {"className":"lagr-header-menu","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
		<div class="wp-block-group lagr-header-menu">
			<!-- wp:navigation {"overlayBackgroundColor":"base","overlayTextColor":"contrast","layout":{"type":"flex","justifyContent":"left"}} -->
			<!-- wp:navigation-link {"label":"Каталог","url":"<?php echo esc_url( $repos_url ); ?>","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"Поставщики","url":"<?php echo esc_url( $partners_url ); ?>","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"Блог","url":"<?php echo esc_url( $blog_url ); ?>","kind":"custom"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:group -->

		<!-- wp:search {"showLabel":false,"placeholder":"Поиск","buttonText":"Искать","buttonPosition":"button-inside","buttonUseIcon":true,"className":"lagr-header-search"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
