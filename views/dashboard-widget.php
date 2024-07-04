<?php
/**
 * Dashboard Widget View for Performance Dashboard
 *
 * This file contains the HTML structure for the Performance Dashboard widget
 * that is displayed in the WordPress admin dashboard. It shows the latest
 * performance data metrics and a link to the full performance dashboard.
 *
 * @package Performance_Dashboard
 */

// Ensure this file is being included by a parent file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get the latest performance data.
$latest_data = ! empty( $performance_data ) ? $performance_data[0] : null;
?>

<div class="performance-dashboard-widget">
	<?php if ( $latest_data ) : ?>
		<p>
			<?php
			// Translators: %s is the URL for which the latest performance data is shown.
			echo esc_html( sprintf( __( 'Latest data for: %s', 'performance-dashboard' ), $latest_data->url ) );
			?>
		</p>
		
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Metric', 'performance-dashboard' ); ?></th>
					<th><?php esc_html_e( 'Value', 'performance-dashboard' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php esc_html_e( 'Time to First Byte (TTFB)', 'performance-dashboard' ); ?></td>
					<td><?php echo esc_html( round( $latest_data->ttfb, 2 ) ); ?> ms</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Largest Contentful Paint (LCP)', 'performance-dashboard' ); ?></td>
					<td><?php echo esc_html( round( $latest_data->lcp, 2 ) ); ?> ms</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Cumulative Layout Shift (CLS)', 'performance-dashboard' ); ?></td>
					<td><?php echo esc_html( round( $latest_data->cls, 3 ) ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Interaction to Next Paint (INP)', 'performance-dashboard' ); ?></td>
					<td><?php echo esc_html( round( $latest_data->inp, 2 ) ); ?> ms</td>
				</tr>
			</tbody>
		</table>

		<p class="description">
			<?php
			// Translators: %s is the timestamp when the latest performance data was collected.
			echo esc_html( sprintf( __( 'Collected at: %s', 'performance-dashboard' ), $latest_data->timestamp ) );
			?>
		</p>
	<?php else : ?>
		<p><?php esc_html_e( 'No performance data available yet.', 'performance-dashboard' ); ?></p>
	<?php endif; ?>
	
	<p>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=performance-dashboard' ) ); ?>">
			<?php esc_html_e( 'View full performance dashboard', 'performance-dashboard' ); ?>
		</a>
	</p>
</div>
