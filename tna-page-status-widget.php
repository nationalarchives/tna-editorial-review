<?php
/**
 * Page status dashboard widget
 * Displays a list of pages with pending and draft statuses on the dashboard
 *
 */

// Adds widget to dashboard
function page_status_add_dashboard_widgets() {
	wp_add_dashboard_widget(
		'page_status_dashboard_widget',
		'Pending and draft pages',
		'page_status_dashboard_widget_function'
	);
}
add_action( 'wp_dashboard_setup', 'page_status_add_dashboard_widgets' );

// Page status function
function page_status_dashboard_widget_function() {
	// Declare variables
	$current_user = wp_get_current_user();

	// Set the wp arguments
	$query = array(
		'post_type' => 'page',
		'post_status' => array('draft', 'pending'),
		'orderby' => 'modified'
	);
	$loop = new WP_Query($query);

	// Return top table
    echo returnTopTemplate( $current_user->ID, $current_user->user_login );

	// Loop through table rows
	while ( $loop->have_posts() ) : $loop->the_post();

		echo returnTableContent();

	endwhile;

	// Return bottom table
	echo returnBottomTemplate();

}
