<?php

const TEST_DB = true;
const TEST_MAIL = true;
const TEST_FILE = true;
const TEST_PHPINFO = true;
const DIVIDER = '<hr style="width: 100%; margin: 2em 0">';

echo "<h1 style='padding-top: 1em'>PHP " . phpversion() . " on Alpine 3.22</h1>";
echo DIVIDER;

////////////////////////////////////////////////////////////
// Проверка подключения к базе данных                     //
////////////////////////////////////////////////////////////
if (TEST_DB) {
    $host = 'db';
    $db = 'testdb';
    $user = 'root';
    $pass = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        echo "<h3 style='color:green'>✅ Подключено к MariaDB!</h3>";
    } catch (PDOException $e) {
        echo "<h3 style='color:red'>❌ Ошибка БД: " . $e->getMessage() . "</h3>";
    }

    echo DIVIDER;
}

////////////////////////////////////////////////////////////
// Отправка тестового письма                              //
////////////////////////////////////////////////////////////
if (TEST_MAIL) {
    $to = 'nobody@example.com';
    $subject = 'the subject';
    $message = 'hello';
    $headers = [
        'From'     => 'webmaster@example.com',
        'Reply-To' => 'webmaster@example.com',
        'X-Mailer' => 'PHP/' . phpversion()
    ];

    if (mail($to, $subject, $message, $headers)) {
        echo "<h3 style='color:blue'>✅ Письмо отправлено Проверь MailPit: <a href='http://localhost:8025' target='_blank'>http://localhost:8025</a></h3>";
    } else {
        echo "<h3 style='color:red'>❌ Не удалось отправить письмо</h3>";
    }

    echo DIVIDER;
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

    echo DIVIDER;
}

////////////////////////////////////////////////////////////
// Вывод информации о PHP                                 //
////////////////////////////////////////////////////////////
if (TEST_PHPINFO) {
    phpinfo();
}
