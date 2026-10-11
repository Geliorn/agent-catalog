<?php
/**
 * Title: Карточки: компактные
 * Slug: agent-catalog/cards-compact
 * Categories: agent_catalog_page
 * Description: Сетка репозиториев компактными карточками — плотно, 4 колонки, без строки тегов. Для витрин с большим числом карточек.
 * Inserter: yes
 *
 * Standalone drop-in grid using the compact card look (the is-style block
 * style packaged as a ready pattern). Reuses agent_catalog_repo_query(), so
 * it stays in sync with the compact style everywhere else.
 */

echo agent_catalog_repo_query( array( 'perPage' => 8, 'columns' => 4, 'order' => 'score', 'style' => 'compact' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- block markup from a trusted helper.
