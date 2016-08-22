<?php
/**
 * Submit page for review email notification
 * Sends an email notifying Editors that a devolved editor has made a change
 *
 */

add_action( 'post_submitbox_misc_actions', 'adds_tell_us_what_changes_textarea' );
function adds_tell_us_what_changes_textarea(){
	global $post;
	$status = get_post_status( $post->ID );
	if ( $status == 'draft' || $status == 'auto-draft' ) { ?>
	<div class="misc-pub-section changes-comment">
		<label for="my-changes">Tell us what changes you have made:</label><br />
		<textarea id="my-changes" name="my-changes"></textarea>
	</div>
	<?php }
}

function wp_mail_set_text_body( $phpmailer ) {
	if (empty($phpmailer->AltBody)) {
		$phpmailer->AltBody = strip_tags($phpmailer->Body);
	}
}
add_action('phpmailer_init','wp_mail_set_text_body');

function html_email_subject_pending( $name ) {

	// HTML email footer (This HTML format will only work with Outlook)
	$subject = 'Editorial review: ' . $name . ' submitted a page for review';

	return $subject;
}

function html_email_header( $title ) {

	// HTML email header (This HTML format will only work with Outlook)
	$html_header = '<html><head>';
	$html_header .= '<title>' . $title . '</title>';
	$html_header .= '<style type="text/css">body{font-family:Arial,sans-serif;font-size:16px;}a,strong{color:#0073aa;}</style>';
	$html_header .= '</head><body>';

	return $html_header;
}

function html_email_footer() {

	// HTML email footer (This HTML format will only work with Outlook)
	$html_footer = '<hr></body></html>';

	return $html_footer;
}

function html_email_body( $sender, $title, $shortlink, $edit_link, $page_url, $date ) {

	// Variables
	global $post;
	$comments = get_user_changes_comments( filter_input(INPUT_POST, 'my-changes', FILTER_SANITIZE_SPECIAL_CHARS) );

	// Greeting
	$greetings = array( 'Hello', 'G&lsquo;day', 'Hey', 'Buna', 'Kon&lsquo;nichiwa', 'Bonjour', 'Hola', 'Ciao', 'Vannakam' );

	// HTML email body (This HTML format will only work with Outlook)
	$html_message = '<p>' . $greetings[array_rand($greetings, 1)] . ' web editor,</p>';
	$html_message .= '<h3><strong>' . $sender . '</strong> has submitted ';
	$html_message .= '<strong>' . $title . '</strong> for review</h3>';
	$html_message .= '<p>Page title: ' . $title . ' ';
	$html_message .= '<small>( <a href="' . $shortlink . '&preview=true">Preview</a> | ';
	$html_message .= '<a href="' . $edit_link . '">Edit</a> )</small></p>';
	$html_message .= '<p>Page hierarchy: ' . $page_url . '</p>';
	$html_message .= '<p>Page ID: ' . $post->ID . '</p>';
	$html_message .= '<p>Modified: ' . $date . '</p>';
	$html_message .= '<p>' . $sender . ' comments:</p>';
	$html_message .= '<p>' . $comments . '</p>';

	return $html_message;
}

function notify_editor_of_pending() {

	// Current user (Sender)
	$current_user = wp_get_current_user();

	// Send email to these email addresses
	$to = array( get_web_editor_email( get_userdata(22) ), $current_user->user_email );

	// Email Subject
	$subject = html_email_subject_pending( $current_user->display_name );

	// Email message
	$message = html_email_header( html_email_subject_pending( $current_user->display_name ) )
	           . html_email_body(
		           $current_user->display_name,                 // Sender
		           get_the_title(),                             // Page title
		           wp_get_shortlink(),                          // Preview link
		           get_edit_post_link(),                        // Edit link
		           get_permalink(),                             // Page URL
		           get_the_modified_date($d = 'l d M Y, G:i')   // Page modified date
	           )
	           . html_email_footer();

	wp_mail( $to, $subject, $message );
}
add_action( 'new_to_pending', 'notify_editor_of_pending' );
add_action( 'draft_to_pending', 'notify_editor_of_pending' );
add_action( 'auto-draft_to_pending', 'notify_editor_of_pending' );
