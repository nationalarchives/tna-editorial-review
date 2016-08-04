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

include 'functions.php';

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

// Adds dashboard column option to screen options
function dashboard_columns() {
	add_screen_option(
		'layout_columns',
		array(
			'max'     => 3,
			'default' => 2
		)
	);
}
add_action( 'admin_head-index.php', 'dashboard_columns' );

// Page status function
function page_status_dashboard_widget_function() {

	$query = array(
		'post_type' => 'page',
		'post_status' => array('draft', 'pending'),
		'orderby' => 'modified'
	);
	$loop = new WP_Query($query);

	$current_user = wp_get_current_user();
    echo returnTopTemplate( $current_user->ID, $current_user->user_login );

	while ( $loop->have_posts() ) : $loop->the_post();
		global $post;
		$status = get_post_status( $post->ID );
		$author = get_the_modified_author();
        $edit_link = get_edit_post_link( $post->ID );
        $title = get_the_title();
        $modified_date = get_the_modified_date($d = 'j/n/y');
		$currentUserLogin = $current_user->user_login;
		$link = ' <a href="' . $edit_link . '">edit</a>';
		$myPageClass = returnMyPageClass( $author, $currentUserLogin );
		$display_status = returnDisplayStatus($status);

		$html = '<tr class="page-'. $status . ' ' . $myPageClass . '">';
		$html .= '<td class="title">' . $title;
		$html .= $link;
		$html .= '</td>';
        $html .= '<td>' . $author . ' on ' . $modified_date .'</td>';
		$html .= '<td>' . $display_status . '</td>';
		$html .= '</tr>';
	endwhile;

	$html .= '</table></div>';

	echo $html;

}
