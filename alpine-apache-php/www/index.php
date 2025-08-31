<?php

const TEST_DB = false;
const TEST_MAIL = false;
const TEST_FILE = true;
const TEST_PHPINFO = true;

echo "<h1>PHP " . phpversion() . " on Alpine 3.22</h1>";
echo '<hr style="width: 100%; margin-bottom: 2em">';

////////////////////////////////////////////////////////////
// Проверка подключения к базе данных                     //
////////////////////////////////////////////////////////////
if (TEST_DB) {
    $host = 'db';
    $db = 'devdb';
    $user = 'root';
    $pass = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        echo "<h3 style='color:green'>✅ Подключено к MariaDB!</h3>";
    } catch (PDOException $e) {
        echo "<h3 style='color:red'>❌ Ошибка БД: " . $e->getMessage() . "</h3>";
    }
}

////////////////////////////////////////////////////////////
// Отправка тестового письма                              //
////////////////////////////////////////////////////////////
if (TEST_MAIL) {
    $to = 'test@example.com';
    $subject = 'Тестовое письмо из Docker';
    $message = 'Привет Это письмо отправлено через PHP mail() и перехвачено Mailhog.';
    $headers = 'From: no-reply@local.dev' . "\r\n" .
        'Reply-To: no-reply@local.dev' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    if (mail($to, $subject, $message, $headers)) {
        echo "<h3 style='color:blue'>✅ Письмо отправлено Проверь Mailhog: <a href='http://localhost:8025' target='_blank'>http://localhost:8025</a></h3>";
    } else {
        echo "<h3 style='color:red'>❌ Не удалось отправить письмо</h3>";
    }
}

////////////////////////////////////////////////////////////
// Создание текстового файла                              //
////////////////////////////////////////////////////////////
if (TEST_FILE) {
    $filename = 'test.txt';
    $content = 'TEST TEST TEST';

    if (file_put_contents($filename, $content) !== false) {
        echo '<h3 style="color:green">✅ Был создан файл "' . $filename . '" с содержимым "' . $content . '".</h3>';
    } else {
        echo '<h3 style="color:red">❌ Не удалось создать файл ' . $filename . '</h3>';
    }
}

////////////////////////////////////////////////////////////
// Вывод информации о PHP                                 //
////////////////////////////////////////////////////////////
if (TEST_PHPINFO) {
    phpinfo();
}
