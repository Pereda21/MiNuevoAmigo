<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y limpiar datos
    $tipo = $conn->real_escape_string($_POST['tipo']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telefono = $conn->real_escape_string($_POST['telefono'] ?? '');
    $ciudad = $conn->real_escape_string($_POST['ciudad'] ?? '');

    // Verificar si usuario o email ya existen
    $check_sql = "SELECT id FROM usuarios WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        header("Location: ../pages/register.php?error=usuario_existe");
        exit();
    }

    // Insertar en tabla usuarios
    $sql = "INSERT INTO usuarios (username, email, password, tipo) 
            VALUES ('$username', '$email', '$password', '$tipo')";

    if ($conn->query($sql) === TRUE) {
        $user_id = $conn->insert_id;

        // Insertar en tabla específica según tipo
        if ($tipo === 'adoptante') {
            $nombre = $conn->real_escape_string($_POST['nombre']);
            $apellidos = $conn->real_escape_string($_POST['apellidos']);
            
            $sql_detail = "INSERT INTO adoptantes (id, nombre, apellidos, telefono, ciudad) 
                          VALUES ('$user_id', '$nombre', '$apellidos', '$telefono', '$ciudad')";
        } else {
            $nombre_refugio = $conn->real_escape_string($_POST['nombre_refugio']);
            $nombre_contacto = $conn->real_escape_string($_POST['nombre_contacto']);
            
            $sql_detail = "INSERT INTO refugios (id, nombre_refugio, nombre_contacto, telefono, ciudad) 
                          VALUES ('$user_id', '$nombre_refugio', '$nombre_contacto', '$telefono', '$ciudad')";
        }

        if ($conn->query($sql_detail)) {
            // Iniciar sesión
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $tipo;
            $_SESSION['logged_in'] = true;

            header("Location: ../pages/profile.php?success=registro_ok");
        } else {
            header("Location: ../pages/register.php?error=detalle_fallo");
        }
    } else {
        header("Location: ../pages/register.php?error=registro_fallo");
    }

    $conn->close();
}
?>