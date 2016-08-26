<?php

// Returns true or type of page, if on an edit page
function is_edit_page($new_edit = null){
	global $pagenow;
	if (!is_admin()) return false;
	if ($new_edit == "edit")
		return in_array( $pagenow, array( 'post.php',  ) );
	elseif ($new_edit == "new")
		return in_array( $pagenow, array( 'post-new.php' ) );
	else
		return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
}

// Hides the publish button if status is pending and the current user is an author
function hide_action_button() {
	if ( is_edit_page() ) {
		$status = get_post_status();
		if ( get_current_user_role() == 'author' && $status == 'pending' ) { ?>
			<style type="text/css" media="screen">#major-publishing-actions{display:none;}</style>
		<?php }
	}
}
add_action( 'admin_head', 'hide_action_button' );

// Adds message when page status is pending
function adds_editors_reviewing_message(){
	$status = get_post_status();
	if ( get_current_user_role() == 'author' && $status == 'pending' ) { ?>
		<div class="misc-pub-section pending-message">
			<p><strong>Web editors reviewing</strong></p>
		</div>
	<?php }
}
add_action( 'post_submitbox_misc_actions', 'adds_editors_reviewing_message' );

// Adds save draft button
function adds_save_draft_button() {
	$status = get_post_status();
	if ( get_current_user_role() == 'author' && $status !== 'pending' ) { ?>
		<div id="draft-action">
			<div id="save-action">
				<input type="submit" name="save" id="save-post" value="Save Draft" class="button">
			</div>
			<div class="clear"></div>
		</div>
	<?php }
}
add_action( 'post_submitbox_misc_actions', 'adds_save_draft_button' );

function change_post_status( $data ) {
	if ( get_current_user_role() == 'author' && $data['post_status'] !== 'pending' ) {
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
		$data['post_status'] = 'draft';
	}
	return $data;
}
add_filter('wp_insert_post_data', 'change_post_status', '99');

// Author can't publish when updating an existing page
function change_status( $post_id ) {
	if ( get_current_user_role() == 'author' ) {
		remove_action('save_post', 'change_status');
		wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
		add_action('save_post', 'change_status');
	}
}
// add_action('publish_post', 'change_status');

// Author can't publish when updating an existing page
function change_status_on_update( $post_id ) {
	if ( get_current_user_role() == 'author' ) {
		remove_action('save_post', 'change_status_on_update');
		wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
		add_action('save_post', 'change_status_on_update');
	}
}
// add_action('pre_post_update', 'change_status_on_update');

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
