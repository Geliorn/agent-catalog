<?php
/**
 * Title: Footer
 * Slug: agent-catalog/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: Navigation footer — categories, popular tags, sections and an about blurb, over a copyright bar.
 *
 * A "sitemap" footer: the dynamic topic/tag link lists give every category and
 * tag archive an internal link (good for browsing and for SEO), which the slim
 * header deliberately doesn't carry. URLs resolve live so it's portable across
 * the network.
 */

$repos_url    = get_post_type_archive_link( 'github_repo' );
$repos_url    = $repos_url ? $repos_url : home_url( '/' );
$partners_url = get_post_type_archive_link( 'contractor' );
$partners_url = $partners_url ? $partners_url : home_url( '/' );
$blog_page    = get_option( 'page_for_posts' );
$blog_url     = $blog_page ? get_permalink( $blog_page ) : home_url( '/' );
?>
<!-- wp:group {"align":"full","className":"lagr-footer","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|50"}},"border":{"top":{"color":"var:preset|color|light","width":"1px"}}},"backgroundColor":"base","layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignfull lagr-footer has-base-background-color has-background" style="border-top-color:var(--wp--preset--color--light);border-top-width:1px;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--50)">

	<!-- wp:columns {"className":"lagr-footer-cols"} -->
	<div class="wp-block-columns lagr-footer-cols">

		<!-- wp:column {"width":"20%"} -->
		<div class="wp-block-column" style="flex-basis:20%">
			<!-- wp:heading {"level":3,"className":"lagr-footer-head"} --><h3 class="wp-block-heading lagr-footer-head">Категории</h3><!-- /wp:heading -->
			<!-- wp:agent-catalog/term-links {"taxonomy":"repo_topic","number":8,"showCount":true} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"20%"} -->
		<div class="wp-block-column" style="flex-basis:20%">
			<!-- wp:heading {"level":3,"className":"lagr-footer-head"} --><h3 class="wp-block-heading lagr-footer-head">Популярные теги</h3><!-- /wp:heading -->
			<!-- wp:agent-catalog/term-links {"taxonomy":"post_tag","number":5,"orderBy":"likes"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"20%"} -->
		<div class="wp-block-column" style="flex-basis:20%">
			<!-- wp:heading {"level":3,"className":"lagr-footer-head"} --><h3 class="wp-block-heading lagr-footer-head">Навигация</h3><!-- /wp:heading -->
			<!-- wp:list {"className":"lagr-footer-links"} -->
			<ul class="wp-block-list lagr-footer-links">
				<!-- wp:list-item --><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="<?php echo esc_url( $repos_url ); ?>">Все репозитории</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="<?php echo esc_url( $partners_url ); ?>">Сервисы</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="<?php echo esc_url( $blog_url ); ?>">Блог</a></li><!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"40%"} -->
		<div class="wp-block-column" style="flex-basis:40%">
			<!-- wp:heading {"level":3,"className":"lagr-footer-head"} --><h3 class="wp-block-heading lagr-footer-head">О проекте</h3><!-- /wp:heading -->
			<!-- wp:paragraph {"fontSize":"small","textColor":"contrast-2"} --><p class="has-contrast-2-color has-text-color has-small-font-size">Каталог репозиториев с GitHub по AI-агентам, MCP и Agent Skills — собирается и обновляется автоматически, плюс каталог AI-сервисов и интеграторов.</p><!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

	<!-- wp:group {"className":"lagr-footer-bar","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"},"padding":{"top":"var:preset|spacing|40"}},"border":{"top":{"color":"var:preset|color|light","width":"1px"}}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
	<div class="wp-block-group lagr-footer-bar" style="border-top-color:var(--wp--preset--color--light);border-top-width:1px;margin-top:var(--wp--preset--spacing--50);padding-top:var(--wp--preset--spacing--40)">
		<!-- wp:group {"style":{"spacing":{"blockGap":"0.3em"}},"fontSize":"small","textColor":"contrast-2","layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group has-contrast-2-color has-text-color has-small-font-size">
			<!-- wp:paragraph --><p>© <?php echo esc_html( gmdate( 'Y' ) ); ?></p><!-- /wp:paragraph -->
			<!-- wp:site-title {"level":0,"isLink":true} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
