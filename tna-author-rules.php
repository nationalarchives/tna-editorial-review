<?php

// Hides the publish button if status is pending and the current user is an author
function hide_action_button() {
	if ( is_edit_page() ) {
		$status = get_post_status();
		if ( get_current_user_role() == 'author' && ( $status == 'pending' || $status == 'publish' ) ) { ?>
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

// On edit published page, 'Save draft' button saves as draft
function save_as_draft( $data, $postarr ) {
	if ( get_current_user_role() == 'author' && isset( $postarr['save'] ) === true ) {
		// Checks 'Save Draft' button's value
		if ( $postarr['save'] == 'Save Draft' ) {
			$data['post_status'] = 'draft';
		}
	}
	return $data;
}
add_filter( 'wp_insert_post_data' , 'save_as_draft' , '99', 2 );
