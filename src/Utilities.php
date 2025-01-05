<?php
/**
 * EDD Log Views Registration Helper
 *
 * Provides a simplified interface for registering Easy Digital Downloads log views.
 * This helper function wraps the Log_Views_Manager class to provide a quick way to register
 * multiple log views at once with support for custom capabilities and file locations.
 *
 * Example usage:
 * ```php
 * $views = [
 *     [
 *         'id'         => 'guest_verifications',
 *         'title'      => 'Guest Verifications',
 *         'class_name' => 'Guest_Verifications_Log_Table',
 *         'file'       => 'class-guest-verifications-log-table.php',
 *         'capability' => 'view_shop_reports'
 *     ]
 * ];
 *
 * register_log_views( $views, dirname(__FILE__) . '/logs' );
 * ```
 *
 * @package     ArrayPress/EDD/Register/LogViews
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 * @author      David Sherlock
 */

declare( strict_types=1 );

namespace ArrayPress\EDD\Register;

use Exception;

if ( ! function_exists( __NAMESPACE__ . '\\log_views' ) ):
	/**
	 * Helper function to get the Log Views Manager instance.
	 *
	 * @return LogViewsManager
	 */
	function log_views(): LogViewsManager {
		return LogViewsManager::instance();
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\\register_log_view' ) ):
	/**
	 * Helper function to register a single log view.
	 *
	 * @param array $args View configuration.
	 *
	 * @return bool
	 */
	function register_log_view( array $args ): bool {
		return log_views()->register( $args );
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\\register_log_views' ) ):
	/**
	 * Helper function to register multiple log views.
	 *
	 * @param array  $views     Array of view configurations.
	 * @param string $base_path Optional base path for all views.
	 *
	 * @return void
	 */
	function register_log_views( array $views, string $base_path = '' ): void {
		log_views()->register_many( $views, $base_path );
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\\register_log_order_link' ) ):
	/**
	 * Helper function to register order log links.
	 *
	 * Example usage:
	 * ```php
	 * register_order_log([
	 *     'id'     => 'guest-verifications',
	 *     'label'  => __('Customer Verifications', 'your-textdomain'),
	 *     'view'   => 'guest_verifications'
	 * ]);
	 * ```
	 *
	 * @param array $args Log link configuration.
	 *
	 * @return LogOrderLink|null Returns OrderLogs instance or null if registration fails.
	 */
	function register_log_order_link( array $args ): ?LogOrderLink {
		try {
			return LogOrderLink::register_log( $args );
		} catch ( Exception $e ) {
			return null;
		}
	}
endif;