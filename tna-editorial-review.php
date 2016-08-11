<?php
/**
 * Plugin Name: TNA Editorial Review
 * Plugin URI: https://github.com/nationalarchives/tna-editorial-review
 * Description: The National Archives editorial review workflow plugin.
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

include 'functions.php';
include 'tna-email-notification.php';
include 'tna-page-status-widget.php';
