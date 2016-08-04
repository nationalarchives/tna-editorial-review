<?php

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

function returnMyPageClass($author, $currentUserLogin)
{
    if($author === $currentUserLogin) {
        return 'my-page';
    }
    return '';

}

function returnDisplayStatus($status)
{
    if($status === 'pending') {
        $display_status = 'web editors reviewing';
    }
    if($status === 'draft') {
        $display_status = 'with author';
    }

    return $display_status;

}
