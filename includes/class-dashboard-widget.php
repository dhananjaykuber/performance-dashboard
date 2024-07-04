<?php
/**
 * Dashboard Widget Class
 *
 * This class handles the creation and display of a custom dashboard widget
 * for the Performance Dashboard plugin.
 *
 * @package Performance_Dashboard
 */

/**
 * Dashboard_Widget class
 */
class Dashboard_Widget {

	/**
	 * Constructor
	 *
	 * Initializes the class and adds the dashboard widget setup action.
	 */
	public function __construct() {
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
	}

	/**
	 * Add Dashboard Widget
	 *
	 * Registers the custom dashboard widget.
	 */
	public function add_dashboard_widget() {
		wp_add_dashboard_widget(
			'performance_dashboard_widget', // Widget slug.
			__( 'Performance Overview', 'performance-dashboard' ), // Title.
			array( $this, 'display_dashboard_widget' ) // Display callback.
		);
	}

	/**
	 * Display Dashboard Widget
	 *
	 * Callback function to render the content of the custom dashboard widget.
	 * It fetches the latest performance data and includes the dashboard widget view.
	 */
	public function display_dashboard_widget() {
		$performance_data = Dashboard_Data::get_performance_data( null, 1 );

		include PERFORMANCE_DASHBOARD_PLUGIN_DIR . 'views/dashboard-widget.php';
	}
}
