<?php
require __DIR__ . '/../templates/header.php'; 
require_once __DIR__ . '/../includes/app.php';


session_start();


if (isset($_SESSION['user_id'])) { //Si hay sesion activa, manda a index
    header('Location: /book_manager/public/index.php');
    exit;
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = $_POST['password'] ?? '';
    $err = [];

    if (!empty($email) && !empty($pass)) {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]); 
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($pass, $user['password_hash'])) {
                //session_start();
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: /book_manager/admin/admin.php');
                exit;
            } else {
                $err[] = "Contraseña incorrecta.";
            }
        } else {
            $err[] = "No existe una cuenta con ese correo.";
        }
    } else {
        $err[] = "El e-mail y la contraseña son obligatorios.";
    }

    if (!empty($err)) {
        foreach ($err as $e) {
            echo "<p style='color:red;'>$e</p>";
        }
    }
}

/****************************************************/


?>
<body class="body-details">
    <div class="login-container">
        <form class="formulario" action="" method="POST">
            <legend>Iniciar Sesión</legend>
            
            <?php if(!empty($err)): ?>
                <div class="errores">
                    <?php foreach($err as $e): ?>
                        <p><?= htmlspecialchars($e) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <label for="email">E-mail: </label>
            <input type="email" name="email" placeholder="Tu e-mail" id="email">
            <label for="password">Contraseña: </label>
            <input type="password" name="password" placeholder="Tu contraseña" id="password" required>

            <div>
                <button type="submit" class="boton-blanco">Iniciar sesión</button>
                <a href="/book_manager/public/registrar.php" class="boton-blanco">Registrarme</a>
            </div>

            <p>¿Olvidaste tu contraseña?</p>
        </form>
    </div>

</body>
</html>