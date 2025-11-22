<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    
    // Verificar que el usuario esté logueado y sea adoptante
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'adoptante') {
        header("Location: ../pages/login.php");
        exit();
    }

    $solicitud_id = $conn->real_escape_string($_POST['solicitud_id']);
    $adoptante_id = $_SESSION['user_id'];

    // Verificar que la solicitud pertenece al adoptante y está pendiente
    $check_sql = "SELECT id FROM solicitudes_adopcion WHERE id = '$solicitud_id' AND id_adoptante = '$adoptante_id' AND estado = 'pendiente'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows === 1) {
        // Cancelar la solicitud
        $update_sql = "UPDATE solicitudes_adopcion SET estado = 'cancelada', fecha_resolucion = NOW() WHERE id = '$solicitud_id'";
        
        if ($conn->query($update_sql)) {
            header("Location: ../pages/solicitudes.php?success=solicitud_cancelada");
        } else {
            header("Location: ../pages/solicitudes.php?error=cancelar_fallo");
        }
    } else {
        header("Location: ../pages/solicitudes.php?error=solicitud_no_encontrada");
    }

    $conn->close();
} else {
    header("Location: ../pages/solicitudes.php");
    exit();
}
?>