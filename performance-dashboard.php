<?php
/**
 * Plugin Name: Performance Dashboard
 * Plugin URI: https://github.com/dhananjaykuber/performance-dashboard
 * Description: Collect and display Core Web Vitals data in WordPress dashboard.
 * Version: 1.0.0
 * Author: Dhananjay Kuber
 * Author URI: https://github.com/dhananjaykuber/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: performance-dashboard
 *
 * @package Performance_Dashboard
 */

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
define( 'PERFORMANCE_DASHBOARD_VERSION', '1.0.0' );
define( 'PERFORMANCE_DASHBOARD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PERFORMANCE_DASHBOARD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files.
require_once PERFORMANCE_DASHBOARD_PLUGIN_DIR . '/includes/class-admin-page.php';
require_once PERFORMANCE_DASHBOARD_PLUGIN_DIR . '/includes/class-dashboard-data.php';
require_once PERFORMANCE_DASHBOARD_PLUGIN_DIR . '/includes/class-dashboard-widget.php';
require_once PERFORMANCE_DASHBOARD_PLUGIN_DIR . '/includes/class-enqueue-scripts.php';

/**
 * Initialize the plugin
 *
 * @return void
 */
function performance_dashboard_init() {
	new Dashboard_Data();
	new Admin_Page();
	new Dashboard_Widget();
	new Enqueue_Scripts();
}

add_action( 'plugins_loaded', 'performance_dashboard_init' );

/**
 * Activation function.
 *
 * @return void
 */
function performance_dashboard_activate() {
	$performance_data = new Dashboard_Data();
	$performance_data->create_table();
}

register_activation_hook( __FILE__, 'performance_dashboard_activate' );
