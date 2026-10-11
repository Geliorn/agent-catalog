<?php
/**
 * Title: Карточки: минимал-список
 * Slug: agent-catalog/cards-minimal
 * Categories: agent_catalog_page
 * Description: Репозитории плоским списком в одну колонку — тема, описание, теги и статистика в каждой строке, без рамок. Для узких колонок и длинных списков.
 * Inserter: yes
 *
 * Standalone drop-in list using the minimal card look (richer rows: topic,
 * a fuller description and tags, no card chrome). Reuses
 * agent_catalog_repo_query() so it matches the minimal style elsewhere.
 */

echo agent_catalog_repo_query( array( 'perPage' => 8, 'columns' => 1, 'order' => 'score', 'style' => 'minimal' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- block markup from a trusted helper.
