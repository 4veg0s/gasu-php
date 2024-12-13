<?php

// CONSTANTS
require_once('../util/constants.php');

function profile_link($user_id, $a_text) {
    $href = profile_pointer($user_id);
    return '<a href="' . $href . '">' . $a_text . '</a>';
}

function chat_link($id_to, $a_text) {
    $href = chat_pointer($id_to);
    return '<a href="' . $href . '">' . $a_text . '</a>';
}

// страница чата с гет параметром (пример: ../pages/chat.php?id_to=1)
function chat_pointer($id_to) {
    global $chat_page_path;
    return $chat_page_path . '?id_to=' . $id_to;
}

// страница профиля с гет параметром (пример: ../pages/profile.php?id_to=1)
function profile_pointer($user_id) {
    global $profile_page_path;
    return $profile_page_path . '?user_id=' . $user_id;
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

function create_simple_delete_button($button_name, $action, $button_caption, $object_id_to_delete) {
    return '<form method="POST" action="' . $action . '" name="form_' . $button_name . '">' .
    '<input type="hidden" id="hidden-delete-id" name="hidden-delete-id" value="' . $object_id_to_delete . '">' .
    '<input type="submit" name="' . $button_name . '" onclick="deleteAlert()" class="delete-button" value="' . $button_caption . '">' .
    '</form>';
}

function create_link_button($link, $button_caption) {
    return '<a href="' . $link . '">' .
    '<input type="button" value="' . $button_caption . '" class="common-button">' .
    '</a>';
}

?>