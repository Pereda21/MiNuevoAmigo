<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validar que si existe user_id, el usuario realmente existe
// Esto evita que sesiones fantasma persistan
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../config/database.php';
    $user_id = $_SESSION['user_id'];
    $check = $conn->query("SELECT id FROM usuarios WHERE id = '$user_id' LIMIT 1");
    if ($check->num_rows === 0) {
        // Usuario no existe, destruir sesión
        $_SESSION = array();
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MiNuevoAmigo - Adopta una Mascota</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

  <!-- Barra de navegación -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php">MiNuevoAmigo</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="pages/animals.php">Animales</a></li>
          <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><a class="nav-link" href="pages/profile.php">Mi Perfil</a></li>
            <li class="nav-item"><a class="nav-link" href="pages/logout.php">Cerrar Sesión</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="pages/login.php">Iniciar sesión</a></li>
            <li class="nav-item"><a class="nav-link" href="pages/register.php">Registrarse</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
