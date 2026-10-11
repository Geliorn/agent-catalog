<?php
/**
 * Title: Категории и теги (сеткой)
 * Slug: agent-catalog/categories-tags
 * Categories: agent_catalog_page
 * Description: Единая сетка: сначала немногочисленные категории (темы, выделены), затем топ-30 тегов по количеству. Без разделяющих заголовков.
 * Inserter: yes
 *
 * One combined term grid, no dividing heading: the few curated topics come
 * first (marked so they stand out), then the 30 most-used tags. Categories are
 * always just 3-5, so a separate "Категории" heading over a near-empty row
 * looked wrong — they ride in the same grid as the tags instead.
 */
?>
<!-- wp:group {"align":"wide","className":"lagr-section-head","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<div class="wp-block-group alignwide lagr-section-head">
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Темы и теги</h2><!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:agent-catalog/term-grid {"taxonomy":"repo_topic","number":0,"appendTaxonomy":"post_tag","appendNumber":25,"maxTotal":25,"align":"wide"} /-->
