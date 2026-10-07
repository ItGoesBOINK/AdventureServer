<?php

require_once __DIR__ . '/config.php';

$host = '127.0.0.1';
$port = '3306';
$database = getenv('MYSQL_DATABASE');
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');

/*
echo "Database:" . $database . "<br>";
echo "User:" . $username . "<br>";
echo "Password:" . $password . "<br>";
*/

$dsn = "mysql:host=$host;port=$port;dbname=$database;charset-utf8mb4";
$pdo = new PDO($dsn, $username, $password);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

?>