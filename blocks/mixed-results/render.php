<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo Agent_Catalog_Mixed_Results::render(); // phpcs:ignore WordPress.Security.EscapeOutput -- do_blocks() output (real rendered HTML), not raw data.
