<?php

function profile_link($user_id, $a_text) {
    $href = 'profile.php?user_id=';
    return '<a href="' . $href . $user_id . '">' . $a_text . '</a>';
}

function chat_link($id_to, $a_text) {
    $href = 'chat.php?id_to=';
    return '<a href="' . $href . $id_to . '">' . $a_text . '</a>';
}

function create_simple_get_button($button_name, $action, $value) {
    return '<form method="GET" action="' . $action . '" name="form_' . $button_name . '">' .
    '<input type="button" name="' . $button_name . '" value="' . $value . '">' .
    '</form>';
}

function create_simple_post_button($button_name, $action, $value) {
    return '<form method="POST" action="' . $action . '" name="form_' . $button_name . '">' .
    '<input type="submit" name="' . $button_name . '" value="' . $value . '">' .
    '</form>';
}

function create_link_button($link, $button_caption) {
    return '<a href="' . $link . '">' .
    '<input type="button" value="' . $button_caption . '">' .
    '</a>';
}

?>