<?php 
require_once __DIR__ . '/../includes/app.php';
session_start();



if(!isset($_GET['id'])){
    die("ID no valido");
}

$id = intval($_GET['id']);
$book = Book::findId($id);

if(!$book){
    die("Libro no encontrado");
}

require __DIR__ . '/../templates/header.php'; 

?>

<body>
    <main>
        <h1><?= htmlspecialchars($book['title']) ?></h1>
        <section>
            <iframe src="ver_pdf.php?id=<?= $book['id'] ?>" width="50%" height="500"></iframe> <!-- VISOR DE PDF -->
        </section>

        <section>
            <p><strong>Autor:</strong> <?= htmlspecialchars($book['author']) ?></p>
            <p><strong>Género:</strong> <?= htmlspecialchars($book['genre']) ?></p>
            <p><strong>Año:</strong> <?= htmlspecialchars($book['year']) ?></p>
            <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
        </section>





        <section>
            <form action="/book_manager/admin/download.php" method="post"> <!-- BOTON DE DESCAGA -->
                <input type="hidden" name="id" value="<?= $book['id'] ?>">
                <button>Descargar</button>
            </form>
        </section>
    </main>
</body>
