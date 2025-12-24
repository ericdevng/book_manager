<?php 
require_once __DIR__ . '/../app.php';


class User {
    public static function findId(int $id){
        $pdo = Database::getInstance()->getConnection();

        $stmt = $pdo -> prepare("SELECT * FROM users WHERE id = :id LIMIT 1"); 
        $stmt -> execute(['id' => $id]);

        return $stmt -> fetch(PDO::FETCH_ASSOC);
    }


    public static function updateMail(int $id, string $email){
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo -> prepare("SELECT * FROM users WHERE id = :id");
        $stmt -> execute(['id' => $id]);
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Correo electrónico inválido.";
        }

        if($_SESSION['user_id'] != $user['id']){
            return "No tienes permiso para realizar ésta acción";
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
        $stmt->execute([
            'email' => $email,
            'id' => $id
        ]);

        if ($stmt->fetch()) {
            return "Este correo ya está en uso.";
        }

        $stmt = $pdo -> prepare("UPDATE users SET email = :email WHERE id = :id");
        $stmt -> execute(['email' => $email, 'id' => $id]);
        return true;
    }

    public static function updateUser(int $id, string $username){
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo -> prepare("SELECT * FROM users WHERE id = :id");
        $stmt -> execute(['id' => $id]);
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);

        if(!$user){
            return "El nombre de usuario no existe.";
        }

        if($_SESSION['user_id'] != $user['id']){
            return "No tienes permiso para realizar ésta acción";
        }

        $stmt = $pdo->prepare("UPDATE users SET username = :username WHERE id = :id");
        $stmt->execute([
            'username' => $username,
            'id' => $id
        ]);
        return true;
    }


    public static function updatePass(int $id, string $current, string $newPass, string $confirmPass) {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return "Usuario no encontrado.";
        }

        if (!password_verify($current, $user['password_hash'])) {
            return "La contraseña actual es incorrecta.";
        }

        if ($newPass !== $confirmPass) {
            return "Las nuevas contraseñas no coinciden.";
        }

        if (strlen($newPass) < 6) {
            return "La contraseña debe tener al menos 6 caracteres.";
        }

        $newPassHash = password_hash($newPass, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");

        $stmt->execute(['hash' => $newPassHash, 'id' => $id]);

        return true;
    }


    public static function deleteAccount(int $userId, string $pass){
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            return "Usuario no encontrado.";
        }

        if(!password_verify($pass, $user['password_hash'])){
            return "Contraseña incorrecta.";
        }

        try{
            $pdo -> beginTransaction();
            Book::deleteByUser($userId);

            $stmt = $pdo -> prepare("DELETE FROM users WHERE id = :user_id");
            $stmt -> execute(['user_id' => $userId]);

            $pdo -> commit();
            return true;
        }catch (Exception $e){
            $pdo -> rollBack();
            return $e -> getMessage();
        }
    }

    public static function requestPassReset(string $email){
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo -> prepare("SELECT id FROM users WHERE email = :email");
        $stmt -> execute(['email' => $email]);
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);

        if(!$user){
            return true; //El usuario no debe saber que paso (seguridad temporal)
        }

        $token = bin2hex(random_bytes(32)); //TOKEN DESARROLLO

        $expires = date('Y-m-d H:i:s', time() + 1800);

        $stmt = $pdo -> prepare("UPDATE users SET reset_token = :token, reset_token_expires = :expires WHERE id = :id");

        $stmt -> execute(['token' => $token, 'expires' => $expires, 'id' => $user['id']]);




        return $token; //TOKEN DESARROLLO

    }

    public static function resetPassword(string $token, string $newPass){
        $pdo = Database::getInstance()->getConnection();

        $stmt = $pdo -> prepare("SELECT id, reset_token_expires FROM users WHERE reset_token = :token");
        $stmt -> execute(['token' => $token]);
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);

        if(!$user){
            return "Token invalido";
        }

        if(strtotime($user['reset_token_expires']) < time()){
            return "El token ha expirado";
        }

        $passHash = password_hash($newPass, PASSWORD_DEFAULT);

        $stmt = $pdo -> prepare("UPDATE users SET password_hash = :password, reset_token = NULL, reset_token_expires = NULL WHERE id = :id");
        $stmt -> execute(['password' => $passHash, 'id' => $user['id']]);
        return true;
    }


    
}



?>