<?php

echo "<h1>Hello from PHP " . phpversion() . " running on Alpine!</h1>";

//echo "<h2>Database connection test:</h2>";
//$host = 'mysql'; // Замените на имя сервиса вашей базы данных, если она используется
//$user = 'root';
//$password = 'password';
//$database = 'test';
//
//try {
//    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $user, $password);
//    echo "<p style='color:green;'>Connection to database successful!</p>";
//} catch (PDOException $e) {
//    echo "<p style='color:red;'>Could not connect to database: " . $e->getMessage() . "</p>";
//}

phpinfo();
