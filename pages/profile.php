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
                                <h3 class="text-success">0</h3>
                                <p class="text-muted">Solicitudes enviadas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card sombra-card text-center">
                            <div class="card-body">
                                <h3 class="text-success">0</h3>
                                <p class="text-muted">Adopciones completadas</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-4">
                        <div class="card sombra-card text-center">
                            <div class="card-body">
                                <h3 class="text-success">0</h3>
                                <p class="text-muted">Animales activos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card sombra-card text-center">
                            <div class="card-body">
                                <h3 class="text-success">0</h3>
                                <p class="text-muted">Solicitudes pendientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card sombra-card text-center">
                            <div class="card-body">
                                <h3 class="text-success">0</h3>
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