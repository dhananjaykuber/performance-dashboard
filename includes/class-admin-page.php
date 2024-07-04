<?php
/**
 * Performance Dashboard Admin Page.
 *
 * This class handles the creation and display of the admin page
 * for the Performance Dashboard plugin.
 *
 * @package Performance_Dashboard
 */

/**
 * Class Admin_Page
 */
class Admin_Page {

	/**
	 * Constructor.
	 *
	 * Adds the admin menu item for the Performance Dashboard.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/**
	 * Add admin menu item.
	 *
	 * Registers the Performance Dashboard menu item in the WordPress admin menu.
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'Performance Dashboard', 'performance-dashboard' ),
			__( 'Performance', 'performance-dashboard' ),
			'manage_options',
			'performance-dashboard',
			array( $this, 'display_admin_page' ),
			'dashicons-chart-area',
			100
		);
	}

	/**
	 * Display admin page.
	 *
	 * Displays the Performance Dashboard admin page content.
	 */
	public function display_admin_page() {
		// Get the list of unique URLs from the database.
		$urls = $this->get_unique_urls();

		// Get the selected URL from the query parameter, if any.
		$selected_url = isset( $_GET['url'] ) ? sanitize_text_field( $_GET['url'] ) : ''; // phpcs:ignore

		// Get the performance data for the selected URL.
		$performance_data = Dashboard_Data::get_performance_data( $selected_url );

		include PERFORMANCE_DASHBOARD_PLUGIN_DIR . 'views/admin-page.php';
	}

	/**
	 * Get unique URLs.
	 *
	 * Retrieves a list of unique URLs from the performance data table.
	 *
	 * @return array List of unique URLs.
	 */
	private function get_unique_urls() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'performance_data';

		// Get a list of distinct URLs from the performance data table.
		return $wpdb->get_col( "SELECT DISTINCT url FROM $table_name ORDER BY url ASC" ); // phpcs:ignore
	}
}
