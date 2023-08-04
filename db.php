<?php

$host = 'localhost';
$dbname = 'test_gleb_stratiy';
$username = 'root';
$password = 'goliylox123!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;", "$username", "$password");
} catch (PDOException $exception) {
    echo $exception;
}




