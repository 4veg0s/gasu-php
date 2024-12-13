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

$id_from = $_SESSION['id_user'];
if ($id_from != '') {
    // echo '<pre>';
    // echo print_r($_GET);
    // echo '</pre><br>';

    // echo '<pre>';
    // echo print_r($_POST);
    // echo '</pre>';

    $id_to = $user_id;

    $sql_user_to = 'select * from user where user.id = ' . $id_to;
    $sql_user_from = 'select * from user where user.id = ' . $id_from;

    $user_to = $conn->query($sql_user_to)->fetch_assoc();
    $current_user = $conn->query($sql_user_from)->fetch_assoc();

    $sql_delete_report_by_id = '';
    if (isset($_POST['deleteReportSubmit'])) {
        $report_id_to_delete = $_POST['hidden-delete-id'];
        $sql_delete_report_by_id = 'DELETE FROM `profile_report` where `id` = ' . $report_id_to_delete;

        $conn->query($sql_delete_report_by_id);

        // header("Location: " . $_SERVER['PHP_SELF'] . '?user_id=' . $id_to);
        // exit();
    }
    if (isset($_POST['sendReport'])) {
        if ($_POST['report'] != '') {
            $str = 'INSERT INTO `profile_report` (
                `id_from`, `id_to`, `text`, `creation`
            ) VALUES (' . 
            '' . $id_from . ',' .
            '' . $id_to . ',' .
            '"' . $_POST['report'] . '",' .
            'NOW()' .
            ')';
            
            // echo $str;

            $conn->query($str);

            // header("Location: " . $_SERVER['PHP_SELF'] . '?user_id=' . $id_to);
            // exit();
            $_POST = '';
        }
    }

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
                '<button class="common-button" onclick="toggleReports()">Комментарии о профиле</button>' .
                create_link_button($welcome_page_path, 'На главную') .
            '</div>' .
        '</div>';

    $sql_select_all_reports_by_to = 'SELECT user.F, user.I, user.O, profile_report.id, profile_report.id_from, profile_report.id_to, profile_report.text, profile_report.creation 
    FROM `profile_report` JOIN `user` 
    ON user.id = ' . $id_to . ' and profile_report.id_to = ' . $id_to;
    
    $sql_reports_ordered = '(' . $sql_select_all_reports_by_to . ') order by creation';
    
    $all_reports_ordered_by_creation = $conn->query($sql_reports_ordered);
    
    $all_reports_ordered_by_creation_array = array();

    if ($all_reports_ordered_by_creation->num_rows > 0) {
        while ($report = $all_reports_ordered_by_creation->fetch_assoc()) {
            $all_reports_ordered_by_creation_array[] = $report;
        }
    }

    echo '<div class="modal" id="modal">
            <div class="modal-content">
                <span class="close-button" onclick="closeModal()">&times;</span>
                <div class="modal-header">' .
                '<h2><center>Отзывы о пользователе ' . $user_to['F'] . ' ' . $user_to['I'] . ' ' . $user_to['O'] . '</center></h2>' .
            '</div>
            <div class="modal-body">';
    
    $files = scandir($file_destination_path);
    $report_blocks = '';
    $next_date = '';
    foreach ($all_reports_ordered_by_creation_array as $report) {
        $sql_user_from = 'SELECT 
            id, F, I, O, login, role
            FROM user where id = ' . $report['id_from']; // инфа о пользователе, оставившем отзыв

        $user_from = $conn->query($sql_user_from)->fetch_assoc();

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

        $prev_date = $next_date;
        $report_block = '';
        $next_date = explode(' ', $report['creation'])[0];
        
        // $message_time = explode(' ', $report['creation'])[1];

        $report_author_avatar = profile_link($user_from['id'], '<img src="' . $img_route . '" class="" title="' . $user_from['F'] . ' ' . $user_from['I'] . ' ' . $user_from['O'] . '"/>');

        $next_report = '<div class="report">
            <div class="review-header">
                    <div class="report-author">' .
                        $report_author_avatar .
                        '<span>' . $user_from['F'] . ' ' . $user_from['I'] . ' ' . $user_from['O'] . '</span>' .
                    '</div>' .  // report-author
                    '<div class="report-date">' . $next_date . '</div>' .
            '</div>' .
            '<div class="report-text">' . $report['text'] . '</div>' .
            ($current_user['id'] == $report['id_from'] || $current_user['role'] == $admin_role ? '<div class="message_buttons">' . create_simple_delete_button('deleteReportSubmit', '', 'x', $report['id']) . '</div>' : '') .
        '</div>';   // report

        // $report_block = $report_block . '<div class="message_time ' . $message_align_css_class . '"><p>' . $message_time . '</p></div>';
        
        $report_blocks = $report_blocks . $next_report;
    }
    echo $report_blocks;    // форма с текстареа и кнопкой отправить

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

    echo '<div class="add-report">
            <div class="send_message_form">
                <form method="POST" action="">' .
                    '<textarea name="report" class="report-text" title="Ваш комментарий" placeholder="Оставьте комментарий о профиле"></textarea><br>' .
                    '<input type="submit" name="sendReport" class="submit-button" value="Отправить">' .
                    //profile_link($user_from['id'], '<img src="' . $img_route . '" class="message_avatar send_message" title="' . $user_from['F'] . ' ' . $user_from['I'] . ' ' . $user_from['O'] . '"/>') .
                '</form>
            </div>
        </div>';

    echo '</div>';  // конец modal
}

echo "<script>
        function toggleReports() {
            const reviews = document.getElementById('reports');
            reviews.style.display = reviews.style.display === 'block' ? 'none' : 'block';
        }

        function toggleReports() {
            const modal = document.getElementById('modal');
            modal.style.display = 'flex';
        }

        function closeModal() {
            const modal = document.getElementById('modal');
            modal.style.display = 'none';
        }

        // Закрытие модального окна при клике вне контента
        window.onclick = function(event) {
            const modal = document.getElementById('modal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    </script>";
?>