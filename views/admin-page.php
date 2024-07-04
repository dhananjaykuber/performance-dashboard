<?php
/**
 * Admin Page View for Performance Dashboard
 *
 * This file contains the HTML structure for the Performance Dashboard admin page.
 * It includes a form to select a URL, a canvas element for displaying charts,
 * and a section for displaying performance summaries.
 *
 * @package Performance_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Example URLs array for demonstration purposes.
// phpcs:ignore
$urls = array(
	'https://example.com/page1',
	'https://example.com/page2',
	'https://example.com/page3',
);
?>

<div class="wrap performance-dashboard-container">
	<h1><?php esc_html_e( 'Performance Dashboard', 'performance-dashboard' ); ?></h1>

	<div class="performance-dashboard-header">
		<form method="get">
			<input type="hidden" name="page" value="performance-dashboard">
			<select name="url" onchange="this.form.submit()">
				<option value=""><?php esc_html_e( 'Select URL', 'performance-dashboard' ); ?></option>
				<?php foreach ( $urls as $url ) : ?>
					<option value="<?php echo esc_attr( $url ); ?>" <?php selected( $selected_url, $url ); ?>>
						<?php echo esc_html( $url ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</form>
	</div>

	<div class="performance-dashboard-chart">
		<canvas id="performanceChart"></canvas>
	</div>

	<div class="performance-dashboard-summary">
		<!-- Summary data will be populated dynamically in the PHP code -->
	</div>
</div>
