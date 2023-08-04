<?php
require_once '../db.php';
require_once '../movieController.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['name'];
    $image = $_FILES['image']['name'];
    $imageExtension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    $releaseDate = $_POST['releaseDate'];
    $description = $_POST['description'];

    // Валидация длины поля "Название фильма"
    if (mb_strlen($title) > 255) {
        $errors['title'] = 'Длина поля "Название фильма" не должна превышать 255 символов.';
    }

    // Валидация длины поля "Описание"
    if (mb_strlen($description) > 255) {
        $errors['description'] = 'Длина поля "Описание" не должна превышать 255 символов.';
    }

    // Если есть ошибки, не выполняем добавление фильма
    if (empty($errors)) {
        // Перемещение загруженного файла в папку назначения
        $targetDirectory = '/uploads/';
        $tempFilePath = $_FILES['image']['tmp_name'];
        $uniqueFilename = uniqid() . '_' . $image;
        $targetFilePath = $_SERVER["DOCUMENT_ROOT"] . $targetDirectory . $uniqueFilename;

        if (move_uploaded_file($tempFilePath, $targetFilePath)) {
            // сохраняем путь к файлу в базе данных
            $imagePath = $targetDirectory . $uniqueFilename;

            if (addMovie($title, $imagePath, $releaseDate, $description)) {
                echo 'Фильм успешно добавлен!';
                header('Location: /index.php');
                exit;
            } else {
                echo 'Ошибка при добавлении фильма.';
            }
        } else {
            $errors['file'] = 'Ошибка при перемещении файла.';
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Добавить фильм</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style/index.css">
</head>

<body>
<div class="wrapper">
    <div>
        <?php include './header.php' ?>

        <div class="container mt-5">
            <h1 class="mt-4">Добавить фильм</h1>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Название фильма:</label>
                    <input type="text"
                           class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" name="name"
                           id="name" required>
                    <?php if (isset($errors['title'])): ?>
                        <div class="invalid-feedback">
                            <?php echo $errors['title']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="image">Изображение (только JPG, JPEG):</label>
                    <input type="file"
                           class="form-control-file <?php echo isset($errors['image']) || isset($errors['file']) ? 'is-invalid' : ''; ?>"
                           name="image" id="image" accept=".jpg, .jpeg" required>
                    <?php if (isset($errors['image']) || isset($errors['file'])): ?>
                        <div class="invalid-feedback">
                            <?php echo isset($errors['image']) ? $errors['image'] : $errors['file']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="releaseDate">Дата выпуска:</label>
                    <input type="date" class="form-control" name="releaseDate" id="releaseDate" required>
                </div>
                <div class="form-group">
                    <label for="description">Описание:</label>
                    <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>"
                              name="description" id="description"></textarea>
                    <?php if (isset($errors['description'])): ?>
                        <div class="invalid-feedback">
                            <?php echo $errors['description']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Добавить фильм</button>
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
