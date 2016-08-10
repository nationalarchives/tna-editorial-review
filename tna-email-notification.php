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
		<label for="my-changes">Tell us what changes you have made</label><br />
		<textarea id="my-changes" name="my-changes"></textarea>
	</div>
	<?php
}

function user_email_notifications( $user ) {
	?>
	<h3>Editorial review</h3>
	<table class="form-table">
		<tr>
			<th scope="row">Email notifications</th>
			<td>
				<label for="notification">
					<input id="notification" type="checkbox" name="notification" value="true" <?php checked( esc_attr( get_user_meta( $user->ID, 'notification', true ) ), 'true' ); ?> />
					Subscribe to email notifications
				</label>
			</td>
		</tr>
	</table>
<?php }
add_action( 'show_user_profile', 'user_email_notifications' );
add_action( 'edit_user_profile', 'user_email_notifications' );

function wp_mail_set_text_body($phpmailer) {
	if (empty($phpmailer->AltBody)) {
		$phpmailer->AltBody = strip_tags($phpmailer->Body);
	}
}
add_action('phpmailer_init','wp_mail_set_text_body');

function get_user_changes_comments( $myChanges ) {
	if ($myChanges) {
		return filter_input(INPUT_POST, $myChanges, FILTER_SANITIZE_SPECIAL_CHARS);
	} else {
		return 'No user comments';
	}
}

function notify_editor_of_pending( $post ) {
	$comments = get_user_changes_comments('my-changes');
	$current_user = wp_get_current_user();
	$user = $current_user->display_name;
	$to = array ( 'domingobishop@gmail.com', $current_user->user_email );
	$subject = 'Editorial review: ' . $user . ' submitted a page for review';
	$message = '<p><strong>' . $user . '</strong> has submitted <strong>' . get_the_title() . '</strong> for review.</p>';
	$message .= '<p>Page title: ' . get_the_title() . ' <a href="' . wp_get_shortlink() . '&preview=true">Preview</a> <a href="' . get_edit_post_link() . '">Edit</a></p>';
	$message .= '<p>Modified: ' . get_the_modified_date($d = 'j/n/y G:i') . '</p>';
	$message .= '<p>' . $user . ' comments: ' . $comments . '</p>';
	wp_mail( $to, $subject, $message );
}
add_action( 'new_to_pending', 'notify_editor_of_pending' );
add_action( 'draft_to_pending', 'notify_editor_of_pending' );
add_action( 'auto-draft_to_pending', 'notify_editor_of_pending' );
add_action( 'publish_post', 'notify_editor_of_pending' );
add_action( 'publish_page', 'notify_editor_of_pending' );
