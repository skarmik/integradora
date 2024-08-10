<?php
    include '../conection/conection.php';

    $fecha_salida = $_POST['fecha_salida'];
    $hora_salida = $_POST['hora_salida'];
    $id_trayecto = $_POST['id_trayecto'];
    $id_autobus = $_POST['id_autobus'];
    $hora_llegada = $_POST['hora_llegada'];

    $fecha_actual = date("Y-m-d");

    if ($fecha_salida < $fecha_actual) {
        echo "Error: La fecha seleccionada no es válida";
    } else {
        $sql = "INSERT INTO horario (fecha_salida, hora_salida, id_trayecto, id_autobus, hora_llegada) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conection->prepare($sql);
        $stmt->bind_param("ssiis", $fecha_salida, $hora_salida, $id_trayecto, $id_autobus, $hora_llegada);

        if ($stmt->execute()) {
            echo "Nuevo registro exitoso";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conection->close();
?>
