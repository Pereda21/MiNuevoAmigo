<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    
    // Verificar que el usuario esté logueado y sea adoptante
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'adoptante') {
        header("Location: ../pages/login.php");
        exit();
    }

    $adoptante_id = $_SESSION['user_id'];
    $animal_id = $conn->real_escape_string($_POST['animal_id']);
    $mensaje = $conn->real_escape_string($_POST['mensaje']);

    // Verificar que el animal existe y está disponible
    $animal_check = $conn->query("SELECT id, estado FROM animales WHERE id = '$animal_id' AND estado = 'disponible'");
    
    if ($animal_check->num_rows === 0) {
        header("Location: ../pages/animals.php?error=animal_no_disponible");
        exit();
    }

    // Verificar que no existe ya una solicitud pendiente del mismo adoptante para este animal
    $solicitud_check = $conn->query("SELECT id FROM solicitudes_adopcion WHERE id_animal = '$animal_id' AND id_adoptante = '$adoptante_id' AND estado = 'pendiente'");
    
    if ($solicitud_check->num_rows > 0) {
        header("Location: ../pages/animal_detalle.php?id=$animal_id&error=solicitud_existente");
        exit();
    }

    // Insertar la solicitud
    $sql = "INSERT INTO solicitudes_adopcion (id_animal, id_adoptante, estado, mensaje_adoptante) 
            VALUES ('$animal_id', '$adoptante_id', 'pendiente', '$mensaje')";

    if ($conn->query($sql)) {
        header("Location: ../pages/animal_detalle.php?id=$animal_id&success=solicitud_enviada");
    } else {
        header("Location: ../pages/animal_detalle.php?id=$animal_id&error=solicitud_fallo");
    }

    $conn->close();
} else {
    header("Location: ../pages/animals.php");
    exit();
}
?>