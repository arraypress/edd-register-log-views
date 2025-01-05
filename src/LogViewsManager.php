<?php
/**
 * EDD Custom Log Views Library
 *
 * A library for easily registering custom log views in Easy Digital Downloads.
 *
 * @package     ArrayPress\EDD\Register\LogViews
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @since       1.0
 */

namespace ArrayPress\EDD\Register;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use Exception;
use function edd_logs_view_page;

/**
 * Class LogViewsManager
 *
 * @since 1.0
 */
class LogViewsManager {

	/**
	 * Registered views
	 *
	 * @var array
	 */
	private array $views = [];

	/**
	 * Instance of this class.
	 *
	 * @var self|null
	 */
	private static ?LogViewsManager $instance = null;

	/**
	 * Get the instance of this class.
	 *
	 * @return self
	 */
	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		// Add EDD log view filter
		add_filter( 'edd_log_views', array( $this, 'register_views_with_edd' ) );
	}

	/**
	 * Register a custom log view.
	 *
	 * @param array $args       {
	 *                          Arguments for registering a log view.
	 *
	 * @type string $id         Required. Unique identifier for the log view.
	 * @type string $title      Required. Title displayed in the log views menu.
	 * @type string $class_name Required. Fully qualified name of the list table class.
	 * @type string $file       Required. Path to the class file relative to base_path.
	 * @type string $capability Optional. Required capability to view logs. Default 'view_shop_reports'.
	 * @type string $base_path  Optional. Base path for class file. Defaults to dirname($file).
	 *                          }
	 *
	 * @return bool True if registered successfully, false otherwise.
	 */
	public function register( array $args ): bool {
		$defaults = array(
			'id'         => '',
			'title'      => '',
			'class_name' => '',
			'file'       => '',
			'capability' => 'view_shop_reports',
			'base_path'  => '',
		);

		$args = wp_parse_args( $args, $defaults );

		// Check required fields
		if ( empty( $args['id'] ) || empty( $args['title'] ) || empty( $args['class_name'] ) || empty( $args['file'] ) ) {
			return false;
		}

		// Store the view configuration
		$this->views[ $args['id'] ] = $args;

		// Register the view handler
		$this->register_handler( $args );

		return true;
	}

	/**
	 * Register multiple log views.
	 *
	 * @param array  $views     Array of view configurations.
	 * @param string $base_path Optional base path to prepend to all file paths.
	 *
	 * @return void
	 */
	public function register_many( array $views, string $base_path = '' ): void {
		foreach ( $views as $view ) {
			if ( ! empty( $base_path ) && empty( $view['base_path'] ) ) {
				$view['base_path'] = $base_path;
			}
			$this->register( $view );
		}
	}

	/**
	 * Register views with EDD's log views list.
	 *
	 * @param array $views Existing log views.
	 *
	 * @return array Modified log views.
	 */
	public function register_views_with_edd( array $views ): array {
		foreach ( $this->views as $id => $view ) {
			$views[ $id ] = $view['title'];
		}

		return $views;
	}

	/**
	 * Register the view handler.
	 *
	 * @param array $args View configuration.
	 *
	 * @return void
	 */
	private function register_handler( array $args ): void {
		add_action( "edd_logs_view_{$args['id']}", function () use ( $args ) {
			// Check capability
			if ( ! current_user_can( $args['capability'] ) ) {
				return;
			}

			// Include required core files
			require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
			require_once EDD_PLUGIN_DIR . 'includes/admin/reporting/class-base-logs-list-table.php';

			// Build class file path
			$file_path = ! empty( $args['base_path'] )
				? trailingslashit( $args['base_path'] ) . $args['file']
				: $args['file'];

			// Include the class file
			if ( file_exists( $file_path ) ) {
				require_once $file_path;
			}

			// Initialize table class if it exists
			if ( class_exists( $args['class_name'] ) ) {
				$logs_table = new $args['class_name']();
				edd_logs_view_page( $logs_table, $args['id'] );
			}
		} );
	}

}