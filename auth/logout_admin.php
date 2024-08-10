<?php
session_start();
session_unset();
session_destroy();
header("Location: ../index.php"); // Redirigir a la página de inicio después de cerrar sesión
exit;
?>
