<?php
    include '../conection/conection.php';

    $origen = $_POST['origen'];
    $destino = $_POST['destino'];
    $fecha = $_POST['fecha'];

    $stmt = $conection->prepare("INSERT INTO trayecto (origen, destino, fecha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $origen, $destino, $fecha);

    if ($stmt->execute()) {
        echo "Nuevo trayecto registrado exitoso.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conection->close();
?>
