<?php

session_start();

if ($_GET['act'] == 'logout') {
    $_SESSION['id_user'] = '';
    $logged_in = 0;
}
if ($_SESSION['id_user'] != '') {
    $logged_in = 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login1'])) {
    $login_post_saver = $_POST;
    $sql_select_user_by_login_and_password = "select * from user where login = '" . $_POST['login1'] . "' and password = '" . md5($_POST['password1']) . "'";
    $result = $conn->query($sql_select_user_by_login_and_password)->fetch_assoc();

    $input_error = '';
    $error_message = '';
    if ($result == '' || $result['password'] != md5($_POST['password1'])) {
        $error_message = 'Неверный логин или пароль';
        $input_error = $input_error_class;
    } else {
        $_SESSION['id_user'] = $result['id'];
        echo 'Вы вошли как ' . $result['F'] . ' ' . $result['I'] . '<br><br>';
        $logged_in = 1;

        // Выполнить редирект после успешного логина
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if ($_SESSION['id_user'] != '') {
    $id_user = $_SESSION['id_user'];
    $sql_select_user = "SELECT * FROM user WHERE id = '" . $id_user . "'";
    $user_info = $conn->query($sql_select_user)->fetch_assoc();
    if ($user_info == '') {
        echo 'Ошибка';
    } else {
        $files = scandir($file_destination_path);
        $img_file = '';
        foreach ($files as $file) {
            if (strpos($file, $user_info['login']) !== false) {
                $img_file = $file;
            }
        }
        if ($img_file == '') {
            $img_file = $placeholder_image_name;
        }
        $img_route = $appserv_route_dir . '/' . $img_file;
        echo '<div class="userdata_block">
                <table border=1>' . 
                    '<tr>' .
                        '<th>Фамилия</th>' .
                        '<th>Имя</th>' .
                        '<th>Отчество</th>' .
                        '<th>Email</th>' .
                        '<th>Дата рождения</th>' .
                        '<th>Логин</th>' .
                        // '<th>Пароль</th>' .
                        '<th>Время регистрации</th>' .
                        '<th>Город</th>' .
                        '<th>Статус</th>' .
                        '<th>Роль</th>' .
                        '<th>Аватар</th>' .
                    '</tr>';
        echo '<tr>' .
                    '<td>' . $user_info['F'] . '</td>' . 
                    '<td>' . $user_info['I'] . '</td>' . 
                    '<td>' . $user_info['O'] . '</td>' . 
                    '<td>' . $user_info['email'] . '</td>' . 
                    '<td>' . $user_info['birth'] . '</td>' . 
                    '<td>' . $user_info['login'] . '</td>' . 
                    // '<td>' . $user_info['password'] . '</td>' . 
                    '<td>' . $user_info['registration'] . '</td>' . 
                    '<td>' . '<a href="https://yandex.ru/maps/?ll=' . $row['lng'] . ',' . $row['lat'] . '&z=10" target="_blank">' . $row['name'] . '</a>' . '</td>' . 
                    '<td>' . $user_info['status'] . '</td>' . 
                    '<td>' . $user_info['role'] . '</td>' . 
                    '<td>' . profile_link($user_info['id'], '<img src="' . $img_route . '" class="table_img"/>') . '</td>' . 
                '</tr>';
        echo '</table>
        </div>';
    }
}

if ($logged_in != 1) {
    echo '<div class="form login_block active" id="loginForm">
    <h1><center>Вход</center></h1>
    <form method="POST" action="/atkachev/pages/welcome.php" name="myForm" id="myForm">';
    if ($error_message) {
        echo '<div class="error_message">' . $error_message . '</div>';
    }
    echo '<input type="text" name="login1" title="Введите логин" placeholder="Логин" class="' . $input_error . '" value="' . $login_post_saver['login1'] . '"><br>';
    echo '<input type="password" name="password1" title="Введите пароль" class="' . $input_error . '" placeholder="Пароль"><br>';
    echo '<input type="submit" name="mySubmitLogin" value="Войти">' .
        
        '<input type="button" class="toggle-btn" onclick="toggleForms(`registerForm`)" value="Зарегистрироваться">' .
    '</form>' .
    '</div>';
} else {
    echo '<a href="?act=logout">Выйти</a>' . '<br><br>';
}

// echo '<pre>';
// echo print_r($_SESSION);
// echo '</pre>';
?>