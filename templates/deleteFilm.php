<?php
require_once '../db.php';
require_once '../movieController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieId = $_POST['movie_id'];

    if (deleteMovie($movieId)) {
        echo 'Фильм успешно удален!';
    } else {
        echo 'Ошибка при удалении фильма.';
    }
}

header('Location: /index.php');

?>
