<?php
    include 'conection/conection.php';
    session_start();

    $is_admin = isset($_SESSION['admin_id']);
    
    $sql = "SELECT id, titulo, resumen, contenido, fecha, imagen FROM noticias ORDER BY fecha DESC LIMIT 3";
    $result = $conection->query($sql);

    $news_items = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $news_items[] = $row;
        }
    } else {
        echo "<script>
                alert('0 resultados.');
                window.location.href = 'index.php';
              </script>";
        exit;  
    }

    $sql_origen = "SELECT DISTINCT origen FROM trayecto ORDER BY origen";
    $result_origen = $conection->query($sql_origen);

    $sql_destino = "SELECT DISTINCT destino FROM trayecto ORDER BY destino";
    $result_destino = $conection->query($sql_destino);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'components/head_meta.php'; ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            .fondo {
                background: url('assets/images/Fondo_inicio.jpg') no-repeat center center fixed;
                background-size: cover;
                height: 300px;
                position: relative;
                display: flex;
                justify-content: center;
                align-items: center;
                opacity: 0.8;
            }
            .bg-cards {
                background-color: #CCCCFF; /* Tono entre morado y azul claro */
                border-radius: 20px;
                padding: 20px;
                margin-top: 20px;
                display: flex;
                justify-content: space-around;
                align-items: center;
            }
            .card-custom {
                border: none;
                border-radius: 15px;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                width: 30%;
                background: #fff;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s;
                text-decoration: none;
                color: inherit;
            }
            .card-custom:hover {
                transform: scale(1.02);
            }
            .card-custom img {
                width: 100%;
                height: auto;
                object-fit: cover;
            }
            .card-custom .card-body {
                padding: 15px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .select, .in {
                border-radius: 10px;
                padding: 14px;
                border: none;
                margin: 10px;
                opacity: 0.9;
                width: 300px;
                height: 50px;
                background-color: rgba(10, 25, 45, 0.9); /* Tono azul marino más oscuro */
                color: #fff;
            }
            .select option {
                color: #fff; /* Color blanco para las opciones del select */
                background-color: rgba(10, 25, 45, 0.9); /* Asegura que el fondo de las opciones coincida con el fondo del select */
            }
            .boton_buscar {
                border-radius: 20px;
                padding: 10px 20px;
                opacity: 0.9;
                background-color: #fff;
                color: #555;
            }
            h4 {
                font-weight: bold;
            }
            @media (max-width: 768px) {
                .bg-cards {
                    flex-direction: column;
                    align-items: center;
                    border-radius: 50px;
                }
                .card-custom {
                    width: 100%;
                    border-radius: 30px;
                    margin: 10px;
                }
                .card-custom img {
                    width: 100%;
                    height: 150px;
                }
                .card-custom .card-body {
                    width: 100%;
                }
                .fondo {
                    height: 400px;
                    justify-content: flex-start;
                    align-items: flex-start;
                    padding-top: 20px;
                }
                .formm {
                    text-align: center;
                    width: 100%;
                    margin-top: 40px;
                }
                .select, .in {
                    border-radius: 20px;
                    margin-top: 40px;
                    width: 80%;
                    margin: 5px auto;
                }
                .boton_buscar {
                    margin-top: 30px;
                    width: 40%;
                }
            }
            
        </style>
    </head>
    <body>
        <?php include 'components/navbar.php'; ?>
        <div class="fondo">
            <form class="formm" action="<?php echo $is_admin ? 'admin_r_b.php' : 'resultado_busqueda.php'; ?>" method="GET" onsubmit="return validateForm()">
                <select class="select" id="origen" name="origen">
                    <option value="" disabled selected>Origen</option>
                    <?php
                    if ($result_origen->num_rows > 0): 
                        while($row = $result_origen->fetch_assoc()): 
                    ?>
                            <option value="<?php echo htmlspecialchars($row['origen']); ?>">
                                <?php echo htmlspecialchars($row['origen']); ?>
                            </option>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                        <option>No hay orígenes disponibles</option>
                    <?php endif; ?>
                </select>
                <select class="select" id="destino" name="destino" >
                    <option value="" disabled selected>Destino</option>
                    <?php
                    if ($result_destino->num_rows > 0): 
                        while($row = $result_destino->fetch_assoc()): 
                    ?>
                            <option value="<?php echo htmlspecialchars($row['destino']); ?>">
                                <?php echo htmlspecialchars($row['destino']); ?>
                            </option>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                        <option>No hay destinos disponibles</option>
                    <?php endif; ?>
                </select>
                <input class="in" type="text" id="fecha" name="fecha" placeholder="Fecha" required>
                <button type="submit" class="btn btn-light btn-lg mx-4 boton_buscar">Buscar</button>
            </form>
        </div>
        <h4 class="mt-4 text-center mb-3">DESTACADO</h4>
        
    
        <div class="container bg-cards">
            <?php foreach ($news_items as $item): ?>
                <?php 
                    $image_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $item['imagen'];
                    $image_path = str_replace(".png.png", ".png", $image_path);
                    if (file_exists($image_path)): 
                ?>
                    <a href="noticia.php?id=<?php echo $item['id']; ?>" class="text-decoration-none text-dark card-custom">
                        <img src="/<?php echo htmlspecialchars($item['imagen']); ?>" alt="Imagen">
                        <div class="card-body">
                            <h5 class="fw-bold"><?php echo htmlspecialchars($item['titulo']); ?></h5>
                            <p><?php echo htmlspecialchars($item['resumen']); ?></p>
                        </div>
                        
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </body>
    <?php include 'components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
    $(document).ready(function() {
        $("#fecha").flatpickr({
            dateFormat: "Y-m-d",
            minDate: "today"
        });
    });

    function validateForm() {
        var origen = document.getElementById("origen").value;
        var destino = document.getElementById("destino").value;

        if (origen === "") {
            alert("Por favor selecciona un origen.");
            return false;
        }

        if (destino === "") {
            alert("Por favor selecciona un destino.");
            return false;
        }

        return true;
    }
    </script>

</html>
