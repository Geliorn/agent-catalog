<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo Agent_Catalog_Term_Grid::render( $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput -- render() escapes every value it emits.
