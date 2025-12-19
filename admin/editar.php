<?php
require __DIR__ . '/../templates/header.php'; 
require_once '../includes/app.php';
require_once '../includes/classes/Book.php';

session_start();
verifySession();



if(!isset($_GET['id'])){
    die("ID no valido");
}

$id = intval($_GET['id']);
$book = Book::findId($id);

if(!$book){
    die("Libro no encontrado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $data = [
        'title' => $_POST['title'],
        'author' => $_POST['author'],
        'genre' => $_POST['genre'],
        'year' => $_POST['year'],
        'description' => $_POST['description'],
    ];

    $err = errFinder($data, $_FILES['book_file'] ?? null, true);

    if(empty($err)){
        Book::update($id, $data, $_FILES['book_file']);
        header("Location: admin.php?updated=1");
        exit;
    }

}

?>


<body>
    <main>
        <h1>Editar archivo.</h1>
        <section>
            <form method="POST" enctype="multipart/form-data">
                <fieldset class="create-form">
                    <legend>Información general</legend>
                    <label for="title">Titulo: </label>
                    <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>">

                    <label for="author">Autor: </label>
                    <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>">

                    <label for="genre">Género: </label>
                    <input type="text" name="genre" value="<?= htmlspecialchars($book['genre']) ?>">

                    <label for="year">Fecha de publicación: </label>
                    <input type="number" name="year" value="<?= htmlspecialchars($book['year']) ?>">

                    <label>Archivo (PDF o EPUB) Opcional:</label>
                    <input type="file" name="book_file" accept=".pdf,.epub">

                    <label for="description">Descripción: </label>
                    <textarea name="description"><?= htmlspecialchars($book['description']) ?></textarea>

                    <button type="submit" class="boton-blanco">Guardar cambios</button>
                </fieldset>
            </form>
        </section>
    </main>
</body>

