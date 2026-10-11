<?php
/**
 * Title: Главная: компактная витрина
 * Slug: agent-catalog/home-compact
 * Categories: agent_catalog_page
 * Description: Плотная витрина для разработчиков — максимум карточек выше сгиба, компактные плитки топа и новинок в 4 колонки, облако тем. Минимум баннеров.
 * Inserter: yes
 *
 * Dense, developer-focused showcase. Compact cards in 4 columns pack the most
 * repos above the fold with the least chrome — no big section banners, no blog
 * teaser grid, just the listing and a topic cloud.
 */

$repos_url = get_post_type_archive_link( 'github_repo' );
$repos_url = $repos_url ? $repos_url : home_url( '/' );
?>
<!-- wp:pattern {"slug":"lagr/hero-search"} /-->

<!-- wp:agent-catalog/catalog-stats {"align":"wide"} /-->

<!-- wp:group {"align":"wide","className":"lagr-section-head","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide lagr-section-head">
	<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Топ по звёздам</h3><!-- /wp:heading -->
	<!-- wp:paragraph {"className":"lagr-section-more"} --><p class="lagr-section-more"><a href="<?php echo esc_url( $repos_url ); ?>">Весь рейтинг →</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php echo agent_catalog_repo_query( array( 'perPage' => 8, 'columns' => 4, 'order' => 'score', 'style' => 'compact' ) ); ?>

<!-- wp:group {"align":"wide","className":"lagr-section-head","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide lagr-section-head" style="margin-top:var(--wp--preset--spacing--60)">
	<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Новые поступления</h3><!-- /wp:heading -->
	<!-- wp:paragraph {"className":"lagr-section-more"} --><p class="lagr-section-more"><a href="<?php echo esc_url( $repos_url ); ?>">Все репозитории →</a></p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php echo agent_catalog_repo_query( array( 'perPage' => 8, 'columns' => 4, 'order' => 'date', 'style' => 'compact' ) ); ?>

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--60)">
	<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Темы и теги</h3><!-- /wp:heading -->
	<!-- wp:pattern {"slug":"lagr/tag-and-topic-cloud"} /-->
</div>
<!-- /wp:group -->
