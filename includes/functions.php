<?php
// Función para sanitizar datos
function limpiarDato($dato) {
    return htmlspecialchars(trim($dato));
}

// Función para redireccionar
function redirigir($url) {
    header("Location: $url");
    exit();
}
?>