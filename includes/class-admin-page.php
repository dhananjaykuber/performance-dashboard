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
		include PERFORMANCE_DASHBOARD_PLUGIN_DIR . 'views/admin-page.php';
	}
}

// Instantiate the Performance_Dashboard_Admin_Page class.
new Admin_Page();
