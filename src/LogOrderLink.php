<?php
/**
 * Order Logs Registration Class
 *
 * @package     ArrayPress\EDD\Register\LogViews
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @since       1.0.0
 */

namespace ArrayPress\EDD\Register;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Class LogOrderLink
 *
 * Handles registration and display of order log links in the EDD admin interface.
 *
 * @since 1.0.0
 */
class LogOrderLink {

	/**
	 * Array of registered log links.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private array $logs = [];

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'edd_view_order_details_logs_after', array( $this, 'render_log_links' ) );
	}

	/**
	 * Register a new log link.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Arguments for registering a log link.
	 *
	 *     @type string   $id               Required. Unique identifier for the log link.
	 *     @type string   $label            Required. Text to display for the link.
	 *     @type string   $view             Required. The log view parameter.
	 *     @type callable $url_callback     Optional. Callback to generate custom URL. Default null.
	 *     @type callable $display_callback Optional. Callback to determine if link should be displayed. Default null.
	 *     @type string   $capability       Optional. Required capability to view. Default 'view_shop_reports'.
	 * }
	 *
	 * @return bool True if registered successfully, false otherwise.
	 */
	public function register( array $args ): bool {
		$defaults = array(
			'id'               => '',
			'label'            => '',
			'view'             => '',
			'url_callback'     => null,
			'display_callback' => null,
			'capability'       => 'view_shop_reports'
		);

		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args['id'] ) || empty( $args['label'] ) || empty( $args['view'] ) ) {
			return false;
		}

		$this->logs[ $args['id'] ] = $args;

		return true;
	}

	/**
	 * Render registered log links.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return void
	 */
	public function render_log_links( int $order_id ): void {
		$order = edd_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		foreach ( $this->logs as $log ) {
			if ( ! current_user_can( $log['capability'] ) ) {
				continue;
			}

			// Check display callback
			if ( is_callable( $log['display_callback'] ) && ! call_user_func( $log['display_callback'], $order ) ) {
				continue;
			}

			if ( is_callable( $log['url_callback'] ) ) {
				$url = call_user_func( $log['url_callback'], $order );
			} else {
				$url = edd_get_admin_url( array(
					'page'     => 'edd-tools',
					'tab'      => 'logs',
					'view'     => $log['view'],
					'customer' => absint( $order->customer_id ),
				) );
			}

			printf(
				'<p><a href="%1$s">%2$s</a></p>',
				esc_url( $url ),
				esc_html( $log['label'] )
			);
		}
	}

}