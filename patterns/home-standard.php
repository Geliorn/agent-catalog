<?php
/**
 * Title: Главная: стандартная
 * Slug: agent-catalog/home-standard
 * Categories: agent_catalog_page
 * Description: Композиция главной по умолчанию — поиск, цифры доверия, топ по звёздам, облако тем/тегов, новые поступления, блог. Все секции выровнены по одной ширине.
 * Inserter: yes
 *
 * The default homepage. Fixes the earlier asymmetry: every section shares one
 * alignwide column, so section headings line up with the card grids they label
 * (the old front-page mixed a constrained 1040 heading with a 1220 grid). URLs
 * are resolved live here so the same pattern drops onto any site in the network
 * without hardcoded slugs.
 */

$repos_url = get_post_type_archive_link( 'github_repo' );
$repos_url = $repos_url ? $repos_url : home_url( '/' );
$blog_page = get_option( 'page_for_posts' );
$blog_url  = $blog_page ? get_permalink( $blog_page ) : home_url( '/' );
?>
<!-- wp:pattern {"slug":"lagr/hero-search"} /-->

<!-- wp:agent-catalog/catalog-stats {"align":"wide"} /-->

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:pattern {"slug":"agent-catalog/categories-tags"} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"lagr-section-head","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide lagr-section-head" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Топ по звёздам</h2><!-- /wp:heading -->
	<!-- wp:paragraph {"className":"lagr-section-more"} --><p class="lagr-section-more"><a href="<?php echo esc_url( $repos_url ); ?>">Вся подборка →</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:pattern {"slug":"lagr/homepage-tiles-top"} /-->

<!-- wp:group {"align":"wide","className":"lagr-section-head","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide lagr-section-head" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Новые поступления</h2><!-- /wp:heading -->
	<!-- wp:paragraph {"className":"lagr-section-more"} --><p class="lagr-section-more"><a href="<?php echo esc_url( $repos_url ); ?>">Все репозитории →</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:pattern {"slug":"lagr/homepage-tiles-new"} /-->

<!-- wp:group {"align":"wide","className":"lagr-section-head","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide lagr-section-head" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Статьи</h2><!-- /wp:heading -->
	<!-- wp:paragraph {"className":"lagr-section-more"} --><p class="lagr-section-more"><a href="<?php echo esc_url( $blog_url ); ?>">Все статьи →</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:query {"query":{"perPage":3,"pages":1,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"align":"wide","displayLayout":{"type":"flex","columns":3}} -->
<div class="wp-block-query alignwide">
	<!-- wp:post-template -->
		<!-- wp:group {"className":"lagr-blog-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"},"blockGap":"8px"},"border":{"width":"1px","radius":"8px","color":"var:preset|color|light"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
		<div class="wp-block-group lagr-blog-card" style="border-color:var(--wp--preset--color--light);border-width:1px;border-radius:8px;padding:var(--wp--preset--spacing--40)">
			<!-- wp:post-title {"isLink":true,"fontSize":"medium"} /-->
			<!-- wp:group {"className":"lagr-blog-meta"} -->
			<div class="wp-block-group lagr-blog-meta">
				<!-- wp:post-terms {"term":"category","separator":" ","className":"lagr-blog-cat lagr-topic-pill","fontSize":"small"} /-->
				<!-- wp:post-terms {"term":"post_tag","separator":" ","className":"lagr-tag-pill","fontSize":"small"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:post-excerpt {"excerptLength":18,"moreText":"","fontSize":"small"} /-->
			<!-- wp:read-more {"content":"Читать →","className":"lagr-blog-more"} /-->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->
	<!-- wp:query-no-results -->
	<!-- wp:paragraph --><p>Скоро здесь появятся статьи.</p><!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
