<?php
/**
 * Handles enqueueing of scripts and styles for the Performance Dashboard plugin.
 *
 * @package Performance_Dashboard
 */

/**
 * Class Performance_Dashboard_Enqueue_Scripts
 */
class Enqueue_Scripts {
	/**
	 * Performance_Dashboard_Enqueue_Scripts constructor.
	 *
	 * Initializes actions to enqueue scripts and styles.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Enqueues frontend scripts.
	 *
	 * Enqueues performance-collector.js for frontend functionality.
	 * Localizes script to provide necessary data to frontend scripts.
	 */
	public function enqueue_frontend_scripts() {
		wp_enqueue_script(
			'performance-collector',
			PERFORMANCE_DASHBOARD_PLUGIN_URL . 'assets/js/performance-collector.js',
			array(),
			PERFORMANCE_DASHBOARD_VERSION,
			true
		);

		wp_localize_script(
			'performance-collector',
			'performanceData',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'performance_data_nonce' ),
			)
		);
	}

	/**
	 * Enqueues admin scripts and styles.
	 *
	 * Enqueues admin.css for styling the admin dashboard.
	 * Enqueues Chart.js from CDN for generating charts in admin.
	 * Enqueues admin.js for additional admin functionality.
	 *
	 * @param string $hook The current admin page hook.
	 */
	public function enqueue_admin_scripts( $hook ) {
		// Only enqueue scripts on the top-level page of the performance dashboard.
		if ( 'toplevel_page_performance-dashboard' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'performance-dashboard-admin',
			PERFORMANCE_DASHBOARD_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			PERFORMANCE_DASHBOARD_VERSION
		);

		wp_enqueue_script(
			'chart-js',
			'https://cdn.jsdelivr.net/npm/chart.js',
			array(),
			'3.7.1',
			true
		);

		wp_enqueue_script(
			'performance-dashboard-admin',
			PERFORMANCE_DASHBOARD_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery', 'chart-js' ),
			PERFORMANCE_DASHBOARD_VERSION,
			true
		);
	}
}
