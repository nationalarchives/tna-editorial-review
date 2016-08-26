<?php
/**
 * Plugin Name: TNA Editorial Review
 * Plugin URI: https://github.com/nationalarchives/tna-editorial-review
 * Description: The National Archives editorial review workflow plugin.
 * Version: 0.4
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

// Gets current user's role
function get_current_user_role() {
	$current_user = wp_get_current_user();
	$roles = $current_user->roles;
	$role = array_shift($roles);
	return $role;
}

// Returns true or type of page, if on an edit page
function is_edit_page( $new_edit = null ) {
	global $pagenow;
	if ( !is_admin() ) return false;
	if ( $new_edit == "edit" )
		return in_array( $pagenow, array( 'post.php',  ) );
	elseif ($new_edit == "new")
		return in_array( $pagenow, array( 'post-new.php' ) );
	else
		return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
}

include 'functions.php';
include 'tna-email-notification.php';
include 'tna-page-status-widget.php';
include 'tna-author-rules.php';
