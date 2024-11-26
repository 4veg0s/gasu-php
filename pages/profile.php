<?php
// DB CONNECTION
require_once('../util/db_connection.php');

// CONSTANTS
require_once('../util/constants.php');

// Functions
require_once('../util/functions.php');

session_start();

// Начало вывода верстки ====================================
echo '<link rel="stylesheet" href="../style/profile.css"/>';

$user_id = $_GET['user_id'];

$sql_user = 'SELECT 
    user.id as user_id, user.F, user.I, user.O, user.email, user.birth, user.login, user.password, user.registration, city.id as city_id, city.name, user.status, user.role
    FROM user
        left join city on user.id_city = city.id 
        having user_id = ' . $user_id; // инфа о пользователе с джоином на город

$user = $conn->query($sql_user)->fetch_assoc();

$chat_link = chat_link($user_id, 'Написать сообщение');

$files = scandir($file_destination_path);
$img_file = '';
foreach ($files as $file) {
    if (strpos($file, $user['login']) !== false) {
        $img_file = $file;
    }
}
if ($img_file == '') {
    $img_file = $placeholder_image_name;
}
$img_route = $appserv_route_dir . '/' . $img_file;

echo '<div class="profile-container">' .
        '<div class="profile-image">' .
            '<img src="' . $img_route . '" alt="Profile Image">' .
        '</div>' .
        '<div class="profile-info">' .
            '<h1>' . $user['F'] . ' ' . $user['I'] . ' ' . $user['O'] . '</h1>' .
            '<p><strong>Email:</strong> ' . $user['email'] . '</p>' .
            '<p><strong>Дата рождения:</strong> ' . $user['birth'] . '</p>' .
            '<p><strong>Логин:</strong> ' . $user['login'] . '</p>' .
            '<p><strong>Город:</strong> ' . $user['name'] . '</p>' .
            create_link_button(chat_pointer($user['user_id']), 'Перейти в диалог') .
            create_link_button($welcome_page_path, 'На главную') .
        '</div>' .
    '</div>';
// отображение аватарки фио и тд
// кнопка для перехода в диалог из профиля
// кнопка перехода на всех пользователей

?>