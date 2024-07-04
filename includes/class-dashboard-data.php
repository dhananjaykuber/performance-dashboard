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
            metric_name varchar(10) NOT NULL,
            metric_value float NOT NULL,
            timestamp datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY url (url),
            KEY metric_name (metric_name)
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

		$url           = sanitize_text_field( wp_unslash( $_POST['url'] ) );
		$valid_metrics = array( 'ttfb', 'lcp', 'cls', 'inp' );

		$data_to_insert = array();
		foreach ( $valid_metrics as $metric ) {
			if ( isset( $_POST[ $metric ] ) ) {
				$data_to_insert[] = array(
					'url'          => $url,
					'metric_name'  => $metric,
					'metric_value' => floatval( $_POST[ $metric ] ),
				);
			}
		}

		if ( empty( $data_to_insert ) ) {
			wp_send_json_error( __( 'No valid metrics provided', 'performance-dashboard' ) );
		}

		global $wpdb;

		// Insert data into the database.
		$success = true;
		foreach ( $data_to_insert as $data ) {
			$result = $wpdb->insert( $this->table_name, $data ); // phpcs:ignore
			if ( false === $result ) {
				$success = false;
				break;
			}
		}

		if ( ! $success ) {
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

		$query = "SELECT url, 
                         MAX(CASE WHEN metric_name = 'ttfb' THEN metric_value END) as ttfb,
                         MAX(CASE WHEN metric_name = 'lcp' THEN metric_value END) as lcp,
                         MAX(CASE WHEN metric_name = 'cls' THEN metric_value END) as cls,
                         MAX(CASE WHEN metric_name = 'inp' THEN metric_value END) as inp,
                         MAX(timestamp) as timestamp
                  FROM $table_name";

		if ( $url ) {
			$query .= $wpdb->prepare( ' WHERE url = %s', $url );
		}

		$query .= ' GROUP BY url ORDER BY MAX(timestamp) DESC LIMIT %d';

		$prepared_query = $wpdb->prepare( $query, $limit ); // phpcs:ignore

		return $wpdb->get_results( $prepared_query ); // phpcs:ignore
	}
}
