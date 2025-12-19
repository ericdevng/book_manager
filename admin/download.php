<?php 

require_once '../includes/app.php';
require_once '../includes/classes/Book.php';



if (!isset($_POST['id'])) {
    die('No llegó el ID');
}

$id = intval($_POST['id']);
$book = Book::findId($id);

if (!$book) {
    die('Libro no encontrado');
}


$path = __DIR__ . '/../uploads/book/' . $book['filename'];

if (!file_exists($path)) {
    die('Archivo no existe: ' . $path);
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $book['filename'] . '"');
header('Content-Length: ' . filesize($path));

readfile($path);
exit;



?>