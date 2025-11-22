<?php
require_once '../config/database.php';

// Verificar que el usuario esté logueado
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Obtener datos del usuario según su tipo
if ($user_type === 'adoptante') {
    $sql = "SELECT u.username, u.email, a.nombre, a.apellidos, a.telefono, a.ciudad 
            FROM usuarios u 
            JOIN adoptantes a ON u.id = a.id 
            WHERE u.id = $user_id";
} else {
    $sql = "SELECT u.username, u.email, r.nombre_refugio, r.nombre_contacto, r.telefono, r.ciudad, r.descripcion 
            FROM usuarios u 
            JOIN refugios r ON u.id = r.id 
            WHERE u.id = $user_id";
}

$result = $conn->query($sql);
$user_data = $result->fetch_assoc();

// Obtener estadísticas según el tipo de usuario
if ($user_type === 'adoptante') {
    // Estadísticas para adoptante
    $solicitudes_sql = "SELECT COUNT(*) as total FROM solicitudes_adopcion WHERE id_adoptante = '$user_id'";
    $solicitudes_result = $conn->query($solicitudes_sql);
    $total_solicitudes = $solicitudes_result->fetch_assoc()['total'];
    
    $aceptadas_sql = "SELECT COUNT(*) as aceptadas FROM solicitudes_adopcion WHERE id_adoptante = '$user_id' AND estado = 'aceptada'";
    $aceptadas_result = $conn->query($aceptadas_sql);
    $solicitudes_aceptadas = $aceptadas_result->fetch_assoc()['aceptadas'];
} else {
    // Estadísticas para refugio
    $animales_sql = "SELECT COUNT(*) as total FROM animales WHERE id_refugio = '$user_id' AND estado = 'disponible'";
    $animales_result = $conn->query($animales_sql);
    $animales_activos = $animales_result->fetch_assoc()['total'];
    
    $solicitudes_pendientes_sql = "SELECT COUNT(*) as pendientes 
                                  FROM solicitudes_adopcion sa 
                                  JOIN animales a ON sa.id_animal = a.id 
                                  WHERE a.id_refugio = '$user_id' AND sa.estado = 'pendiente'";
    $solicitudes_pendientes_result = $conn->query($solicitudes_pendientes_sql);
    $solicitudes_pendientes = $solicitudes_pendientes_result->fetch_assoc()['pendientes'];
    
    $adopciones_exitosas_sql = "SELECT COUNT(*) as exitosas 
                               FROM solicitudes_adopcion sa 
                               JOIN animales a ON sa.id_animal = a.id 
                               WHERE a.id_refugio = '$user_id' AND sa.estado = 'aceptada'";
    $adopciones_exitosas_result = $conn->query($adopciones_exitosas_sql);
    $adopciones_exitosas = $adopciones_exitosas_result->fetch_assoc()['exitosas'];
}
?>

<?php require_once '../includes/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <!-- Panel lateral -->
            <div class="card sombra-card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Mi Perfil</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <span class="text-white fs-2">
                                <?php echo $user_type === 'adoptante' ? '👤' : '🏠'; ?>
                            </span>
                        </div>
                    </div>
                    <h5><?php echo $user_data['username']; ?></h5>
                    <p class="text-muted">
                        <?php echo $user_type === 'adoptante' ? 'Adoptante' : 'Refugio'; ?>
                    </p>
                </div>
            </div>

            <!-- Menú de navegación -->
            <div class="card sombra-card">
                <div class="card-body">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="profile.php">
                                📝 Mi información
                            </a>
                        </li>
                        <?php if ($user_type === 'adoptante'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="solicitudes.php">
                                    📋 Mis solicitudes
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="dashboard.php">
                                    🐾 Mis animales
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="solicitudes_refugio.php">
                                    📨 Solicitudes
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Información del perfil -->
            <div class="card sombra-card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Información Personal</h5>
                    <a href="editar_perfil.php" class="btn btn-light btn-sm">✏️ Editar</a>
                </div>
                <div class="card-body">
                    <?php if ($user_type === 'adoptante'): ?>
                        <!-- Perfil de adoptante -->
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Nombre:</div>
                            <div class="col-sm-8"><?php echo $user_data['nombre'] . ' ' . $user_data['apellidos']; ?></div>
                        </div>
                    <?php else: ?>
                        <!-- Perfil de refugio -->
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Refugio:</div>
                            <div class="col-sm-8"><?php echo $user_data['nombre_refugio']; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Contacto:</div>
                            <div class="col-sm-8"><?php echo $user_data['nombre_contacto']; ?></div>
                        </div>
                        <?php if (!empty($user_data['descripcion'])): ?>
                        <div class="row mb-3">
                            <div class="col-sm-4 fw-bold">Descripción:</div>
                            <div class="col-sm-8"><?php echo $user_data['descripcion']; ?></div>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Información común -->
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Usuario:</div>
                        <div class="col-sm-8"><?php echo $user_data['username']; ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Email:</div>
                        <div class="col-sm-8"><?php echo $user_data['email']; ?></div>
                    </div>
                    <?php if (!empty($user_data['telefono'])): ?>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Teléfono:</div>
                        <div class="col-sm-8"><?php echo $user_data['telefono']; ?></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($user_data['ciudad'])): ?>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Ciudad:</div>
                        <div class="col-sm-8"><?php echo $user_data['ciudad']; ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="row mt-4">
                <?php if ($user_type === 'adoptante'): ?>
                    <div class="col-md-6">
                        <div class="card sombra-card text-center">
                            <div class="card-body">
                                <h3 class="text-success"><?php echo $total_solicitudes; ?></h3>
                                <p class="text-muted">Solicitudes enviadas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card sombra-card text-center">
                            <div class="card-body">
                                <h3 class="text-success"><?php echo $solicitudes_aceptadas; ?></h3>
                                <p class="text-muted">Adopciones aceptadas</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-4">
                        <div class="card sombra-card text-center">
                            <div class="card-body">
                                <h3 class="text-success"><?php echo $animales_activos; ?></h3>
                                <p class="text-muted">Animales activos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card sombra-card text-center">
                            <div class="card-body">
                                <h3 class="text-success"><?php echo $solicitudes_pendientes; ?></h3>
                                <p class="text-muted">Solicitudes pendientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card sombra-card text-center">
                            <div class="card-body">
                                <h3 class="text-success"><?php echo $adopciones_exitosas; ?></h3>
                                <p class="text-muted">Adopciones exitosas</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>