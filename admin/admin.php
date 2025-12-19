<?php 
    require __DIR__ . '/../templates/header.php'; 
    require_once '../includes/app.php';
    session_start();
    verifySession();

    $userId = $_SESSION['user_id'];
    $pdo = Database::getInstance()->getConnection();
    $stmt = $pdo->prepare("SELECT * FROM books WHERE user_id = :user_id");
    $stmt -> execute(['user_id' => $userId]);
    $books = $stmt -> fetchAll(PDO::FETCH_ASSOC);
?>

    <section class="admin-table">
        <h2>Archivos</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Género</th>
                <th>Año</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= htmlspecialchars($book['genre']) ?></td>
                    <td><?= htmlspecialchars($book['year']) ?></td>
                    <td><?= htmlspecialchars($book['description']) ?></td>
                    <td>

                        <a href="editar.php?id=<?= $book['id'] ?>">Editar</a><br>
                        <a href="eliminar.php?id=<?= $book['id'] ?>"
                            onclick="return confirm('¿Seguro que quieres eliminar?');">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <section>
        <a href="/book_manager/admin/crear.php" class="boton-blanco">Añadir archivo</a>
    </section>
<script src="/public/main.js"></script>
</body>
</html>