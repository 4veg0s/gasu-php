<?php
require('../util/db_connection.php');

// CONSTANTS
require('../util/constants.php');

// Functions
require('../util/functions.php');

// Начало вывода верстки ====================================
echo '<link rel="stylesheet" href="../style/welcome.css"/>';

$input_error_class = 'input_error';     // класс ошибки ввода для подсветки красным

require('../pages/login.php');

if ($_GET['id_to'] != '') {
    require_once('../pages/chat.php');
}

// echo '<pre>';
// echo print_r($_POST);
// echo '</pre>';
// echo '<pre>';
// echo print_r($_FILES['avatar']);
// echo '</pre>';

if (isset($_POST['mySubmit'])) {
    $input_errors = [];
    $error_messages = [];
    $post_saver = $_POST;
    $err = '';
    if ($_POST['F'] == '') {
        $err = $err . 'Не введена фамилия<br>';
        $error_messages['F'] = 'Не введена фамилия';
        $input_errors['F'] = $input_error_class;
    }
    if ($_POST['I'] == '') {
        $err = $err . 'Не введено имя<br>';
        $error_messages['I'] = 'Не введено имя';
        $input_errors['I'] = $input_error_class;
    }
    if ($_POST['email'] == '') {
        $err = $err . 'Не введен Email<br>';
        $error_messages['email'] = 'Не введен Email';
        $input_errors['email'] = $input_error_class;
    }
    if ($_POST['birth'] == '') {
        $err = $err . 'Не введена дата рождения<br>';
        $error_messages['birth'] = 'Не введена дата рождения';
        $input_errors['birth'] = $input_error_class;
    }
    if ($_POST['login'] == '') {
        $err = $err . 'Не введен логин<br>';
        $error_messages['login'] = 'Не введен логин';
        $input_errors['login'] = $input_error_class;
    }
    if ($_POST['password'] == '') {
        $err = $err . 'Не введен пароль<br>';
        $error_messages['password'] = 'Не введен пароль';
        $input_errors['password'] = $input_error_class;
    }
    if ($_POST['id_city'] == '' || $_POST['id_city'] == 'Город') {
        $err = $err . 'Не введен город<br>';
        $error_messages['id_city'] = 'Не выбран город';
        $input_errors['id_city'] = $input_error_class;
    }
    if ($_POST['password'] != $_POST['password_repeated']) {
        $err = $err . 'Пароли не совпадают<br>';
        $error_messages['password_repeated'] = 'Пароли не совпадают';
        $input_errors['password_repeated'] = $input_error_class;
    }
    if ($err == '') {
        if ($_FILES['avatar']['tmp_name'] != '') {
            $tmp_filename = $_FILES['avatar']['tmp_name'];
            $file_type_suffix = '.' . str_replace('image/', '', $_FILES['avatar']['type']);
            move_uploaded_file($tmp_filename, $file_destination_path . "\\" . $_POST['login'] . $file_type_suffix);
        }
        $str = 'INSERT INTO user (
            `F`, `I`, `O`, `email`, `birth`, `login`, `password`, `registration`, `id_city`
        ) VALUES (' . 
        '"' . $_POST['F'] . '",' .
        '"' . $_POST['I'] . '",' .
        '"' . $_POST['O'] . '",' .
        '"' . $_POST['email'] . '",' .
        '"' . $_POST['birth'] . '",' .
        '"' . $_POST['login'] . '",' .
        '"' . md5($_POST['password']) . '",' .
        'NOW(),' .
        '"' . $_POST['id_city'] . '"' .
        ')';
        //echo $str;   // DEBUG
        $conn->query($str);
        $post_saver = '';
    } else {
        //echo $err;
    }
}

