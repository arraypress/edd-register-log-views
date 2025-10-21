<?php
/**
 * EDD Custom Log Views Registration Helper
 *
 * Provides a simplified interface for registering custom Easy Digital Downloads log views.
 *
 * @package     ArrayPress\EDD\Register\LogViews
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @since       1.0.0
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use ArrayPress\EDD\Register\LogViewsManager;
use ArrayPress\EDD\Register\LogOrderLink;

if ( ! function_exists( 'edd_custom_log_views' ) ):
	/**
	 * Get the Custom Log Views Manager instance.
	 *
	 * @return LogViewsManager
	 * @since 1.0.0
	 */
	function edd_custom_log_views(): LogViewsManager {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new LogViewsManager();
		}

		return $instance;
	}
endif;

if ( ! function_exists( 'edd_register_custom_log_view' ) ):
	/**
	 * Register a custom log view.
	 *
	 * @param array $args       {
	 *                          View configuration arguments.
	 *
	 * @type string $id         Required. Unique identifier for the log view.
	 * @type string $title      Required. Title displayed in the log views menu.
	 * @type string $class_name Required. Fully qualified name of the list table class.
	 * @type string $file       Required. Path to the class file relative to base_path.
	 * @type string $capability Optional. Required capability to view logs. Default 'view_shop_reports'.
	 * @type string $base_path  Optional. Base path for class file.
	 *                          }
	 *
	 * @return bool True if registered successfully, false otherwise.
	 * @since 1.0.0
	 */
	function edd_register_custom_log_view( array $args ): bool {
		return edd_custom_log_views()->register( $args );
	}
endif;

if ( ! function_exists( 'edd_log_order_links' ) ):
	/**
	 * Get the Log Order Links Manager instance.
	 *
	 * @return LogOrderLink
	 * @since 1.0.0
	 */
	function edd_log_order_links(): LogOrderLink {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new LogOrderLink();
		}

		return $instance;
	}
endif;

if ( ! function_exists( 'edd_register_log_order_link' ) ):
	/**
	 * Register an order log link.
	 *
	 * Adds a link to the order details page that navigates to a specific log view.
	 *
	 * Example usage:
	 * ```php
	 * edd_register_log_order_link([
	 *     'id'     => 'custom-logs',
	 *     'label'  => __('Custom Logs', 'your-plugin'),
	 *     'view'   => 'custom_view'
	 * ]);
	 * ```
	 *
	 * @param array   $args             {
	 *                                  Log link configuration arguments.
	 *
	 * @type string   $id               Required. Unique identifier for the log link.
	 * @type string   $label            Required. Text to display for the link.
	 * @type string   $view             Required. The log view parameter.
	 * @type callable $url_callback     Optional. Callback to generate custom URL. Default null.
	 * @type callable $display_callback Optional. Callback to determine if link should be displayed. Default null.
	 * @type string   $capability       Optional. Required capability to view. Default 'view_shop_reports'.
	 *                                  }
	 *
	 * @return bool True if registered successfully, false otherwise.
	 * @since 1.0.0
	 */
	function edd_register_log_order_link( array $args ): bool {
		return edd_log_order_links()->register( $args );
	}
endif;