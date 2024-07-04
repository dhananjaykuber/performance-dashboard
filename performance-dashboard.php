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
