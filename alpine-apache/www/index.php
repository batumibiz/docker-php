<?php

const TEST_DB = true;
const TEST_MAIL = true;
const TEST_FILE = true;
const TEST_PHPINFO = true;
const DIVIDER = '<hr style="width: 100%; margin: 2em 0">';

echo "<h1 style='padding-top: 1em'>PHP " . phpversion() . " on Alpine 3.22</h1>";
echo DIVIDER;

////////////////////////////////////////////////////////////
// Checking the database connection                       //
////////////////////////////////////////////////////////////
if (TEST_DB) {
    $host = 'mysql';
    $db = 'database';
    $user = 'root';
    $pass = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        echo "<h3 style='color:green'>✅ Connected to MariaDB!</h3>";
    } catch (PDOException $e) {
        echo "<h3 style='color:red'>❌ DB error: " . $e->getMessage() . "</h3>";
    }

    echo DIVIDER;
}

////////////////////////////////////////////////////////////
// Sending a test email                                   //
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
        echo "<h3 style='color:green'>✅ Email sent, check Mailpit: <a href='http://localhost:8025' target='_blank' style='color:blue'>http://localhost:8025</a></h3>";
    } else {
        echo "<h3 style='color:red'>❌ Failed to send email</h3>";
    }

    echo DIVIDER;
}

////////////////////////////////////////////////////////////
// Creating a text file                                   //
////////////////////////////////////////////////////////////
if (TEST_FILE) {
    $filename = 'test.txt';
    $content = 'TEST TEST TEST' . PHP_EOL;

    if (file_put_contents($filename, $content) !== false) {
        echo '<h3 style="color:green">✅ A file "' . $filename . '" containing the contents "' . $content . '" has been created.</h3>';
    } else {
        echo '<h3 style="color:red">❌ Failed to create file ' . $filename . '</h3>';
    }

    echo DIVIDER;
}

////////////////////////////////////////////////////////////
// Displaying information about PHP                       //
////////////////////////////////////////////////////////////
if (TEST_PHPINFO) {
    phpinfo();
}
