<?php
/**
 * Title: Блог: лента по месяцам
 * Slug: agent-catalog/blog-timeline
 * Categories: query, agent_catalog_page
 * Description: Архив статей (вариант C): рубрики пилюлями, лента с разделителями по месяцам, крупная дата слева, небольшая обложка справа, навигация «Новее / Старее».
 * Viewport Width: 1280
 *
 * The month heading is a post-date block in every item (class ac-blog-month);
 * Agent_Catalog_Blog_Loop prints it only when the month changes.
 */

?>
<!-- wp:group {"className":"ac-blog-c","layout":{"type":"constrained","contentSize":"860px"}} -->
<div class="wp-block-group ac-blog-c">

	<!-- wp:agent-catalog/term-links {"taxonomy":"category","number":12,"showCount":true,"orderBy":"posts","display":"pills","allLabel":"Все","className":"ac-blog-rubrics"} /-->

	<!-- wp:query {"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"className":"ac-blog ac-blog-timeline","layout":{"type":"default"}} -->
	<div class="wp-block-query ac-blog ac-blog-timeline">
		<!-- wp:post-template {"className":"ac-blog-list","layout":{"type":"default"}} -->
			<!-- wp:post-date {"format":"F Y","className":"ac-blog-month"} /-->
			<!-- wp:group {"className":"ac-blog-item","layout":{"type":"default"}} -->
			<div class="wp-block-group ac-blog-item">
				<!-- wp:group {"className":"ac-blog-day","layout":{"type":"default"}} -->
				<div class="wp-block-group ac-blog-day">
					<!-- wp:post-date {"format":"j","className":"ac-blog-day-num"} /-->
					<!-- wp:post-date {"format":"M","className":"ac-blog-day-mon"} /-->
				</div>
				<!-- /wp:group -->
				<!-- wp:group {"className":"ac-blog-body","layout":{"type":"default"}} -->
				<div class="wp-block-group ac-blog-body">
					<!-- wp:group {"className":"ac-blog-meta","layout":{"type":"flex","flexWrap":"wrap"}} -->
					<div class="wp-block-group ac-blog-meta">
						<!-- wp:post-terms {"term":"category","separator":" ","className":"ac-blog-cat"} /-->
						<!-- wp:post-time-to-read /-->
					</div>
					<!-- /wp:group -->
					<!-- wp:post-title {"level":2,"isLink":true,"className":"ac-blog-title"} /-->
					<!-- wp:post-excerpt {"excerptLength":30,"className":"ac-blog-excerpt"} /-->
					<!-- wp:group {"className":"ac-blog-foot","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
					<div class="wp-block-group ac-blog-foot">
						<!-- wp:post-terms {"term":"post_tag","separator":" ","className":"ac-blog-tags"} /-->
						<!-- wp:read-more {"content":"Подробно →","className":"ac-blog-more"} /-->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
				<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","className":"ac-blog-cover"} /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
		<!-- wp:paragraph --><p>Статей пока нет.</p><!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->

		<!-- wp:query-pagination {"paginationArrow":"arrow","className":"ac-blog-pager","layout":{"type":"flex","justifyContent":"center"}} -->
			<!-- wp:query-pagination-previous {"label":"Новее"} /-->
			<!-- wp:query-pagination-next {"label":"Старее"} /-->
		<!-- /wp:query-pagination -->
	</div>
	<!-- /wp:query -->

</div>
<!-- /wp:group -->