if ($logged_in == 1) {
    // echo '<div class="container">';  // открывается в login.php

    $sql = 'SELECT 
    user.id as user_id, user.F, user.I, user.O, user.email, user.birth, user.login, user.password, user.registration, city.id as city_id, city.name, city.lng, city.lat, user.status, user.role
    FROM user
        left join city on user.id_city = city.id';

    $result = $conn->query($sql);
    // проверка наличия результатов и вывод данных
    if ($result->num_rows > 0) {
        // вывод заголовка таблицы
        echo '<div class="table_block">
                <table border=1>';
        echo '<tr>' .
                '<th>Фамилия</th>' .
                '<th>Имя</th>' .
                '<th>Отчество</th>' .
                '<th>Email</th>' .
                '<th>Дата рождения</th>' .
                '<th>Логин</th>' .
                //'<th>Пароль</th>' .
                '<th>Время регистрации</th>' .
                '<th>Город</th>' .
                '<th>Статус</th>' .
                '<th>Роль</th>' .
                '<th>Аватар</th>' .
                '<th>Чат</th>' .
            '</tr>';
        // вывод данных каждой строки
        while ($row = $result->fetch_assoc()) {
            $files = scandir($file_destination_path);
            $img_file = '';
            foreach ($files as $file) {
                if (strpos($file, $row['login']) !== false) {
                    $img_file = $file;
                }
            }
            if ($img_file == '') {
                $img_file = $placeholder_image_name;
            }
            $img_route = $appserv_route_dir . '/' . $img_file;
            $profile_link = profile_link($row['user_id'], 'Профиль');
            $chat_link = chat_link($row['user_id'], 'Написать');
            $map_link = '<a href="https://yandex.ru/maps/?ll=' . $row['lng'] . ',' . $row['lat'] . '&z=10" target="_blank">' . $row['name'] . '</a>';
            // echo '<pre>';
            // echo print_r($row);
            // echo '</pre>';
            echo '<tr>' .
                    '<td>' . $row['F'] . '</td>' . 
                    '<td>' . $row['I'] . '</td>' . 
                    '<td>' . $row['O'] . '</td>' . 
                    '<td>' . $row['email'] . '</td>' . 
                    '<td>' . $row['birth'] . '</td>' . 
                    '<td>' . $row['login'] . '</td>' . 
                    //'<td>' . $row['password'] . '</td>' . 
                    '<td>' . $row['registration'] . '</td>' . 
                    '<td>' . $map_link . '</td>' . 
                    '<td>' . $row['status'] . '</td>' . 
                    '<td>' . $row['role'] . '</td>' . 
                    '<td>' . profile_link($row['user_id'], '<img src="' . $img_route . '" class="table_img"/>') . '</td>' . 
                    '<td>' . $chat_link . '</td>' . 
                '</tr>';
        }
        echo '</table>
        </div>';
    } else {
        echo '0 results';
    }

    echo '</div>';  // закрытие container из login.php
} else {
    $sql_select_all_from_city = 'select * from city';
    $result_cities = $conn->query($sql_select_all_from_city);
    $select_tag_str = '';
    if ($result_cities->num_rows > 0) {
        $cities = [];
        for ($i = 0; $i < $result_cities->num_rows; $i++) {
            $cities[$i] = $result_cities->fetch_assoc();
            $select_tag_str = $select_tag_str . '<option value="' . $cities[$i]['id'] . '"' . ($cities[$i]['id'] == $post_saver['id_city'] ? 'selected' : '') . '>' . $cities[$i]['name'] . '</option>';
        }
    }
    
    // echo '<div class="container log-reg">';

    echo '<div class="form block" id="registerForm">
    <h1><center>Регистрация</center></h1>
    <form enctype="multipart/form-data" method="POST" href="" name="myForm" id="myForm">';
            echo '<input type="file" name="avatar" title="Загрузите изображение для аватарки">' . '<br>';
            if ($error_messages['F']) {
                echo '<div class="error_message">' . $error_messages['F'] . '</div>';
            }
            echo '<input type="text" name="F" title="Введите фамилию" value="' . $post_saver['F'] . '" class="' . $input_errors['F'] . '" placeholder="Фамилия"><br>';
            if ($error_messages['I']) {
                echo '<div class="error_message">' . $error_messages['I'] . '</div>';
            }
            echo '<input type="text" name="I" title="Введите имя" value="' . $post_saver['I'] . '" class="' . $input_errors['I'] . '" placeholder="Имя"><br>';
            echo '<input type="text" name="O" title="Введите отчество (при наличии)" value="' . $post_saver['O'] . '" class="' . $input_errors['O'] . '" placeholder="Отчество"><br>';
            if ($error_messages['birth']) {
                echo '<div class="error_message">' . $error_messages['birth'] . '</div>';
            }
            echo '<input type="date" name="birth" title="Укажите дату рождения" value="' . $post_saver['birth'] . '" class="' . $input_errors['birth'] . '"><br>';
            if ($error_messages['id_city']) {
                echo '<div class="error_message">' . $error_messages['id_city'] . '</div>';
            }
            echo '<select name="id_city">' .
            '<option title="Укажите город">Город</option>' .
                $select_tag_str .
            '</select>' . '<br>';
            if ($error_messages['email']) {
                echo '<div class="error_message">' . $error_messages['email'] . '</div>';
            }
            echo '<input type="email" name="email" title="Введите адрес электронной почты" value="' . $post_saver['email'] . '" class="' . $input_errors['email'] . '" placeholder="Email"><br>';
            if ($error_messages['login']) {
                echo '<div class="error_message">' . $error_messages['login'] . '</div>';
            }
            echo '<input type="text" name="login" title="Введите логин" value="' . $post_saver['login'] . '" class="' . $input_errors['login'] . '" placeholder="Логин"><br>';
            if ($error_messages['password']) {
                echo '<div class="error_message">' . $error_messages['password'] . '</div>';
            }
            echo '<input type="password" name="password" title="Введите пароль" value="' . $post_saver['password'] . '" class="' . $input_errors['password'] . '" placeholder="Пароль"><br>';
            if ($error_messages['password_repeated']) {
                echo '<div class="error_message">' . $error_messages['password_repeated'] . '</div>';
            }
            echo '<input type="password" name="password_repeated" title="Повторите введенный пороль" value="' . '' . '" class="' . $input_errors['password_repeated'] . '" placeholder="Повторите пароль"><br>';
            echo '<input type="submit" name="mySubmit" value="Зарегистрироваться">' .
            
            '<input type="button" class="toggle-btn" onclick="toggleForms(`loginForm`)" value="Авторизоваться">' .
        '</form>' . 
        '</div>';

        echo '</div>';  // закрытие container log-reg

        
    echo "<script>
        // Читаем сохраненное состояние из localStorage
        const activeForm = localStorage.getItem('activeForm') || 'loginForm'; // По умолчанию login
        toggleForms(activeForm);

        function toggleForms(formId) {
            document.querySelectorAll('.form').forEach(form => form.classList.remove('active'));
            document.getElementById(formId).classList.add('active');
            // Сохраняем состояние в localStorage
            localStorage.setItem('activeForm', formId);
        }
    </script>";
}

// закрытие подключения
$conn->close();
?>