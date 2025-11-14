<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si usuario está logueado
function usuarioLogueado() {
    return isset($_SESSION['user_id']);
}
?>