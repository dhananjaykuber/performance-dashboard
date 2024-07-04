<?php
/**
 * Performance Dashboard Data
 *
 * This class handles the creation of the performance data table and
 * saving/retrieving performance data via AJAX.
 *
 * @package Performance_Dashboard
 */

/**
 * Class Dashboard_Data
 */
class Dashboard_Data {

	/**
	 * The name of the database table for performance data.
	 *
	 * @var string $table_name
	 */
	private $table_name;

	/**
	 * Constructor.
	 *
	 * Sets up the database table name and registers AJAX actions for saving performance data.
	 */
	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'performance_data';

		add_action( 'wp_ajax_save_performance_data', array( $this, 'save_performance_data' ) );
		add_action( 'wp_ajax_nopriv_save_performance_data', array( $this, 'save_performance_data' ) );
	}

	/**
	 * Create table.
	 *
	 * Creates the database table for storing performance data.
	 */
	public function create_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $this->table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            url varchar(255) NOT NULL,
            ttfb float NOT NULL,
            lcp float NOT NULL,
            cls float NOT NULL,
            inp float NOT NULL,
            timestamp datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( $sql );
	}

	/**
	 * Save performance data.
	 *
	 * Handles the AJAX request to save performance data to the database.
	 */
	public function save_performance_data() {
		// Verify the nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'performance_data_nonce' ) ) {
			wp_send_json_error( __( 'Invalid nonce', 'performance-dashboard' ) );
		}

		// Check if required POST data is set.
		if ( ! isset( $_POST['url'] ) ) {
			wp_send_json_error( __( 'Missing required data', 'performance-dashboard' ) );
		}

		// Sanitize and validate the input data.
		$url  = sanitize_text_field( wp_unslash( $_POST['url'] ) );
		$ttfb = isset( $_POST['ttfb'] ) ? floatval( $_POST['ttfb'] ) : null;
		$lcp  = isset( $_POST['lcp'] ) ? floatval( $_POST['lcp'] ) : null;
		$cls  = isset( $_POST['cls'] ) ? floatval( $_POST['cls'] ) : null;
		$inp  = isset( $_POST['inp'] ) ? floatval( $_POST['inp'] ) : null;

		// Prepare data for insertion.
		$data = array(
			'url'  => $url,
			'ttfb' => $ttfb,
			'lcp'  => $lcp,
			'cls'  => $cls,
			'inp'  => $inp,
		);

		// Remove null values.
		$data = array_filter(
			$data,
			function ( $value ) {
				return ! is_null( $value );
			}
		);

		global $wpdb;

		// Insert data into the database.
		$result = $wpdb->insert( $this->table_name, $data ); // phpcs:ignore

		if ( false === $result ) {
			wp_send_json_error( __( 'Failed to save data', 'performance-dashboard' ) );
		} else {
			wp_send_json_success( __( 'Data saved successfully', 'performance-dashboard' ) );
		}
	}

	/**
	 * Get performance data.
	 *
	 * Retrieves performance data from the database.
	 *
	 * @param string|null $url  The URL to filter the data by.
	 * @param int         $limit The number of records to retrieve.
	 * @return array The retrieved performance data.
	 */
	public static function get_performance_data( $url = null, $limit = 10 ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'performance_data';

		// Initialize the base query.
		$query = "SELECT * FROM $table_name";

		// Prepare the query based on whether the URL is provided or not.
		if ( $url ) {
			$query         .= ' WHERE url = %s ORDER BY timestamp DESC LIMIT %d';
			$prepared_query = $wpdb->prepare( $query, $url, $limit ); // phpcs:ignore
		} else {
			$query         .= ' ORDER BY timestamp DESC LIMIT %d';
			$prepared_query = $wpdb->prepare( $query, $limit ); // phpcs:ignore
		}

		// Execute the prepared query.
		return $wpdb->get_results( $prepared_query ); // phpcs:ignore
	}
}
