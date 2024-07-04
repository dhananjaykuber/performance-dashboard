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
			PERFORMANCE_DASHBOARD_PLUGIN_URL . '/build/performance-collector.js',
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
			PERFORMANCE_DASHBOARD_PLUGIN_URL . '/build/admin.css',
			array(),
			PERFORMANCE_DASHBOARD_VERSION
		);

		$this->enqueue_chartjs_admin_scripts();
	}

	/**
	 * Enqueues Chart.js library and admin scripts, and localizes performance data.
	 *
	 * This function performs the following tasks:
	 * 1. Enqueues the Chart.js library from a CDN.
	 * 2. Enqueues the plugin's admin JavaScript file.
	 * 3. Retrieves the last 10 performance data entries.
	 * 4. Prepares the performance data for use in JavaScript.
	 * 5. Localizes the prepared data for use in the admin script.
	 *
	 * @return void
	 */
	private function enqueue_chartjs_admin_scripts() {
		// Enqueue Chart.js from CDN.
		wp_enqueue_script(
			'chart-js',
			'https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js',
			array(),
			'3.7.1',
			true
		);

		wp_enqueue_script(
			'chartjs-adapter-date-fns',
			'https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js',
			array( 'chart-js' ),
			'3.0.0',
			true
		);

		wp_enqueue_script(
			'performance-dashboard-admin',
			PERFORMANCE_DASHBOARD_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery', 'chart-js', 'chartjs-adapter-date-fns' ),
			PERFORMANCE_DASHBOARD_VERSION,
			true
		);

		// Retrieve last 10 performance data entries.
		$performance_data = Dashboard_Data::get_performance_data( null, 10 );

		// Prepare data arrays for JavaScript use.
		$labels    = array();
		$ttfb_data = array();
		$lcp_data  = array();
		$cls_data  = array();
		$inp_data  = array();

		// Populate data arrays from performance data.
		foreach ( $performance_data as $data ) {
			$labels[]    = $data->timestamp;
			$ttfb_data[] = $data->ttfb;
			$lcp_data[]  = $data->lcp;
			$cls_data[]  = $data->cls;
			$inp_data[]  = $data->inp;
		}

		// Localize the script with new data.
		wp_localize_script(
			'performance-dashboard-admin',
			'performanceData',
			array(
				'labels' => $labels,
				'ttfb'   => $ttfb_data,
				'lcp'    => $lcp_data,
				'cls'    => $cls_data,
				'inp'    => $inp_data,
			)
		);
	}
}
