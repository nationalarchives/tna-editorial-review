<?php

// Hides the publish button if status is pending the current user is an author
function hide_action_button() {
	$status = get_post_status();
	$current_screen = get_current_screen();
	if ( $current_screen->base == 'post' && get_current_user_role() == 'author' && $status == 'pending' ) { ?>
			<style type="text/css" media="screen">#major-publishing-actions{display:none;}</style>
	<?php }
}
add_action( 'admin_head', 'hide_action_button' );

// Adds message when page status is pending
function adds_editors_reviewing_message(){
	$status = get_post_status();
	$current_screen = get_current_screen();
	if ( $current_screen->base == 'post' && get_current_user_role() == 'author' && $status == 'pending' ) { ?>
		<div class="misc-pub-section pending-message">
			<p><strong>Web editors reviewing</strong></p>
		</div>
	<?php }
}
add_action( 'post_submitbox_misc_actions', 'adds_editors_reviewing_message' );

// Adds save draft button
function adds_save_draft_button() {
	$status = get_post_status();
	$current_screen = get_current_screen();
	if ( $current_screen->base == 'post' && get_current_user_role() == 'author' && $status !== 'pending' ) { ?>
		<div id="draft-action">
			<div id="save-action">
				<input type="submit" name="save" id="save-post" value="Save Draft" class="button">
			</div>
			<div class="clear"></div>
		</div>
	<?php }
}
add_action( 'post_submitbox_misc_actions', 'adds_save_draft_button' );

// Author can't publish when updating an existing page
function change_status( $post_id ) {
	if ( get_current_user_role() == 'author' ) {
		remove_action('save_post', 'change_status');
		wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
		add_action('save_post', 'change_status');
	}
}
add_action('publish_post', 'change_status');

// Changes the action button text when edit page
function change_publish_button( $translation, $text ) {
	if ( get_current_user_role() == 'author' ) {
		if ( $text == 'Publish' ) {
			return 'Update';
		}
		return $translation;
	}
}
// add_filter( 'gettext', 'change_publish_button', 10, 2 );

// Removes capabilities
function take_away_publish_permissions() {
	$role = get_role( 'author' );
	$role->remove_cap( 'publish_posts' );
}
// add_action( 'init', 'take_away_publish_permissions' );
