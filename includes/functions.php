<?php 

function verifySession() {
    if(session_status() === PHP_SESSION_NONE){ //La sesion se inicia una vez por archivo
        session_start();
    }

    if(!isset($_SESSION['user_id'])){ //Si no está logeado, bye
        header('Location: /book_manager/public/login.php');
        exit;
    }
}


function errFinder(array $data, ?array $file = null, bool $isEdit = false): array {
    $err = [];

    if (empty($data['title'])) {
        $err[] = "Título obligatorio";
    }

    if (empty($data['author'])) {
        $err[] = "Autor obligatorio";
    }

    if (empty($data['genre'])) {
        $err[] = "Género obligatorio";
    }

    if ($data['year'] < 1500 || $data['year'] > intval(date('Y'))) {
        $err[] = "El año debe estar entre 1500 y el actual.";
    }

    if (empty($data['description'])) {
        $err[] = "Añade una descripción breve";
    }




    if (!$isEdit || ($file && $file['error'] === 0)) { // El archivo es obligatorio SOLO en create.php, por eso $isEdit debe ser true

        if (!$file || $file['error'] !== 0) {
            if (!$isEdit) {
                $err[] = "No se subió ningún archivo";
            }
        } else {
            $allow = ['pdf', 'epub'];
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, $allow)) {
                $err[] = "El archivo debe ser PDF o EPUB.";
            }

            if ($file['size'] > 10 * 1024 * 1024) {
                $err[] = "Solo se admiten 10MB por archivo";
            }
        }
    }

    return $err;
}


// function detector() {
//     ini_set('display_errors', 1);
//     ini_set('display_startup_errors', 1);
//     error_reporting(E_ALL);
// }



?>