<?php
    include '../conection/conection.php';

    $numero_autobus = $_POST['numero_autobus'];
    $asientos = $_POST['asientos'];
    $modelo_autobus = $_POST['modelo_autobus'];

    $sql_check = "SELECT * FROM autobus WHERE numero_autobus = ?";
    $stmt_check = $conection->prepare($sql_check);
    $stmt_check->bind_param("s", $numero_autobus);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $stmt_check->close();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('El número de autobús ya existe. Intente con otro número.');
              </script>";
    } else {
        $sql_insert = "INSERT INTO autobus (numero_autobus, asientos, modelo_autobus) VALUES (?, ?, ?)";
        $stmt_insert = $conection->prepare($sql_insert);
        $stmt_insert->bind_param("sis", $numero_autobus, $asientos, $modelo_autobus);

        if ($stmt_insert->execute()) {
            echo "Registro exitoso";
            exit;
        } else {
            echo "No se pudo realizar la acción";
        }
        $stmt_insert->close();
    }

    $conection->close();
?>
