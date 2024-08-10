<?php
    include '../conection/conection.php';

    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $titulo = $_POST['titulo'];
        $resumen = $_POST['resumen'];
        $contenido = $_POST['contenido'];
        $fecha = $_POST['fecha'];
        $imagen = $_FILES['imagen']['name'];
        $imagen = preg_replace("/[^a-zA-Z0-9.\-_]/", '_', $imagen);
        $target_file = $target_dir . basename($imagen);
    
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            $path_to_save = "uploads/" . basename($imagen);
            $sql = "INSERT INTO noticias (titulo, resumen, contenido, fecha, imagen) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conection->prepare($sql);
            $stmt->bind_param("sssss", $titulo, $resumen, $contenido, $fecha, $path_to_save);
    
            if ($stmt->execute()) {
                echo "Nueva noticia agregada, exitoso.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error al subir la imagen.";
        }
        $conection->close();
    }
?>
