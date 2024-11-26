<?php
$servername = 'localhost';
$username = 'root';
$password = '11111111';
$dbname = 'atkachev_db';    // fixme

// создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);
// проверка подключения
if ($conn->connection_error) {
    die('Connection failed: ' . $conn->connection_error);
}
?>