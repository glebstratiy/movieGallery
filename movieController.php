<?php
function getMovieById($movieId) {
    global $pdo;

    $sql = 'SELECT * FROM movie WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$movieId]);

    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    return $movie;
}

function addMovie($name, $image, $releaseDate, $description) {
    global $pdo;

    $sql = "INSERT INTO movie (name, image, release_date, description) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $image, $releaseDate, $description]);

    return true;
}

function editMovie($id, $name, $imagePath, $releaseDate, $description) {
    global $pdo;

    $sql = "UPDATE movie SET name=?, image=?, release_date=?, description=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $imagePath, $releaseDate, $description, $id]);

    return true;
}

function deleteMovie($id) {
    global $pdo;

    $sql = "DELETE FROM movie WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // Проверяем, была ли удалена хотя бы одна строка
    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function getMoviesWithPagination($limit, $offset) {
    global $pdo;

    $sql = "SELECT * FROM movie LIMIT ? OFFSET ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();

    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $movies;
}

function countMovies() {
    global $pdo;

    $sql = "SELECT COUNT(*) FROM movie";
    $stmt = $pdo->query($sql);
    $count = $stmt->fetchColumn();

    return $count;
}



?>
