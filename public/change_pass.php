<?php
require_once '../includes/app.php';
require_once '../templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
        var_dump($token);
    exit;

    if ($password !== $confirm) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // var_dump($_POST);
        // die();
        $result = User::resetPassword($token, $password);

        if ($result === true) {
            $success = "Contraseña actualizada. Ya puedes iniciar sesión.";
        } else {
            $error = $result;
        }
    }


    User::resetPassword($token, $confirm);
    

}

?>



<form method="POST">
    <label>Token de recuperación</label>
    <input type="text" name="token" required>

    <label>Nueva contraseña</label>
    <input type="password" name="password" required>

    <label>Confirmar contraseña</label>
    <input type="password" name="confirm_password" required>

    <button type="submit">Cambiar contraseña</button>
</form>


<?php if (!empty($error)): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color:green"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>