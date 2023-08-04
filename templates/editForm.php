<?php
require_once '../db.php';
require_once '../movieController.php';

$movieId = $_GET['id'];

$movie = getMovieById($movieId);

if (!$movie) {
    echo 'Фильм не найден.';
    exit;
}

$errors = []; // Массив для хранения ошибок

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['name'];
    $image = $_FILES['image']['name'];
    $releaseDate = $_POST['releaseDate'];
    $description = $_POST['description'];

    // Перемещение загруженного файла в папку назначения, если выбран новый файл
    if (!empty($image)) {
        $targetDirectory = '/uploads/';
        $tempFilePath = $_FILES['image']['tmp_name'];
        $uniqueFilename = uniqid() . '_' . $image;
        $targetFilePath = $targetDirectory . $uniqueFilename;

        if (move_uploaded_file($tempFilePath, $_SERVER["DOCUMENT_ROOT"] . $targetFilePath)) {
            // обновляем путь к файлу в базе данных
            $imagePath = $targetFilePath;
        } else {
            echo 'Ошибка при перемещении файла.';
            exit;
        }
    } else {
        // Используем существующий путь к файлу, если новый файл не выбран
        $imagePath = $movie['image'];
    }

    // Валидация длины поля "Название фильма"
    if (mb_strlen($title) > 255) {
        $errors['title'] = 'Длина поля "Название фильма" не должна превышать 255 символов.';
    }

    // Валидация длины поля "Описание"
    if (mb_strlen($description) > 255) {
        $errors['description'] = 'Длина поля "Описание" не должна превышать 255 символов.';
    }

    // Если есть ошибки, не выполняем обновление фильма
    if (empty($errors)) {
        if (editMovie($movieId, $title, $imagePath, $releaseDate, $description)) {
            echo 'Фильм успешно обновлен!';
            header('Location: /index.php');
            exit;
        } else {
            echo 'Ошибка при обновлении фильма.';
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Редактировать фильм</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style/index.css">
</head>

<body>
    <div class="wrapper">
        <div>
            <?php include './header.php' ?>

            <div class="container">
                <h1 class="mt-4">Редактировать фильм</h1>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Название фильма:</label>
                        <input type="text"
                            class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" name="name"
                            id="name" value="<?php echo $movie['name']; ?>">
                        <?php if (isset($errors['title'])): ?>
                        <div class="invalid-feedback">
                            <?php echo $errors['title']; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="image">Изображение:</label>
                        <input type="file"
                            class="form-control-file <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>"
                            name="image" id="image" accept=".jpg, .jpeg">
                        <?php if (isset($errors['image'])): ?>
                        <div class="invalid-feedback">
                            <?php echo $errors['image']; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="releaseDate">Дата выпуска:</label>
                        <input type="date" class="form-control" name="releaseDate" id="releaseDate"
                            value="<?php echo $movie['release_date']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Описание:</label>
                        <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>"
                            name="description" id="description" rows="4"><?php echo $movie['description']; ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                        <div class="invalid-feedback">
                            <?php echo $errors['description']; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    <a href="/"><button type="button" class="btn btn-primary">На главную</button></a>
                </form>
            </div>
        </div>
        <?php include './footer.php' ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>