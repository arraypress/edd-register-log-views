<?php
/**
 * Order Logs Registration Class
 *
 * @package     ArrayPress/EDD-Register-Exporters
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 * @author      David Sherlock
 */

declare( strict_types=1 );

namespace ArrayPress\EDD\Register;

/**
 * Class OrderLogs
 *
 * @package ArrayPress\EDD\Register\Export
 * @since   1.0.0
 */
class LogOrderLink {

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 * @var self|null
	 */
	private static ?self $instance = null;

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
	private function __construct() {
	}

	/**
	 * Get instance of this class.
	 *
	 * @return self Instance of this class.
	 * @since 1.0.0
	 *
	 */
	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register a new log link.
	 *
	 * @param array   $args         {
	 *                              Arguments for registering a log link.
	 *
	 * @type string   $id           Required. Unique identifier for the log link.
	 * @type string   $label        Required. Text to display for the link.
	 * @type string   $view         Required. The log view parameter.
	 * @type callable $url_callback Optional. Callback to generate custom URL. Default null.
	 * @type string   $capability   Optional. Required capability to view. Default 'view_shop_reports'.
	 *                              }
	 *
	 * @return bool True if registered successfully, false otherwise.
	 * @since 1.0.0
	 *
	 */
	public function register( array $args ): bool {
		$defaults = [
			'id'           => '',
			'label'        => '',
			'view'         => '',
			'url_callback' => null,
			'capability'   => 'view_shop_reports'
		];

		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args['id'] ) || empty( $args['label'] ) || empty( $args['view'] ) ) {
			return false;
		}

		$this->logs[ $args['id'] ] = $args;

		return true;
	}

	/**
	 * Initialize hooks.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function init(): void {
		add_action( 'edd_view_order_details_logs_after', [ $this, 'render_log_links' ] );
	}

	/**
	 * Render registered log links.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return void
	 * @since 1.0.0
	 *
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

			if ( is_callable( $log['url_callback'] ) ) {
				$url = call_user_func( $log['url_callback'], $order );
			} else {
				$url = edd_get_admin_url( [
					'page'     => 'edd-tools',
					'tab'      => 'logs',
					'view'     => $log['view'],
					'customer' => absint( $order->customer_id ),
				] );
			}

			printf(
				'<p><a href="%1$s">%2$s</a></p>',
				esc_url( $url ),
				esc_html( $log['label'] )
			);
		}
	}

	/**
	 * Static registration helper.
	 *
	 * @param array $args Registration arguments.
	 *
	 * @return self
	 * @since 1.0.0
	 *
	 */
	public static function register_log( array $args ): self {
		$instance = self::instance();
		$instance->register( $args );
		$instance->init();

		return $instance;
	}

}