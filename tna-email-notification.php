<?php
/**
 * Submit page for review email notification
 * Sends an email notifying Editors that a devolved editor has made a change
 *
 */

add_action( 'post_submitbox_misc_actions', 'adds_tell_us_what_changes_textarea' );
function adds_tell_us_what_changes_textarea(){
	?>
	<div class="misc-pub-section changes-comment">
		<label for="changes">Tell us what changes you have made</label><br />
		<textarea id="changes" name="changes"></textarea>
	</div>
	<?php
}

add_action('phpmailer_init','wp_mail_set_text_body');
function wp_mail_set_text_body($phpmailer) {
	if (empty($phpmailer->AltBody)) {$phpmailer->AltBody = strip_tags($phpmailer->Body);}
}

function notify_editor_for_pending( $post ) {
	$changes = filter_input(INPUT_POST, 'changes', FILTER_SANITIZE_SPECIAL_CHARS);
	if ($changes) {
		$comments = $changes;
	} else {
		$comments = 'No user comments';
	}
	$current_user = wp_get_current_user();
	$user = $current_user->display_name;
	$to = array ( 'domingobishop@gmail.com', $current_user->user_email );
	$subject = 'Editorial review: ' . $user . ' submitted a page for review';
	$message = '<p><strong>' . $user . '</strong> has submitted <strong>' . get_the_title() . '</strong> for review.</p>';
	$message .= '<p>Page title: ' . get_the_title() . ' <a href="' . wp_get_shortlink() . '&preview=true">Preview</a> <a href="' . get_edit_post_link() . '">Edit</a></p>';
	$message .= '<p>Modified: ' . get_the_modified_date($d = 'j/n/y h:i') . '</p>';
	$message .= '<p>' . $user . ' comments: ' . $comments . '</p>';
	wp_mail( $to, $subject, $message );
}

add_action( 'new_to_pending', 'notify_editor_for_pending' );
add_action( 'draft_to_pending', 'notify_editor_for_pending' );
add_action( 'auto-draft_to_pending', 'notify_editor_for_pending' );
add_action( 'publish_post', 'notify_editor_for_pending' );
add_action( 'publish_page', 'notify_editor_for_pending' );
