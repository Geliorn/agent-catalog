<?php
/**
 * Title: Written by
 * Slug: agent-catalog/hidden-written-by
 * Inserter: no
 *
 * Article byline: author avatar + name on top, publish date below. Styled as a
 * compact card row (.lagr-article-byline) so a post reads like an editorial
 * article, not a bare "Автор: … в …" line.
 */

?>
<!-- wp:group {"className":"lagr-article-byline","style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|60"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group lagr-article-byline" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:avatar {"size":44,"isLink":true,"className":"lagr-byline-avatar"} /-->
	<!-- wp:group {"className":"lagr-byline-meta","style":{"spacing":{"blockGap":"2px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
	<div class="wp-block-group lagr-byline-meta">
		<!-- wp:post-author-name {"isLink":true,"className":"lagr-byline-name","fontSize":"small"} /-->
		<!-- wp:post-date {"format":"d.m.Y","className":"lagr-byline-date","fontSize":"small"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
