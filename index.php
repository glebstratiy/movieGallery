<?php
require_once './db.php';
require_once './movieController.php';

$totalMovies = countMovies();

// Параметры пагинации
$limit = 9;
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Текущая страница

$totalPages = ceil($totalMovies / $limit);

// Определение смещения для текущей страницы
$offset = ($page - 1) * $limit;

// Получение списка фильмов с учетом пагинации
$movies = getMoviesWithPagination($limit, $offset);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Список фильмов</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/style/index.css">
    <style>
    .movie-card {
        min-width: 300px;
        margin-bottom: 20px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        transition: 0.3s;
    }

    .movie-card:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .movie-card:hover {
        transform: scale(1.02);
    }

    .movie-card img {
        width: 100%;
        height: 450px;
        object-fit: cover;
        border-radius: 4px 4px 0 0;
    }

    .movie-card .card-body {
        padding: 15px;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div>
            <?php include './templates/header.php' ?>

            <div class="container">
                <div class="header-list" style="display: flex; align-items: center">
                    <h1 class="mt-4">Список фильмов</h1>
                    <a href="/templates/addForm.php" class="btn btn-success mt-4">Добавить фильм</a>
                </div>
                <div class="row mt-4">
                    <?php foreach ($movies as $movie): ?>
                    <div class="col-md-4">
                        <div class="card movie-card">
                            <a href="/movie.php?id=<?php echo $movie['id']; ?>">
                                <img src="<?php echo $movie['image']; ?>" alt="Изображение" class=""
                                    style="width: 100%;">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo $movie['name']; ?>
                                </h5>
                                <h5 class="card-date">
                                    <b>Дата выпуска: </b><?php echo $movie['release_date']; ?>
                                </h5>
                                <div class="mt-3 card-btn">
                                    <a href="/templates/editForm.php?id=<?=  $movie['id']; ?>"
                                        class="btn btn-primary">Редактировать</a>
                                    <form method="POST" action="/templates/deleteFilm.php" class="d-inline">
                                        <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                                        <input type="submit" value="Удалить фильм" class="btn btn-danger">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Пагинация -->
                <nav aria-label="Навигация по страницам">
                    <ul class="pagination mt-4 justify-content-center">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">&laquo; Назад</a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">Вперед &raquo;</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>



        <?php include './templates/footer.php' ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>