<?php
require __DIR__ . '/../templates/header.php'; 
require_once '../includes/app.php';
require_once '../includes/classes/Book.php';
require_once '../includes/classes/User.php';

session_start();
verifySession();

if(!isset($_SESSION['user_id'])){
        die("Error, no hay ninguna sesion permitida para esta acción");
}

$id = intval($_SESSION['user_id']);
$user = User::findId($id); 

if(!$user){
    die("No tienes permiso para realizar ésta acción");
}


$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'update_email':
            $email = $_POST['email'];
            $result = User::updateMail($id, $email);

            if ($result === true) {
                // volver a cargar el usuario actualizado
                $user = User::findId($id);
                $message = "Correo actualizado correctamente.";
            } else {
                // mostrar error
                $message = $result;
            }
            break;


        case 'update_username':
            $username = $_POST['username'];
            $result = User::updateUser($id, $username);
            
            if($result === true) {
                $user = User::findId($id); //Volver a cargar el usuario actualizado
                $message = "Nombre de usuario actualizado correctamente.";
            } else {
                $message = $result;
            }
            break;

        case 'update_password':
            if (empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
                $message = "Todos los campos son obligatorios.";
                break;
            }

            $result = User::updatePass($id, $_POST['current_password'], $_POST['new_password'], $_POST['confirm_password']);

            $message = $result === true ? "Contraseña actualizada." : $result;
            break;

        // case 'delete_account':
        //     $userId = $_SESSION['user_id'];
        //     $result = User::deleteAccount($id, $pass);


            



        //     break;

    }
}

?>

<body>
    <main>
        <h1>Configuracion de cuenta.</h1>
        <section>
            <h2>Cambiar nombre de usuario</h2>
            <form method="POST" action="">
                <fieldset>
                    <h2>Nombre de usuario actual: <?= htmlspecialchars($user['username']) ?> </h2>
                    <label for="username">Nombre de usuario nuevo: </label>
                    <input type="hidden" name="action" value="update_username" >
                    <input type="text" name="username" value="">
                    <button type="submit" class="">Cambiar nombre</button>
                </fieldset>
            </form>
        </section>
        <section>
            <h2>Cambiar correo electronico</h2>
            <form method="POST" action="">
                <fieldset>
                    <h2>Correo electronico actual: <?= htmlspecialchars($user['email']) ?> </h2>
                    <label for="email">Correo electronico nuevo: </label>
                    <input type="hidden" name="action" value="update_email" >
                    <input type="email" name="email" value="">
                    <button type="submit" class="">Cambiar e-mail</button>
                </fieldset>           
            </form>
        </section>
        <section>
            <h2>Cambiar contraseña</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_password"
                <fieldset>
                    <label for="current_password">Contraseña actual:</label>
                    <input type="password" name="current_password" id="current_password" placeholder="Tu contraseña actual" required>

                    <label for="new_password">Contraseña nueva:</label>
                    <input type="password" name="new_password" id="new_password" placeholder="Escribir contraseña nueva" required>

                    <label for="confirm_password">Confirmar nueva contraseña:</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmar nueva contraseña" required>

                    <button type="submit">Cambiar contraseña</button>
                </fieldset>
            </form>
        </section>


        <section>
            <h2>Eliminar cuenta</h2>
            <form method="POST" action="">
                <fieldset>
                    <input type="hidden" name="action" value="delete_account">
                    <label>Escribe tu contraseña actual para confirmar: </label>
                    <input type="password" name="password" required>
                    <button type="submit" onclick="return confirm('¿Estás seguro? esta acción es irreversible')" class="danger">ELIMINAR CUENTA</button>
                </fieldset>
            </form>
            <p>Al eliminar tu cuenta, debes saber que automaticamente se borraran todos los registros que has realizado, incluyendo los archivos que has subido; todo de forma PERMANENTE.</p>
        </section>

        <?php if ($message): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </main>
</body>