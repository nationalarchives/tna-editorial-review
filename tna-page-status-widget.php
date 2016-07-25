<?php
/**
 * Plugin Name: TNA page status widget
 * Plugin URI: https://github.com/nationalarchives/tna-page-status-widget
 * Description: Displays pages with pending and draft statuses on the dashboard.
 * Version: 0.1
 * Author: Chris Bishop
 * Author URI: https://github.com/nationalarchives
 * License: GPL2
 */

// Loads admin CSS
function load_tna_page_status_admin_style() {
	wp_register_style( 'custom_wp_admin_css', plugin_dir_url(__FILE__) . '/style.css', false, '0.1' );
	wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'load_tna_page_status_admin_style' );

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

	$query = array(
		'post_type' => 'page',
		'post_status' => array('draft', 'pending'),
		'orderby' => 'modified'
	);
	$loop = new WP_Query($query);

	$current_user = wp_get_current_user();
	$html = '<div class="tna-page-status-widget current-user-id-'  . $current_user->ID . '">';
	$html .= '<h4>Hello ' . $current_user->user_login . '</h4>';
	$html .= '<table>';
	$html .= '<tr>';
	$html .= '<th>Title</th>';
	$html .= '<th>Last modified by</th>';
	$html .= '<th>Current status</th>';
	$html .= '</tr>';

	while ( $loop->have_posts() ) : $loop->the_post();
		global $post;
		$status = get_post_status( $post->ID );
		$author = get_the_modified_author();
		if ( $author == $current_user->user_login ) {
			$myPage = 'my-page';
		} else {
			$myPage = '';
		}
		if ($status == 'pending') {
			$display_status = 'web editors reviewing';
			$link = ' <a href="' . get_edit_post_link( $post->ID ) . '">edit</a>';
			// $link = ' <a href="' . get_permalink( $post->ID ) . '">view</a>';
		} else {
			$display_status = 'with author';
			$link = ' <a href="' . get_edit_post_link( $post->ID ) . '">edit</a>';
		}
		$html .= '<tr class="page-'. $status . ' ' . $myPage . '">';
		$html .= '<td class="title">' . get_the_title();
		$html .= $link;
		$html .= '</td>';
		$html .= '<td>' . $author . ' on<br />' . get_the_modified_date( $d = 'j/n/y' ) .'</td>';
		$html .= '<td>' . $display_status . '</td>';
		$html .= '</tr>';
	endwhile;

	$html .= '</table></div>';

	echo $html;

}
