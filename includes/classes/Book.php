<?php
require_once __DIR__ . '/../app.php';

class Book {
    public static function findId(int $id){
        $pdo = Database::getInstance()->getConnection();

        $stmt = $pdo -> prepare("SELECT * FROM books WHERE id = :id LIMIT 1"); 
        $stmt -> execute(['id' => $id]);

        return $stmt -> fetch(PDO::FETCH_ASSOC);
    }

    public static function update(int $id, array $data, ?array $file = null){
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo -> prepare("SELECT * FROM books WHERE id = :id");
        $stmt -> execute(['id' => $id]);
        $book = $stmt -> fetch(PDO::FETCH_ASSOC);

        if(!$book){
            return  "El libro no existe.";
        }

        if($_SESSION['user_id'] != $book['user_id']){
            return "No cuentas con permiso para editar este archivo";
        }

        $filename = $book['filename'];

        if($file && $file['error'] === 0){

            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $newName = uniqid('book_') . '.' . $extension;

            $path = __DIR__ . '/../../uploads/book/' . $newName;
            move_uploaded_file($file['tmp_name'], $path);

            // Borrar archivo viejo
            $oldPath = __DIR__ . '/../../uploads/book/' . $filename;
            if(file_exists($oldPath)){
                unlink($oldPath);
            }

            $filename = $newName;
        }

        

        $stmt = $pdo -> prepare("UPDATE books SET title = :title, author = :author, genre = :genre, year = :year, description = :description, filename = :filename WHERE id = :id");
        $stmt->execute([
            'title' => $data['title'],
            'author' => $data['author'],
            'genre' => $data['genre'],
            'year' => $data['year'],
            'description' => $data['description'],
            'filename' => $filename,
            'id' => $id
        ]);

        return true;
    }


    public static function create(array $data) {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO books 
            (user_id, title, author, genre, year, description, filename)
            VALUES 
            (:user_id, :title, :author, :genre, :year, :description, :filename)
        ");

        return $stmt->execute([ 
            'user_id'     => $data['user_id'],
            'title'       => $data['title'],
            'author'      => $data['author'],
            'genre'       => $data['genre'],
            'year'        => $data['year'],
            'description' => $data['description'],
            'filename'    => $data['filename']
        ]);
    }


    public static function delete($id){
        $pdo = Database::getInstance()->getConnection();

        // Obtener filename y user_id
        $stmt = $pdo->prepare("SELECT filename, user_id FROM books WHERE id = ?");
        $stmt->execute([$id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$book) {
            return "El libro no existe.";
        }

        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $book['user_id']) {
            return "No tienes permiso de eliminar este libro";
        }

        // Borrar archivo
        $filePath = __DIR__ . '/../../uploads/book/' . $book['filename'];
        if (!empty($book['filename']) && file_exists($filePath)) {
            unlink($filePath);
        }

        // Borrar de la BD
        $delete = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $delete->execute([$id]);

        return true;
    }

    public static function search(string $q): array {
        $pdo = Database::getInstance()->getConnection();

        $stmt = $pdo -> prepare("SELECT * FROM books WHERE title LIKE :q OR author LIKE :q OR genre LIKE :q OR description LIKE :q ORDER BY uploaded_at DESC");
        $stmt -> execute(['q' => '%' . $q . '%']);
        return $stmt -> fetchAll(PDO::FETCH_ASSOC);
    } 


    public static function all(){
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo -> query("SELECT * FROM books ORDER BY id DESC");
        return $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }

    // public static function deleteByUser(int $userId){
    //     $pdo = Database::getInstance()->getConnection();
    //     $stmt = $pdo -> prepare("SELECT * FROM books WHERE ")
    // }


}


?>