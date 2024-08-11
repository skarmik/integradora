<?php
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                ?>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="assets/images/logoTicket.png" alt="" style="width: 200px;">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"
            style="border-color: rgba(255,255,255,0.1);">
            <span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 30 30\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath stroke=\'rgba(255, 255, 255, 1)\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-miterlimit=\'10\' d=\'M4 7h22M4 15h22M4 23h22\'/%3E%3C/svg%3E');"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['admin_id'])): ?>
          <!-- Links adicionales para administradores -->
          <li class="nav-item">
                        <a class="nav-link mx-3" href="new_noticia.php" style="color: white;">AGREGAR NOTICIA</a>
                    </li>
          <li class="nav-item mx-3">
            <a class="nav-link" href="auth/admin_register.php" style="color: white;">AGREGAR ADMIN</a>
          </li>
          <li class="nav-item mx-3">
            <a class="nav-link" href="new_horario.php" style="color: white;">AGREGAR HORARIO</a>
          </li>
          <li class="nav-item mx-3">
            <a class="nav-link" href="new_autobus.php" style="color: white;">AGREGAR AUTOBUS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="new_trayecto.php" style="color: white;">AGREGAR TRAYECTO</a>
          </li>
        <?php elseif (isset($_SESSION['user_id'])): ?>
         
        <?php endif;?>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="index.php" style="color: white;">INICIO</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="contact.php" style="color: white;">CONTACTO</a>
                </li>
              
                <a class="mx-3" href="<?php echo isset($_SESSION['admin_id']) ? 'admin_profile.php' : (isset($_SESSION['user_id']) ? 'user.php' : 'auth/login.php'); ?>">
                    <img style="width: 45px;" src="assets/images/user.png" alt="Perfil del usuario">
                </a>
                
         

            </ul>
        </div>
    </div>
</nav>
