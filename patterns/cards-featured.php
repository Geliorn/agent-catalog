<?php
/**
 * Title: Карточки: крупные (featured)
 * Slug: agent-catalog/cards-featured
 * Categories: agent_catalog_page
 * Description: Крупные выделенные карточки репозиториев — акцентная рамка, крупный заголовок, заполненная кнопка. Для топ-подборки или промо-места.
 * Inserter: yes
 *
 * Standalone drop-in grid using the featured card look. Reuses
 * agent_catalog_repo_query() so it matches the featured style elsewhere.
 */

echo agent_catalog_repo_query( array( 'perPage' => 2, 'columns' => 2, 'order' => 'score', 'style' => 'featured' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- block markup from a trusted helper.
