<?php

/**
 * Page status dashboard widget functions
 *
 */

// Return top table
function returnTopTemplate($userID = false, $userLogin = false)
{
    if ($userID === false || $userLogin === false) {
        throw new BadFunctionCallException('returnTopTemplate must be passed two arguments');
    }
    if (is_integer($userID) === false) {
        throw new BadFunctionCallException('returnTopTemplate must be passed an integer userID as its first argument');
    }

    if (is_string($userLogin) === false) {
        throw new BadFunctionCallException('returnTopTemplate must be passed a string userLogin as its second argument');
    }

    $template = '<div class="tna-page-status-widget current-user-id-%s"><h4>Hello %s</h4><table><tr><th>Title</th><th>Last modified by</th><th>Current status</th></tr>';

    return sprintf($template, $userID, $userLogin);

}

// Return my_page class
function returnMyPageClass($author, $currentUserLogin)
{
    if ($author === $currentUserLogin) {
        return 'my-page';
    }
    return 'not-my-page';

}

// Return status variable
function returnDisplayStatus($status)
{
    if ($status === 'pending') {
        $display_status = 'web editors reviewing';
    }
    if ($status === 'draft') {
        $display_status = 'with author (draft)';
    }

    return $display_status;

}

// Return table rows
function returnTableContent()
{
    /* Declare variables */
    global $post, $current_user;
    $status = get_post_status($post->ID);
    $author = get_the_modified_author();
    $edit_link = get_edit_post_link($post->ID);
    $title = get_the_title();
    $modified_date = get_the_modified_date($d = 'j/n/y');
    $currentUserLogin = $current_user->user_login;
    $myPageClass = returnMyPageClass($author, $currentUserLogin);
    $display_status = returnDisplayStatus($status);


    $tableContent = '<tr class="page-' . $status . ' ' . $myPageClass . '"><td class="title">' . $title . ' <a href="' . $edit_link . '">edit</a></td><td>' . $author . ' on ' . $modified_date . '</td><td>' . $display_status . '</td></tr>';

    return $tableContent;
}

// Return bottom table
function returnBottomTemplate() {
    return '</table></div>';
}

/**
 * Submit page for review email notification functions
 *
 */

// Email notification function gets user comments
function get_user_changes_comments( $myChanges ) {
    if ($myChanges) {
        return $myChanges;
    }
    return 'No comments provided';
}

// Email notification function gets web editor email address via ID
function get_web_editor_email( $userData = false ) {
    if ($userData) {
        return $userData->user_email;
    }
    return false;
}

// Gets email address from user login name
function get_email_from_user_login( $user ) {
    if ($user) {

        global $wpdb;
        $email = $wpdb->get_var( $wpdb->prepare("
        SELECT user_email
        FROM $wpdb->users
        WHERE user_login = %s
        ", $user
        ) );

        return $email;
    }
    return false;
}