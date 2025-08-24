<?php
// Подключение к MariaDB
$host = 'db';
$db = 'devdb';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "<h2 style='color:green'>✅ Подключено к MariaDB!</h2>";
} catch (PDOException $e) {
    echo "<h2 style='color:red'>❌ Ошибка БД: " . $e->getMessage() . "</h2>";
}

// Отправка письма
$to = 'test@example.com';
$subject = 'Тестовое письмо из Docker';
$message = 'Привет Это письмо отправлено через PHP mail() и перехвачено Mailhog.';
$headers = 'From: no-reply@local.dev' . "\r\n" .
    'Reply-To: no-reply@local.dev' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo "<h2 style='color:blue'>📧 Письмо отправлено Проверь Mailhog: <a href='http://localhost:8025' target='_blank'>http://localhost:8025</a></h2>";
} else {
    echo "<h2 style='color:red'>❌ Не удалось отправить письмо</h2>";
}

phpinfo();

file_put_contents('test.txt', 'TEST TEST TEST');
