<?php
include 'conection/conection.php';

$id = $_GET['id'];

$sql = "SELECT titulo, resumen, contenido, fecha, imagen FROM noticias WHERE id = ?";
$stmt = $conection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$noticia = null; // Asegura que la variable está definida.

if ($result->num_rows > 0) {
    $noticia = $result->fetch_assoc();
} else {
    echo "<script>
            alert('Noticia no encontrada.');
            window.location.href = 'index.php';
          </script>";
    exit;
}

$conection->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head_meta.php'; ?>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1 {
            font-size: 24px; /* Tamaño del título ajustado */
            font-weight: bold; /* Negrita para destacar el título */
            text-align: center; /* Centra el título horizontalmente */
        }
        .bg-cards {
            background-color: #CCCCFF;
            padding: 20px;
            border-radius: 20px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 95%;
            margin: 20px auto;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            width: 100%;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .news-image {
            width: 90%;
            height: auto;
            border-radius: 50px; /* Asegura bordes redondeados visibles */
            object-fit: cover;
            margin: 20px auto;
            display: block;
        }
        @media (max-width: 768px) {
            .bg-cards {
                width: 100%;
                padding: 20px;
            }
            .card-custom {
                width: 100%;
            }
            .news-image {
                width: 90%;
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <?php if ($noticia): ?>
    <div class="container-fluid mt-4">
        <h1><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
        <div class="bg-cards p-3">
            <div class="card card-custom">
                <div class="row g-0">
                    <div class="col-lg-8 col-md-7">
                        <div class="card-body">
                            <p><?php echo nl2br(htmlspecialchars($noticia['resumen'])); ?></p>
                        </div>
                        <div class="card-body">
                            <p><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5">
                        <?php
                        $image_path = '/' . $noticia['imagen'];
                        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path)) {
                            echo '<img class="img-fluid rounded news-image" src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($noticia['titulo']) . '">';
                        } else {
                            echo 'Archivo no encontrado: ' . $_SERVER['DOCUMENT_ROOT'] . $image_path;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="container mt-4">
        <h1 class="text-center">Noticia no encontrada.</h1>
    </div>
    <?php endif; ?>
    <?php include 'components/footer.php'; ?>
</body>
</html>

