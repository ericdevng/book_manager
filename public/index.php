
<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


require_once __DIR__ . '/../includes/app.php';
require_once __DIR__ . '/../includes/classes/Book.php';
session_start();


$q = trim($_GET['q'] ?? ''); //q toma el valor del buscardor, si no hay nada entonces que sea una cadena vacia

if ($q !== '') { //Si el buscador no está en blanco (si no es una cadena vacia)
    $books = Book::search($q); //entonces busca
    $totalPaginas = 1;// y pon todo en una pagina
    $page = 1;
} else { //Si no pues entonces lista todo como si no hubieras buscado nada y paginalo de 5 en 5

    $limit = 5;
    $page = max(1, (int)($_GET['page'] ?? 1));
    $offset = ($page - 1) * $limit;

    $pdo = Database::getInstance()->getConnection();
    $stmt = $pdo->prepare("
        SELECT b.id, b.title, b.description, b.author, b.genre, b.year,
               LEFT(b.description, 150) AS short_desc,
               u.username, b.uploaded_at
        FROM books b
        JOIN users u ON b.user_id = u.id
        ORDER BY b.uploaded_at DESC
        LIMIT :limit OFFSET :offset
    ");

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
    $totalPaginas = ceil($total / $limit);
}


require __DIR__ . '/../templates/header.php'; 

?>

<body>
    <main>
        <h1>Libros Recientes</h1>

        <section class='books-grid'>
            <?php foreach ($books as $b): ?>
                <article class="book-card">
                    <h2><?= htmlspecialchars($b['title']) ?></h2>

                    <p><strong>Autor:</strong> <?= htmlspecialchars($b['author']) ?></p>
                    <p><strong>Género:</strong> <?= htmlspecialchars($b['genre']) ?></p>
                    <p><strong>Año:</strong> <?= htmlspecialchars($b['year']) ?></p>
                    <p><strong>Subido por:</strong> <?= htmlspecialchars($b['username']) ?></p>

                    <p><?= htmlspecialchars($b['short_desc']) ?>...</p>

                    <a class="boton-blanco" href="libro.php?id=<?= $b['id'] ?>">
                        Ver detalles
                    </a>
                </article>
            <?php endforeach; ?>
        </section>

        <div class="paginacion">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <?php if ($i == $page): ?>
                    <strong><?php echo $i; ?></strong>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalPaginas): ?>
                <a href="?page=<?php echo $page + 1; ?>">Siguiente</a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>


