<?php
/**
 * Title: Главная: лидерборд
 * Slug: agent-catalog/home-leaderboard
 * Categories: agent_catalog_page
 * Description: Первым экраном — ранжированный «Топ по звёздам» нумерованным списком (как skills.sh / Smithery), затем облако тем и блог.
 * Inserter: yes
 *
 * Leaderboard-first homepage (skills.sh / Smithery). The signature block is a
 * ranked two-column list, ordered by score, numbered 1..N via a CSS counter on
 * the .lagr-leaderboard wrapper — cards come from the plugin's own partial.
 */

$repos_url = get_post_type_archive_link( 'github_repo' );
$repos_url = $repos_url ? $repos_url : home_url( '/' );
$blog_page = get_option( 'page_for_posts' );
$blog_url  = $blog_page ? get_permalink( $blog_page ) : home_url( '/' );
?>
<!-- wp:pattern {"slug":"lagr/hero-search"} /-->

<!-- wp:agent-catalog/catalog-stats {"align":"wide"} /-->

<!-- wp:group {"align":"wide","className":"lagr-section-head","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide lagr-section-head">
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Рейтинг по звёздам</h2><!-- /wp:heading -->
	<!-- wp:paragraph {"className":"lagr-section-more"} --><p class="lagr-section-more"><a href="<?php echo esc_url( $repos_url ); ?>">Весь рейтинг →</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php echo agent_catalog_repo_query( array( 'perPage' => 10, 'columns' => 2, 'order' => 'score', 'style' => 'compact', 'leaderboard' => true ) ); ?>

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Темы и теги</h2><!-- /wp:heading -->
	<!-- wp:pattern {"slug":"lagr/tag-and-topic-cloud"} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"lagr-section-head","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide lagr-section-head" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Из блога</h2><!-- /wp:heading -->
	<!-- wp:paragraph {"className":"lagr-section-more"} --><p class="lagr-section-more"><a href="<?php echo esc_url( $blog_url ); ?>">Все статьи →</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:query {"query":{"perPage":3,"pages":1,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"align":"wide","displayLayout":{"type":"flex","columns":3}} -->
<div class="wp-block-query alignwide">
	<!-- wp:post-template -->
		<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}},"border":{"width":"1px","radius":"8px","color":"var:preset|color|light"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group" style="border-color:var(--wp--preset--color--light);border-width:1px;border-radius:8px;padding:var(--wp--preset--spacing--40)">
			<!-- wp:post-terms {"term":"category","fontSize":"small"} /-->
			<!-- wp:post-title {"isLink":true,"fontSize":"medium"} /-->
			<!-- wp:post-excerpt {"excerptLength":18,"moreText":"Читать →","fontSize":"small"} /-->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->
	<!-- wp:query-no-results -->
	<!-- wp:paragraph --><p>Скоро здесь появятся статьи.</p><!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
