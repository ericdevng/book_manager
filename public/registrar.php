
<?php 

require_once '../includes/app.php';
require_once '../templates/header.php';
$pdo = Database::getInstance()->getConnection();

$err = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? ''); 
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';



    if(!$username){
        $err[] = "El nombre de usuario es obligatorio";
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $err[] = "El e-mail no es valido";
    }
    if(strlen($password) < 6){
        $err[] = "La contraseña debe tener al menos 6 caracteres";
    }
    if($password !== $confirm){
        $err[] = "Las contraseñas no coinciden";
    }

    if(empty($err)){
        try {
            $checkmail = $pdo -> prepare("SELECT id FROM users WHERE email = :email");
            $checkmail -> execute(['email' => $email]);
            $mailExists = $checkmail -> fetch();

            $checkuser = $pdo -> prepare("SELECT id FROM users WHERE username = :username");
            $checkuser -> execute(['username' => $username]);
            $userExists = $checkuser -> fetch();

            if ($mailExists) {
                $err[] = "El correo ya está registrado.";
            }

            if ($userExists) {
                $err[] = "El nombre de usuario ya está en uso, prueba otro.";
            }


            if (empty($err)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
                
                $stmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password_hash' => $password_hash
                ]);

                echo "<p style='color:green;'>Registro completado con éxito.</p>";
            }

        } catch (PDOException $e) {
            $err[] = "Error de registro: " . $e -> getMessage();
        }
    }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/book_manager/public/main.css">
    <title>Iniciar sesion</title>
</head>


<body>
    <form class="formulario" action="" method="POST">
        <legend>Datos para registro</legend>

        <?php if (!empty($err)): ?>
            <div class="errores">
                <?php foreach ($err as $e): ?>
                    <p><?= htmlspecialchars($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label for="email">E-mail:</label>
        <input type="email" name="email" placeholder="Tu e-mail" id="email" required>

        <label for="username">Nombre de usuario:</label>
        <input type="text" id="username" name="username" placeholder="Tu nombre" required>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" placeholder="Escribir contraseña" id="password" required>

        <label for="confirm_password">Confirmar contraseña:</label>
        <input type="password" name="confirm_password" placeholder="Confirmar contraseña" id="confirm_password" required>

        <div>
            <button type="submit" class="boton-blanco">Registrarme</button>
        </div>
    </form>
</body>
</html>