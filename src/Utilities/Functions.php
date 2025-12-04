<?php
/**
 * EDD Custom Log Views Registration Helper
 *
 * @package     ArrayPress\EDD\Register\LogViews
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @since       1.0.0
 */

defined( 'ABSPATH' ) || exit;

use ArrayPress\EDD\Register\LogViewsManager;
use ArrayPress\EDD\Register\LogOrderLink;

if ( ! function_exists( 'edd_register_custom_log_view' ) ):
	/**
	 * Register a custom log view.
	 *
	 * @param array $args View configuration arguments.
	 *
	 * @return bool True if registered successfully, false otherwise.
	 */
	function edd_register_custom_log_view( array $args ): bool {
		static $manager = null;
		if ( null === $manager ) {
			$manager = new LogViewsManager();
		}

		return $manager->register( $args );
	}
endif;

if ( ! function_exists( 'edd_register_log_order_link' ) ):
	/**
	 * Register an order log link.
	 *
	 * @param array $args Log link configuration arguments.
	 *
	 * @return bool True if registered successfully, false otherwise.
	 */
	function edd_register_log_order_link( array $args ): bool {
		static $manager = null;
		if ( null === $manager ) {
			$manager = new LogOrderLink();
		}

		return $manager->register( $args );
	}
endif;