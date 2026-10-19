<?php
/**
 * Title: Блог: главная статья + строки
 * Slug: agent-catalog/blog-featured
 * Categories: query, agent_catalog_page
 * Description: Архив статей (вариант B): рубрики пилюлями, свежая статья крупно на первой странице, ниже компактные строки с разделителями, без сайдбара.
 * Viewport Width: 1280
 *
 * One inherited query; the first item is promoted to the large card purely in
 * CSS (body:not(.paged) .ac-blog-featured li:first-child), so pagination and
 * rubric archives keep working with no second query.
 */

?>
<!-- wp:group {"className":"ac-blog-b","layout":{"type":"constrained","contentSize":"920px"}} -->
<div class="wp-block-group ac-blog-b">

	<!-- wp:agent-catalog/term-links {"taxonomy":"category","number":12,"showCount":true,"orderBy":"posts","display":"pills","allLabel":"Все","className":"ac-blog-rubrics"} /-->

	<!-- wp:query {"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"className":"ac-blog ac-blog-featured","layout":{"type":"default"}} -->
	<div class="wp-block-query ac-blog ac-blog-featured">
		<!-- wp:post-template {"className":"ac-blog-list","layout":{"type":"default"}} -->
			<!-- wp:group {"className":"ac-blog-item","layout":{"type":"default"}} -->
			<div class="wp-block-group ac-blog-item">
				<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/10","className":"ac-blog-cover"} /-->
				<!-- wp:group {"className":"ac-blog-body","layout":{"type":"default"}} -->
				<div class="wp-block-group ac-blog-body">
					<!-- wp:group {"className":"ac-blog-meta","layout":{"type":"flex","flexWrap":"wrap"}} -->
					<div class="wp-block-group ac-blog-meta">
						<!-- wp:post-terms {"term":"category","separator":" ","className":"ac-blog-cat"} /-->
						<!-- wp:post-date /-->
						<!-- wp:post-time-to-read /-->
					</div>
					<!-- /wp:group -->
					<!-- wp:post-title {"level":2,"isLink":true,"className":"ac-blog-title"} /-->
					<!-- wp:post-excerpt {"excerptLength":36,"className":"ac-blog-excerpt"} /-->
					<!-- wp:group {"className":"ac-blog-foot","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
					<div class="wp-block-group ac-blog-foot">
						<!-- wp:post-terms {"term":"post_tag","separator":" ","className":"ac-blog-tags"} /-->
						<!-- wp:read-more {"content":"Подробно →","className":"ac-blog-more"} /-->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
		<!-- wp:paragraph --><p>Статей пока нет.</p><!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->

		<!-- wp:query-pagination {"paginationArrow":"arrow","className":"ac-blog-pager","layout":{"type":"flex","justifyContent":"center"}} -->
			<!-- wp:query-pagination-previous {"label":"Назад"} /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next {"label":"Дальше"} /-->
		<!-- /wp:query-pagination -->
	</div>
	<!-- /wp:query -->

</div>
<!-- /wp:group -->
