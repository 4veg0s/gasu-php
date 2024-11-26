<?php
include('db_connection.php');

// CONSTANTS
include('constants.php');

// Functions
include('functions.php');

// Начало вывода верстки ====================================
echo '<link rel="stylesheet" href="01.css"/>';

include('login.php');

if ($_GET['id_to'] != '') {
    include('chat.php');
}

// echo '<pre>';
// echo print_r($files);
// echo '</pre>';

// echo '<pre>';
// echo print_r($_POST);
// echo '</pre>';
// echo '<pre>';
// echo print_r($_FILES['avatar']);
// echo '</pre>';

$input_error_class = 'input_error';

if (isset($_POST['mySubmit'])) {
    $input_error = [];
    $post_saver = $_POST;
    $err = '';
    if ($_POST['F'] == '') {
        $err = $err . 'Не введена фамилия<br>';
        $input_error['F'] = $input_error_class;
    }
    if ($_POST['I'] == '') {
        $err = $err . 'Не введено имя<br>';
        $input_error['I'] = $input_error_class;
    }
    if ($_POST['email'] == '') {
        $err = $err . 'Не введен Email<br>';
        $input_error['email'] = $input_error_class;
    }
    if ($_POST['birth'] == '') {
        $err = $err . 'Не введена дата рождения<br>';
        $input_error['birth'] = $input_error_class;
    }
    if ($_POST['login'] == '') {
        $err = $err . 'Не введен логин<br>';
        $input_error['login'] = $input_error_class;
    }
    if ($_POST['password'] == '') {
        $err = $err . 'Не введен пароль<br>';
        $input_error['password'] = $input_error_class;
    }
    if ($_POST['id_city'] == '' || $_POST['id_city'] == 'Город') {
        $err = $err . 'Не введен город<br>';
        $input_error['id_city'] = $input_error_class;
    }
    if ($_POST['password'] != $_POST['password_repeated']) {
        $err = $err . 'Пароли не совпадают<br>';
        $input_error['password_repeated'] = $input_error_class;
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
        echo $err;
    }
}

if ($logged_in == 1) {
    $sql = 'SELECT 
    user.id as user_id, user.F, user.I, user.O, user.email, user.birth, user.login, user.password, user.registration, city.id as city_id, city.name, user.status, user.role
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
                '<th>Пароль</th>' .
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
            $chat_link = chat_link($row['user_id'], 'Диалог');
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
                    '<td>' . $row['password'] . '</td>' . 
                    '<td>' . $row['registration'] . '</td>' . 
                    '<td>' . '<a href="https://yandex.ru/maps/?ll=' . $row['lng'] . ',' . $row['lat'] . '&z=10" target="_blank">' . $row['name'] . '</a>' . '</td>' . 
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
} else {
    $sql_select_all_from_city = 'select * from city';
    $result_cities = $conn->query($sql_select_all_from_city);
    $select_tag_str = '';
    if ($result_cities->num_rows > 0) {
        $cities = [];
        for ($i = 0; $i < $result_cities->num_rows; $i++) {
            $cities[$i] = $result_cities->fetch_assoc();
            $select_tag_str = $select_tag_str . '<option value="' . $cities[$i]['id'] . '">' . $cities[$i]['name'] . '</option>';
        }
    }
    echo 'Зарегистрироваться<br>';
    echo '<div class="form block">
    <form enctype="multipart/form-data" method="POST" href="" name="myForm" id="myForm">' .
            '<input type="file" name="avatar" title="Загрузите изображение для аватарки">' . '<br>' .
            '<input type="text" name="F" title="Введите фамилию" value="' . $post_saver['F'] . '" class="' . $input_error['F'] . '" placeholder="Фамилия"><br>' .
            '<input type="text" name="I" title="Введите имя" value="' . $post_saver['I'] . '" class="' . $input_error['I'] . '" placeholder="Имя"><br>' .
            '<input type="text" name="O" title="Введите отчество (при наличии)" value="' . $post_saver['O'] . '" class="' . $input_error['O'] . '" placeholder="Отчество"><br>' .
            '<input type="date" name="birth" title="Укажите дату рождения" value="' . $post_saver['birth'] . '" class="' . $input_error['birth'] . '"><br>' .
            '<select name="id_city">' .
            '<option selected disabled title="Укажите город">Город</option>' .
                $select_tag_str .
            '</select>' . '<br>' .
            '<input type="email" name="email" title="Введите адрес электронной почты" value="' . $post_saver['email'] . '" class="' . $input_error['email'] . '" placeholder="Email"><br>' .
            '<input type="text" name="login" title="Введите логин" value="' . $post_saver['login'] . '" class="' . $input_error['login'] . '" placeholder="Логин"><br>' .
            '<input type="password" name="password" title="Введите пароль" value="' . $post_saver['password'] . '" class="' . $input_error['password'] . '" placeholder="Пароль"><br>' .
            '<input type="password" name="password_repeated" title="Повторите введенный пороль" value="' . '' . '" class="' . $input_error['password_repeated'] . '" placeholder="Повторите пароль"><br>' .
            '<input type="submit" name="mySubmit" value="Добавить">' .
        '</form>
        </div>';
}

// закрытие подключения
$conn->close();
?>