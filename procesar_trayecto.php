<?php
    include 'conection/conection.php';

     $origen = $_POST['origen'];
     $destino = $_POST['destino'];
     $fecha = $_POST['fecha'];

    $sql = "SELECT t.id_trayecto, t.origen, t.destino, t.fecha, h.hora_salida, h.hora_llegada
            FROM trayecto t
            JOIN horario h ON t.id_trayecto = h.id_trayecto
            WHERE t.origen = ? AND t.destino = ? AND h.fecha_salida = ?";
    $stmt = $conection->prepare($sql);
    $stmt -> bind_param("sss", $origen, $destino, $fecha);
    $stmt -> execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: resultado_busqueda.php?origen=$origen&destino=$destino&fecha=$fecha");
        exit();
    } else {
        // echo "origen: $origen, destino: $destino, fecha: $fecha";
        echo "<script>
                alert('No se encontraron resultados para su búsqueda.');
                window.location.href = 'index.php';
              </script>";
        exit;
    }

    $conection -> close();
?>
