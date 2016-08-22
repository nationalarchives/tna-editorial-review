<?php

// Hides the publish button if status is pending the current user is an author
function hide_action_button() {
	global $post;
	$status = get_post_status( $post->ID );
	if ( get_current_user_role() == 'author' && $status == 'pending' ) { ?>
			<style type="text/css" media="screen">
				#major-publishing-actions {
					display: none;
				}
			</style>
			<!-- <p>Web editors reviewing</p> -->
		<?php }
}
add_action( 'admin_head', 'hide_action_button' );

function adds_save_draft_button(){
	global $post;
	$status = get_post_status( $post->ID );
	if ( get_current_user_role() == 'author' ) {
		if ( $status == 'new' || $status == 'auto-draft' ) { ?>
			<div id="minor-publishing-actions">
				<div id="save-action">
					<input type="submit" name="save" id="save-post" value="Save Draft" class="button">
				</div>
				<div class="clear"></div>
			</div>
		<?php }
	}
}
// add_action( 'post_submitbox_misc_actions', 'adds_save_draft_button' );

function adds_save_as_pending_button(){
	global $post;
	$status = get_post_status( $post->ID );
	if ( get_current_user_role() == 'author' ) {
		if ( $status == 'new' || $status == 'auto-draft' || $status == 'auto-draft' ) { ?>
			<div id="minor-publishing-actions">
				<div id="save-action">
					<input type="submit" name="save" id="save-post" value="Save as Pending" class="button">
				</div>
				<div class="clear"></div>
			</div>
		<?php }
	}
}
// add_action( 'post_submitbox_misc_actions', 'adds_save_as_pending_button' );

/*add_filter( 'gettext', 'change_publish_button', 10, 2 );

function change_publish_button( $translation, $text ) {

if ( $text == 'Publish' || $text == 'Update' )
	return 'Save Draft';

return $translation;
}

function dont_publish() {
global $post;
$status = get_post_status( $post->ID );
if ( get_current_user_role() == 'author' ) {
	if ( $status == 'new' || $status == 'auto-draft' ) {
		$post = array( 'ID' => $post->ID, 'post_status' => 'draft' );
		wp_update_post($post);
	}
}
}

add_action('publish_post' , 'dont_publish');*/