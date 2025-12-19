<?php
require_once __DIR__ . '/../includes/app.php';

$id = $_GET['id'] ?? null; //Se extrae el ID de GET y si no hay nada, dejalo como null
$book = Book::findId((int)$id); //Pasamos el ID a find para obtener informacion y la dejamos en $book

$path = __DIR__ . '/../uploads/book/' . $book['filename']; //Hacemos la direccion del libro deseado por el ID y le ponemos 'filename' que está en el fetch que hizo book hace una linea

header('Content-Type: application/pdf');                        //CODIGO PARA VISUALIZAR EL PDF TOMADO Y DOXEADO
header('Content-Disposition: inline; filename="archivo.pdf"');

readfile($path); //Mostramos el archivo con la direccion que hicimos en path
exit;
?>