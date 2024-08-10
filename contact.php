<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    session_start(); 
    include 'components/head_meta.php'; 
    ?>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h4 {
            font-weight: 800;
            text-align: center;
            margin-top: 20px;
        }
        
        .bg-form {
            background-color: #d6e1f7;
            border-radius: 20px;
            padding: 20px;
            margin-top: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            position: relative;
            min-height: 300px; 
        }

        label {
            font-weight: 200;
            color: #4a4a4a;
            margin-bottom: 8px;
            display: block;
        }

        input.form-control {
            border-radius: 10px;
            background-color: #6DA0ED;
            color: #fff;
            padding: 14px;
            width: 100%;
            margin-bottom: 20px;
            border: none;
            opacity: 0.9;
        }

        input.form-control:disabled {
            background-color: #BBD5FD;
            color: black;
            opacity: 1;
        } 

        .imagen {
            width: 100%;
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-right: 20px;
        }

        .logo {
            width: 50%;
            position: absolute;
            bottom: 255px;
            right: 50px;
            z-index: 1; 
            max-width: 250px;
        }

        .autobus {
            position: absolute;
            bottom: 80px;
            right: 30px;
            max-width: 300px;
            z-index: 2; 
        }

        @media (max-width: 768px) {
            .bg-form {
                padding: 20px;
                margin: 20px 10px;
                border-radius: 20px;
                position: relative;
                min-height: 400px;
            }

            input.form-control {
                width: 100%;
                border-radius: 20px;
                margin: 10px auto;
                background-color: #FFFFFF;
                padding: 16px;
            }

            .imagen {
                width: 100%;
                height: auto;
                margin-top: 20px;
                position: relative;
                justify-content: center;
                flex-direction: column;
                align-items: center;
                transform: translate(0, 0);
            }

            .logo {
                position: relative;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                max-width: 150px;
                z-index: 1;
            }

            .autobus {
                position: relative;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                max-width: 250px;
                z-index: 2;
            }
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="container">
        <h4>CONTACTO</h4>
        <div class="row bg-form">
            <div class="col-12 col-md-8">
                <form>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="Autobuses Halcón División México" disabled>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" value="autobuseshalcon.dmo@gmail.com" disabled>
                    </div>
                    <div class="form-group">
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" id="address" name="address" value="C. Valdivieso S/N B. San Antonio, San Pablo Huixtepec" disabled>
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-4 imagen">
                <img class="autobus img-fluid" src="assets/images/autobus.png" alt="">
                <img class="logo img-fluid" src="assets/images/logoTicket.png" alt="">
            </div>
        </div>
    </div>
</body>
<?php include 'components/footer.php'; ?>
</html>
