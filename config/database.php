<?php
$servidor = "localhost"; // Cambia esto si tu BD está en otro servidor
$usuario = "root"; // Usuario de MySQL
$password = ""; // Contraseña de MySQL (déjala vacía si no tienes una)
$base_datos = "MiNuevoAmigo";

// Crear conexión
$conn = new mysqli($servidor, $usuario, $password, $base_datos);

// Comprobar conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

/* require_once '../config/database.php';
echo "¡Conexión exitosa! Base de datos conectada correctamente."; */

?>