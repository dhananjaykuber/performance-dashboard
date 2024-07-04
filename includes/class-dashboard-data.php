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
		$result = $wpdb->insert( $this->table_name, $data_to_insert[0] ); // phpcs:ignore

		if ( false === $result ) {
			wp_send_json_error( __( 'Failed to save data', 'performance-dashboard' ) );
		} else {
			wp_send_json_success( __( 'Data saved successfully', 'performance-dashboard' ) );
		}
	}

	/**
	 * Get performance data.
	 *
	 * Retrieves the latest performance data from the database.
	 *
	 * @param string|null $url  The URL to filter the data by.
	 * @param int         $limit The number of records to retrieve.
	 *
	 * @return array The retrieved performance data.
	 */
	public static function get_performance_data( $url = null, $limit = 10 ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'performance_data';

		$query = "
			SELECT t1.url, 
				MAX(CASE WHEN t1.metric_name = 'ttfb' THEN t1.metric_value END) as ttfb,
				MAX(CASE WHEN t1.metric_name = 'lcp' THEN t1.metric_value END) as lcp,
				MAX(CASE WHEN t1.metric_name = 'cls' THEN t1.metric_value END) as cls,
				MAX(CASE WHEN t1.metric_name = 'inp' THEN t1.metric_value END) as inp,
				t1.timestamp
			FROM $table_name t1
			INNER JOIN (
				SELECT url, MAX(timestamp) as max_timestamp
				FROM $table_name
				GROUP BY url
			) t2 ON t1.url = t2.url AND t1.timestamp = t2.max_timestamp
		";

		// Add URL filter if provided.
		if ( $url ) {
			$query .= $wpdb->prepare( ' WHERE t1.url = %s', $url );
		}

		// Group by URL and timestamp to ensure we get one row per URL.
		// Order by timestamp descending to get the most recent first.
		$query .= ' GROUP BY t1.url, t1.timestamp ORDER BY t1.timestamp DESC LIMIT %d';

		// Prepare the query with the limit.
		$prepared_query = $wpdb->prepare( $query, $limit ); // phpcs:ignore

		// Execute the query and return the results.
		return $wpdb->get_results( $prepared_query ); // phpcs:ignore
	}
}
