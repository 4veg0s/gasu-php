<?php
// DB CONNECTION
require_once('../util/db_connection.php');
// CONSTANTS
require_once('../util/constants.php');
// Functions
require_once('../util/functions.php');

session_start();

echo '<link rel="stylesheet" href="../style/chat.css"/>';

echo '<head>
        <title>Диалог</title>
    </head>';

// echo '<pre>';
// echo print_r($_POST);
// echo '</pre>';

$id_from = $_SESSION['id_user'];
if ($id_from != '') {
    // echo '<pre>';
    // echo print_r($_GET);
    // echo '</pre>';

    $id_to = $_GET['id_to'];

    $sql_user_to = 'select * from user where user.id = ' . $id_to;
    $sql_user_from = 'select * from user where user.id = ' . $id_from;

    $user_to = $conn->query($sql_user_to)->fetch_assoc();
    $user_from = $conn->query($sql_user_from)->fetch_assoc();

    $sql_delete_message_by_id = '';
    if (isset($_POST['deleteMessageSubmit'])) {
        $message_id_to_delete = $_POST['hidden-delete-id'];
        $sql_delete_message_by_id = 'DELETE FROM `message` where `id` = ' . $message_id_to_delete;

        $conn->query($sql_delete_message_by_id);

        header("Location: " . $_SERVER['PHP_SELF'] . '?id_to=' . $id_to);
        // exit();
    }
    if (isset($_POST['sendMessage'])) {
        if ($_POST['message'] != '') {
            $str = 'INSERT INTO message (
                `id_from`, `id_to`, `text`, `creation`, `status`
            ) VALUES (' . 
            '' . $user_from['id'] . ',' .
            '' . $user_to['id'] . ',' .
            '"' . $_POST['message'] . '",' .
            'NOW(),' .
            '' . 1 . '' .
            ')';
            
            $conn->query($str);

            header("Location: " . $_SERVER['PHP_SELF'] . '?id_to=' . $id_to);
            // exit();
        }
    }

    $sql_select_all_messages_by_from = 'SELECT user.F, user.I, user.O, message.id, message.id_from, message.id_to, message.text, message.creation, message.status 
    FROM `message` JOIN `user` 
    ON user.id = ' . $id_from . ' and message.id_from = ' . $id_from . ' and message.id_to = ' . $id_to;

    $sql_select_all_messages_by_to = 'SELECT user.F, user.I, user.O, message.id, message.id_from, message.id_to, message.text, message.creation, message.status 
    FROM `message` JOIN `user` 
    ON user.id = ' . $id_to . ' and message.id_from = ' . $id_to . ' and message.id_to = ' . $id_from;
    
    $sql_all_messages = '(' . $sql_select_all_messages_by_from . ') union (' . $sql_select_all_messages_by_to . ') order by creation';
    
    $all_messages_ordered_by_creation = $conn->query($sql_all_messages);
    
    $all_messages_ordered_by_creation_array = array();

    if ($all_messages_ordered_by_creation->num_rows > 0) {
        while ($message = $all_messages_ordered_by_creation->fetch_assoc()) {
            $all_messages_ordered_by_creation_array[] = $message;
        }
    }

    echo '<div class="container">';

    echo '<h2><center>Диалог с пользователем ' . profile_link($user_to['id'], $user_to['F'] . ' ' . $user_to['I'] . ' ' . $user_to['O']) . '</center></h2>';
    echo '<div class="dialog_block">';
    echo '<div class="dialog_header">' .
        '</div>';

    $files = scandir($file_destination_path);
    $messages_blocks = '';
    $next_date = '';
    $sender_align_css_class = "sender";
    $receiver_align_css_class = "receiver";
    foreach ($all_messages_ordered_by_creation_array as $message) {
        $current_user = $message['id_from'] == $user_from['id'] ? $user_from : $user_to;
        $message_align_css_class = $message['id_from'] == $user_from['id'] ? $sender_align_css_class : $receiver_align_css_class;
        $img_file = '';
        foreach ($files as $file) {
            if (strpos($file, $current_user['login']) !== false) {
                $img_file = $file;
            }
        }
        if ($img_file == '') {
            $img_file = $placeholder_image_name;
        }
        $img_route = $appserv_route_dir . '/' . $img_file;

        $prev_date = $next_date;
        $message_block = '';
        $next_date = explode(' ', $message['creation'])[0];
        if ($prev_date != $next_date) {
            $message_block = $message_block . '<div class="chat_date">' .
                                                $next_date .
                                            '</div>';
        }
        $message_time = explode(' ', $message['creation'])[1];

        $message_sender_avatar = profile_link($current_user['id'], '<img src="' . $img_route . '" class="message_avatar" title="' . $current_user['F'] . ' ' . $current_user['I'] . ' ' . $current_user['O'] . '"/>');

        $message_block = $message_block . '<div class="message ' . $message_align_css_class . '">' .
                    '<p>' . ($message['id_from'] == $user_from['id'] ? $message['text'] . $message_sender_avatar : $message_sender_avatar . $message['text']) . '</p>' .
                    ($user_from['id'] == $message['id_from'] || $user_from['role'] == $admin_role ? '<div class="message_buttons">' . create_simple_delete_button('deleteMessageSubmit', '', 'x', $message['id']) . '</div>' : '') .
                    '</div>';   // закрытие message
        $message_block = $message_block . '<div class="message_time ' . $message_align_css_class . '"><p>' . $message_time . '</p></div>';
        
        $messages_blocks = $messages_blocks . $message_block;
    }
    echo '<div class="dialog_area">' .
            $messages_blocks . 
    '</div>';    // форма с текстареа и кнопкой отправить
    
    $img_file = '';
    foreach ($files as $file) {
        if (strpos($file, $user_from['login']) !== false) {
            $img_file = $file;
        }
    }
    if ($img_file == '') {
        $img_file = $placeholder_image_name;
    }
    $img_route = $appserv_route_dir . '/' . $img_file;

    echo '<div class="send_message_form">
    <form method="POST" action="" name="myForm" id="myForm">' .
        '<textarea name="message" class="sendMessageArea" title="Ваше сообщение" placeholder="Введите сообщение"></textarea><br>' .
        '<input type="submit" name="sendMessage" class="sendMessageButton" value="Отправить">' .
        profile_link($user_from['id'], '<img src="' . $img_route . '" class="message_avatar send_message" title="' . $user_from['F'] . ' ' . $user_from['I'] . ' ' . $user_from['O'] . '"/>') .
    '</form>
    </div>';
    echo '</div>';  // конец dialog_block

    echo '<div class="bottom-block">' . create_link_button('../pages/welcome.php', 'На главную') . '</div>';

    echo '</div>';  // конец container
}


// todo: называть картинку при отправке в чате md5(user_from . user_to . message['creation'])

echo "<script>
    document.addEventListener('DOMContentLoaded', function () {
    const dialogArea = document.querySelector('.dialog_area');
    dialogArea.scrollTop = dialogArea.scrollHeight;
});
</script>"

?>