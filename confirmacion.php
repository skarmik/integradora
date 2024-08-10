<?php
    session_start(); 
    include 'conection/conection.php';

    header('Content-Type: application/json');
    $postData = print_r($_POST, true);
    file_put_contents("debug.txt", $postData); 

    // Asegúrate de recibir id_trayecto en la solicitud
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['num_asiento'], $_POST['estado_reserva'], $_POST['id_usuario'], $_POST['id_horario'], $_POST['id_trayecto'])) {

        $num_asiento = $_POST['num_asiento'];
        $estado_reserva = $_POST['estado_reserva'];
        $id_usuario = $_POST['id_usuario'];;
        $id_trayecto = $_POST['id_trayecto'];

        if ($conection->connect_error) {
            echo json_encode(['success' => false, 'error' => 'Database connection failed']);
            exit;
        }

        // Buscar el id_horario basado en el id_trayecto
        $stmt = $conection->prepare("SELECT id_horario FROM horario WHERE id_trayecto = ?");
        
        if (!$stmt) {
            echo json_encode(['success' => false, 'error' => 'Error preparing query']);
            exit;
        }
        
        $stmt->bind_param("i", $id_trayecto);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $id_horario = $row['id_horario'];
            $stmt->close();

            $fecha_reservacion = date('Y-m-d H:i:s');

            $checar = $conection->prepare("SELECT * from reservacion WHERE asiento_reservado = ?");
            if (!$checar) {
                echo json_encode(['success' => false, 'error' => 'Error preparing query']);
                exit;
            }
            $checar->bind_param("i", $num_asiento);
            $checar->execute();
            $result = $checar->get_result();
            $row = $result->fetch_assoc();
            if ($row) {
                echo json_encode(['success' => false, 'error' => 'Ya esta ocupado']);
                exit;
            }
            $stmt = $conection->prepare("INSERT INTO reservacion (asiento_reservado, estado_reserva, id_usuario, id_horario) VALUES (?, 'ocupado', ?, ?)");
            if (!$stmt) {
                echo json_encode(['success' => false, 'error' => 'Error preparing query']);
                exit;
            }

            foreach (explode(',', $num_asiento) as $asiento) {
                $stmt->bind_param("iii", $asiento, $id_usuario, $id_horario);
                $stmt->execute();
            }
            $stmt->close();

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Schedule not found for the specified id_trayecto']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request or missing fields']);
    }
?>
