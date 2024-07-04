<?php
/**
 * Admin Page View for Performance Dashboard
 *
 * This file contains the HTML structure for the Performance Dashboard admin page.
 * It includes a form to select a URL and separate chart containers for each metric.
 *
 * @package Performance_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap performance-dashboard-container">
	<h1><?php esc_html_e( 'Performance Dashboard', 'performance-dashboard' ); ?></h1>

	<div class="performance-dashboard-header">
		<form method="GET">
			<input type="hidden" name="page" value="performance-dashboard">
			<select name="url" id="url-select" onchange="this.form.submit()">
				<option value=""><?php esc_html_e( 'Select URL', 'performance-dashboard' ); ?></option>
				<?php foreach ( $urls as $url ) : ?>
					<option value="<?php echo esc_attr( $url ); ?>" <?php selected( $selected_url, $url ); ?>>
						<?php echo esc_html( $url ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</form>
	</div>

	<div class="performance-dashboard-charts">
		<div class="chart-wrapper">
			<h3><?php esc_html_e( 'Time to First Byte (TTFB)', 'performance-dashboard' ); ?></h3>
			<canvas id="ttfbChart"></canvas>
		</div>
		<div class="chart-wrapper">
			<h3><?php esc_html_e( 'Largest Contentful Paint (LCP)', 'performance-dashboard' ); ?></h3>
			<canvas id="lcpChart"></canvas>
		</div>
		<div class="chart-wrapper">
			<h3><?php esc_html_e( 'Cumulative Layout Shift (CLS)', 'performance-dashboard' ); ?></h3>
			<canvas id="clsChart"></canvas>
		</div>
		<div class="chart-wrapper">
			<h3><?php esc_html_e( 'Interaction to Next Paint (INP)', 'performance-dashboard' ); ?></h3>
			<canvas id="inpChart"></canvas>
		</div>
	</div>

	<div class="performance-dashboard-summary">
		<?php if ( ! empty( $performance_data ) ) : ?>
			<?php $latest_data = $performance_data[0]; ?>
			<div class="performance-dashboard-metric">
				<h3><?php esc_html_e( 'TTFB', 'performance-dashboard' ); ?></h3>
				<p><?php echo esc_html( round( $latest_data->ttfb, 2 ) ); ?> ms</p>
			</div>
			<div class="performance-dashboard-metric">
				<h3><?php esc_html_e( 'LCP', 'performance-dashboard' ); ?></h3>
				<p><?php echo esc_html( round( $latest_data->lcp, 2 ) ); ?> ms</p>
			</div>
			<div class="performance-dashboard-metric">
				<h3><?php esc_html_e( 'CLS', 'performance-dashboard' ); ?></h3>
				<p><?php echo esc_html( round( $latest_data->cls, 3 ) ); ?></p>
			</div>
			<div class="performance-dashboard-metric">
				<h3><?php esc_html_e( 'INP', 'performance-dashboard' ); ?></h3>
				<p><?php echo esc_html( round( $latest_data->inp, 2 ) ); ?> ms</p>
			</div>
		<?php else : ?>
			<p><?php esc_html_e( 'No performance data available.', 'performance-dashboard' ); ?></p>
		<?php endif; ?>
	</div>
</div>