<?php 

require __DIR__ . '/../templates/header.php'; 
require_once '../includes/app.php';
session_start();
verifySession();


if(isset($_GET['ok']) && $_GET['ok'] == 1){     //Condicion para msj de exito
    echo "<p style='color: green;'>Libro subido con éxito.</p>";
}


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $year = intval($_POST['year'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $userId = $_SESSION['user_id'];
    
    $data = [
        'user_id' => $userId,
        'title' => $title,
        'author' => $author,
        'genre' => $genre,
        'year' => $year,
        'description' => $description,
    ];

    //Validaciones
    $err = errFinder($data, $_FILES['book_file'], false);

    
if (empty($err)) {

    $file = $_FILES['book_file'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $newFileName = uniqid('book_') . '.' . $extension;
    $upload = __DIR__ . '/../uploads/book/';
    $fullPath = $upload . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $fullPath)) {

        $data['filename'] = $newFileName;
        Book::create($data);

        header("Location: crear.php?ok=1");
        exit;
    }
}

    if (!empty($err)) {
        foreach ($err as $e) {
            echo "<p style='color:red;'>$e</p>";
        }
    }
}

?>

<body>
    <main>
        <h1>Añadir libro</h1>
        <section class="create-container">
            <form method="POST" action="" enctype="multipart/form-data">
                <fieldset class="create-form">
                    <legend>Información general</legend>
                    <label for="title">Titulo: </label>
                    <input type="text" id="title" name="title" placeholder="Título de la obra">

                    <label for="author">Autor: </label>
                    <input type="text" id="author" name="author" placeholder="Autor de la obra">

                    <label for="genre">Género: </label>
                    <input type="text" id="genre" name="genre" placeholder="Género literario">

                    <label for="year">Fecha de publicación: </label>
                    <input type="number" name="year" id="year" placeholder="Año de publicación" min="1500" max="2025">

                    <label>Archivo (PDF o EPUB):</label>
                    <input type="file" name="book_file" accept=".pdf,.epub">

                    <label for="description">Descripción: </label>
                    <textarea id="description" name="description"></textarea>

                    <button type="submit" class="boton-blanco">Crear</button>
                </fieldset>
            </form>
        </section>

        <section>
            <h2>Añade: </h2>
        </section>
    </main>
</body>
</html>