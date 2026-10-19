<?php
/**
 * Title: Блог: карточки + сайдбар
 * Slug: agent-catalog/blog-cards
 * Categories: query, agent_catalog_page
 * Description: Архив статей (вариант A): рубрики пилюлями, горизонтальные карточки (обложка 16:10, мета, заголовок, анонс, теги, «Подробно»), справа поиск, рубрики и теги блога.
 * Viewport Width: 1280
 *
 * Inherits the main query, so it works for the blog index and rubric archives
 * alike. Cover placeholder for posts without an image comes from
 * Agent_Catalog_Blog_Loop; layout lives in layouts.css (.ac-blog-*).
 */

?>
<!-- wp:agent-catalog/term-links {"taxonomy":"category","number":12,"showCount":true,"orderBy":"posts","display":"pills","allLabel":"Все","className":"ac-blog-rubrics"} /-->

<!-- wp:group {"className":"ac-blog-a","layout":{"type":"default"}} -->
<div class="wp-block-group ac-blog-a">

	<!-- wp:query {"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"className":"ac-blog ac-blog-cards","layout":{"type":"default"}} -->
	<div class="wp-block-query ac-blog ac-blog-cards">
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
					<!-- wp:post-excerpt {"excerptLength":30,"className":"ac-blog-excerpt"} /-->
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

	<!-- wp:group {"tagName":"aside","className":"ac-blog-side","layout":{"type":"default"}} -->
	<aside class="wp-block-group ac-blog-side">
		<!-- wp:group {"className":"ac-blog-side-box","layout":{"type":"default"}} -->
		<div class="wp-block-group ac-blog-side-box">
			<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Поиск по блогу</h3><!-- /wp:heading -->
			<!-- wp:search {"label":"Поиск по блогу","showLabel":false,"placeholder":"Например, Cursor","buttonText":"Найти","buttonPosition":"no-button","query":{"post_type":"post"}} /-->
		</div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"ac-blog-side-box","layout":{"type":"default"}} -->
		<div class="wp-block-group ac-blog-side-box">
			<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Рубрики</h3><!-- /wp:heading -->
			<!-- wp:agent-catalog/term-links {"taxonomy":"category","number":12,"showCount":true,"orderBy":"posts"} /-->
		</div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"ac-blog-side-box","layout":{"type":"default"}} -->
		<div class="wp-block-group ac-blog-side-box">
			<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Популярные теги</h3><!-- /wp:heading -->
			<!-- wp:agent-catalog/term-links {"taxonomy":"post_tag","number":12,"orderBy":"posts","display":"pills","className":"ac-blog-tagcloud"} /-->
		</div>
		<!-- /wp:group -->
	</aside>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
