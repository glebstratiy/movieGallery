<?php
require_once './db.php';
require_once './movieController.php';

$movieId = $_GET['id'];

// Получение информации о фильме по его идентификатору
$movie = getMovieById($movieId);

if (!$movie) {
    echo 'Фильм не найден.';
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        <?php echo $movie['name']; ?>
    </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/style/index.css">
    <style>
    .poster {
        width: 30%;
        height: auto;
        float: left;
        margin-right: 20px;
    }

    .description {
        float: left;
    }

    .img-fluid {
        border-radius: 10px;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div>
            <?php include './templates/header.php' ?>

            <div class="container py-5">
                <a href="/index.php" class="btn btn-primary mt-3">Назад к списку фильмов</a>
                <h1 class="mt-4">
                    <?php echo $movie['name']; ?>
                </h1>
                <div class="poster">
                    <img src="<?php echo $movie['image']; ?>" alt="Изображение" class="img-fluid">
                </div>
                <div class="description">
                    <p><b>Дата выпуска:</b>
                        <?php echo $movie['release_date']; ?>
                    </p>
                    <p><b>Описание:</b>
                        <?php echo $movie['description']; ?>
                    </p>
                </div>
                <div class="clearfix"></div>

            </div>
        </div>
        <?php include './templates/footer.php' ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>