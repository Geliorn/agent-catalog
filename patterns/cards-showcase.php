<?php
/**
 * Title: Витрина: стили карточки репозитория
 * Slug: agent-catalog/cards-showcase
 * Categories: agent_catalog_page
 * Description: Все четыре стиля карточки репозитория на одной странице — стандартная, компактная, список, крупная — для сравнения и выбора под сайт.
 * Inserter: yes
 *
 * Preview of every card style variant side by side, each as its own labelled
 * section, so the look can be compared and picked per site. Reuses the same
 * agent_catalog_repo_query() helper the homepage variants use, so what's shown
 * here is exactly what a site gets when it selects that card style.
 */
?>
<!-- wp:group {"align":"wide"} -->
<div class="wp-block-group alignwide">
	<!-- wp:paragraph {"className":"lagr-eyebrow"} --><p class="lagr-eyebrow">Стандартная</p><!-- /wp:paragraph -->
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Карточка по умолчанию</h2><!-- /wp:heading -->
	<!-- wp:paragraph {"fontSize":"small"} --><p class="has-small-font-size">Полная карточка: аватар, описание, темы и теги, рейтинг, статистика, кнопка «Подробно». Сетка в 3 колонки.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php echo agent_catalog_repo_query( array( 'perPage' => 3, 'columns' => 3, 'order' => 'score' ) ); ?>

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}}} -->
<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:paragraph {"className":"lagr-eyebrow"} --><p class="lagr-eyebrow">Компактная</p><!-- /wp:paragraph -->
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Компактная (плотная)</h2><!-- /wp:heading -->
	<!-- wp:paragraph {"fontSize":"small"} --><p class="has-small-font-size">Плотнее, без строки тегов, описание в одну строку. Больше карточек на экран, сетка в 4 колонки.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php echo agent_catalog_repo_query( array( 'perPage' => 4, 'columns' => 4, 'order' => 'score', 'style' => 'compact' ) ); ?>

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}}} -->
<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:paragraph {"className":"lagr-eyebrow"} --><p class="lagr-eyebrow">Список</p><!-- /wp:paragraph -->
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Минимал-список</h2><!-- /wp:heading -->
	<!-- wp:paragraph {"fontSize":"small"} --><p class="has-small-font-size">Плоские строки без рамок: тема, описание, теги и статистика. Для узких колонок и длинных списков, одна колонка.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php echo agent_catalog_repo_query( array( 'perPage' => 5, 'columns' => 1, 'order' => 'score', 'style' => 'minimal' ) ); ?>

<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}}} -->
<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:paragraph {"className":"lagr-eyebrow"} --><p class="lagr-eyebrow">Крупная</p><!-- /wp:paragraph -->
	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Featured (крупная)</h2><!-- /wp:heading -->
	<!-- wp:paragraph {"fontSize":"small"} --><p class="has-small-font-size">Акцентная рамка, крупный заголовок, заполненная кнопка. Для топ-подборки или рекламного места, 2 колонки.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<?php echo agent_catalog_repo_query( array( 'perPage' => 2, 'columns' => 2, 'order' => 'score', 'style' => 'featured' ) ); ?>
