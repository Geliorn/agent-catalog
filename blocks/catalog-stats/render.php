<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo Agent_Catalog_Catalog_Stats::render(); // phpcs:ignore WordPress.Security.EscapeOutput -- render() escapes every value it emits.
