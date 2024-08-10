<?php
session_start();
include 'conection/conection.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: auth/login.php');
    exit;
}

$id_horario = $_POST['id_horario'];
$num_asiento = $_POST['num_asiento'];
$estado_reserva = $_POST['estado_reserva'];
$id_trayecto = $_POST['id_trayecto'];
$origen = $_POST['origen'];
$destino = $_POST['destino'];
$fecha = $_POST['fecha'];
$hora_salida = $_POST['hora_salida'];

$asientos = explode(', ', $num_asiento);

foreach ($asientos as $asiento) {
    // Verificar si el asiento ya está reservado
    $stmt = $conection->prepare("SELECT * FROM reservacion WHERE id_horario = ? AND asiento_reservado = ?");
    $stmt->bind_param("ii", $id_horario, $asiento);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Si ya está reservado, actualizar el estado a 'pagado'
        $stmt_update = $conection->prepare("UPDATE reservacion SET estado_reserva = ? WHERE id_horario = ? AND asiento_reservado = ?");
        $stmt_update->bind_param("sii", $estado_reserva, $id_horario, $asiento);
        if ($stmt_update->execute()) {
            echo "Asiento $asiento actualizado a pagado.";
        } else {
            echo "Error al actualizar el asiento $asiento: " . $stmt_update->error;
        }
        $stmt_update->close();
    } else {
        // Si no está reservado, insertar una nueva reserva con estado 'pagado'
        $stmt_insert = $conection->prepare("INSERT INTO reservacion (id_horario, asiento_reservado, estado_reserva) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("iis", $id_horario, $asiento, $estado_reserva);
        if ($stmt_insert->execute()) {
            echo "Asiento $asiento reservado y marcado como pagado.";
        } else {
            echo "Error al reservar el asiento $asiento: " . $stmt_insert->error;
        }
        $stmt_insert->close();
    }
    $stmt->close();
}

header("Location: admin_apartar_boleto.php?id_trayecto=$id_trayecto&origen=$origen&destino=$destino&fecha=$fecha&hora=$hora_salida");
exit();
?>
