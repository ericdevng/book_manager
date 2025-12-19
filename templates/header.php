
<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/book_manager/public/main.css">
    <script src="/book_manager/public/main.js"></script>
    <title>Document</title>
</head>
<body class="body-details">
    <header class="header">
        <div class="header-content">
            <div class="nav-bar">
                <h1><a href="/book_manager/public/index.php">Biblioteca PDF</a></h1> <!--CON HIPERVINCULO A INICIO COMO LOGO-->


                <form method="GET" action="/book_manager/public/index.php">
                    <input type="search" name="q" placeholder="Buscar libro..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                </form>


                <div class="mobile-menu" aria-label="Abrir menú" role="button" tabindex="0">
                    <img src="/book_manager/public/images/burguer.png" alt="- - -">
                </div> 

                <nav class="navigation">
                    <a href="/book_manager/public/index.php">Inicio</a>
                    <a href="#">Géneros</a>
                    <a href="#">Nosotros</a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="/book_manager/admin/mi_cuenta.php">Mi cuenta</a></li>
                        <li><a href="/book_manager/admin/admin.php">Mis libros</a></li>
                        <li><a href="/book_manager/public/logout.php">Cerrar sesión</a></li>
                    <?php else: ?>
                        <li><a href="/book_manager/public/login.php">Iniciar sesión</a></li>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>