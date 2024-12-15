<?php

session_start();

if ($_GET['act'] == 'logout') {
    $_SESSION['id_user'] = '';
    $logged_in = 0;
}
if ($_SESSION['id_user'] != '') {
    $logged_in = 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login1']) && isset($_POST['password1'])) {
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
            // echo 'Вы вошли как ' . $result['F'] . ' ' . $result['I'] . '<br><br>';
            $logged_in = 1;

            // Выполнить редирект после успешного логина
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $error_message = 'Введите логин и пароль';
        $input_error = $input_error_class;
    }
}

if ($_SESSION['id_user'] != '') {
    $id_user = $_SESSION['id_user'];
    $sql_select_user = 'SELECT 
    user.id as user_id, user.F, user.I, user.O, user.email, user.birth, user.login, user.password, user.registration, city.id as city_id, city.name, city.name, city.lng, city.lat, user.status, user.role
    FROM user
        left join city on user.id_city = city.id
        having user_id = ' . $id_user;
        // "SELECT * FROM user WHERE id = '" . $id_user . "'";
    $current_user_info = $conn->query($sql_select_user)->fetch_assoc();
    if ($current_user_info == '') {
        echo 'Ошибка';
    } else {
        echo '<div class="container">'; // закрывается в welcome.php

        $files = scandir($file_destination_path);
        $img_file = '';
        foreach ($files as $file) {
            if (strpos($file, $current_user_info['login']) !== false) {
                $img_file = $file;
            }
        }
        if ($img_file == '') {
            $img_file = $placeholder_image_name;
        }
        $img_route = $appserv_route_dir . '/' . $img_file;
        $map_link = '<a href="https://yandex.ru/maps/?ll=' . $current_user_info['lng'] . ',' . $current_user_info['lat'] . '&z=10" target="_blank">' . $current_user_info['name'] . '</a>';

        echo '<div class="userdata_block scroll-container">
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
                    '<td>' . $current_user_info['F'] . '</td>' . 
                    '<td>' . $current_user_info['I'] . '</td>' . 
                    '<td>' . $current_user_info['O'] . '</td>' . 
                    '<td>' . $current_user_info['email'] . '</td>' . 
                    '<td>' . $current_user_info['birth'] . '</td>' . 
                    '<td>' . $current_user_info['login'] . '</td>' . 
                    // '<td>' . $current_user_info['password'] . '</td>' . 
                    '<td>' . $current_user_info['registration'] . '</td>' . 
                    '<td>' . $map_link . '</td>' . 
                    '<td>' . $current_user_info['status'] . '</td>' . 
                    '<td>' . $current_user_info['role'] . '</td>' . 
                    '<td>' . profile_link($current_user_info['user_id'], '<img src="' . $img_route . '" class="table_img"/>') . '</td>' . 
                '</tr>';
        echo '</table>
        </div>';

        // echo '</div>';  // закрытие container в welcome.php
    }
}

if ($logged_in != 1) {
    echo '<div class="container log-reg">';

    echo '<div class="form login_block active" id="loginForm">
    <h1><center>Вход</center></h1>
    <form method="POST" action="/atkachev/pages/welcome.php" name="myLoginForm" id="myLoginForm">';
    if ($error_message) {
        echo '<div class="error_message">' . $error_message . '</div>';
    }
    echo '<input type="text" name="login1" title="Введите логин" placeholder="Логин" class="' . $input_error . '" value="' . $login_post_saver['login1'] . '"><br>';
    echo '<input type="password" name="password1" title="Введите пароль" class="' . $input_error . '" placeholder="Пароль"><br>';
    echo '<input type="submit" name="mySubmitLogin" value="Войти">' .
        
        '<input type="button" class="toggle-btn" onclick="toggleForms(`registerForm`)" value="Зарегистрироваться">' .
    '</form>' .
    '</div>';

    // echo '</div>';  // закрытие container log-reg
} else {
    echo create_link_button('../pages/welcome.php?act=logout', 'Выйти'); // '<a href="?act=logout">Выйти</a>' . '<br><br>';
}

// echo '<pre>';
// echo print_r($_SESSION);
// echo '</pre>';
?>