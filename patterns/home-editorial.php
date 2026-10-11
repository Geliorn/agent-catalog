<?php
/**
 * Title: Главная: журнальная
 * Slug: agent-catalog/home-editorial
 * Categories: agent_catalog_page
 * Description: Крупный «выбор редакции» сверху, затем блог в две колонки с боковой панелью тем, новые репозитории и цифры доверия. Контент-маркетинг / SEO (как MCP-Catalog).
 * Inserter: yes
 *
 * Editorial / magazine homepage (MCP-Catalog). Leads with one large featured
 * pick, then a two-column blog-forward body (articles + a topics rail), a fresh
 * repos row and the trust band — the layout for a catalog that competes on
 * content (reviews / "X vs Y") as much as on the listing itself.
 */

$repos_url = get_post_type_archive_link( 'github_repo' );
$repos_url = $repos_url ? $repos_url : home_url( '/' );
$blog_page = get_option( 'page_for_posts' );
$blog_url  = $blog_page ? get_permalink( $blog_page ) : home_url( '/' );
?>
<!-- wp:pattern {"slug":"lagr/hero-search"} /-->

<!-- wp:group {"align":"wide"} -->
<div class="wp-block-group alignwide">
	<!-- wp:paragraph {"className":"lagr-eyebrow"} --><p class="lagr-eyebrow">Выбор редакции</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php echo agent_catalog_repo_query( array( 'perPage' => 1, 'columns' => 1, 'order' => 'score', 'style' => 'featured' ) ); ?>

<!-- wp:columns {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns alignwide" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:column {"width":"64%"} -->
	<div class="wp-block-column" style="flex-basis:64%">
		<!-- wp:group {"className":"lagr-section-head","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
		<div class="wp-block-group lagr-section-head">
			<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Свежее из блога</h2><!-- /wp:heading -->
			<!-- wp:paragraph {"className":"lagr-section-more"} --><p class="lagr-section-more"><a href="<?php echo esc_url( $blog_url ); ?>">Все статьи →</a></p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:query {"query":{"perPage":4,"pages":1,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"displayLayout":{"type":"list"}} -->
		<div class="wp-block-query">
			<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
				<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"var:preset|spacing|40"}},"border":{"bottom":{"color":"var:preset|color|light","width":"1px"}}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--light);border-bottom-width:1px;padding-bottom:var(--wp--preset--spacing--40)">
					<!-- wp:post-terms {"term":"category","fontSize":"small"} /-->
					<!-- wp:post-title {"isLink":true,"fontSize":"large"} /-->
					<!-- wp:post-excerpt {"excerptLength":28,"moreText":"Читать далее →","fontSize":"small"} /-->
				</div>
				<!-- /wp:group -->
			<!-- /wp:post-template -->
			<!-- wp:query-no-results -->
			<!-- wp:paragraph --><p>Скоро здесь появятся статьи.</p><!-- /wp:paragraph -->
			<!-- /wp:query-no-results -->
		</div>
		<!-- /wp:query -->
	</div>
	<!-- /wp:column -->

	<!-- wp:column {"width":"36%"} -->
	<div class="wp-block-column" style="flex-basis:36%">
		<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Популярные темы</h2><!-- /wp:heading -->
		<!-- wp:tag-cloud {"taxonomy":"repo_topic","numberOfTags":24,"showTagCounts":true,"className":"lagr-topic-tag-cloud"} /-->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:group {"align":"wide","className":"lagr-section-head","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide lagr-section-head" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Новые репозитории</h2><!-- /wp:heading -->
	<!-- wp:paragraph {"className":"lagr-section-more"} --><p class="lagr-section-more"><a href="<?php echo esc_url( $repos_url ); ?>">Все репозитории →</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php echo agent_catalog_repo_query( array( 'perPage' => 3, 'columns' => 3, 'order' => 'date' ) ); ?>

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:agent-catalog/catalog-stats {"align":"wide"} /-->
</div>
<!-- /wp:group -->
