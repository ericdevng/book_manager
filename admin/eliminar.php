<?php 

//  ESTO ES UN CONTROLADOR // 

require_once '../includes/classes/Book.php';

if(!isset($_GET['id'])){
    die("ID invalido");
}

$id = intval($_GET['id']);
$result = Book::delete($id);

if($result === true){
    header("Location: /book_manager/admin/admin.php");
    exit;
} else {
    echo $result;
}

?>