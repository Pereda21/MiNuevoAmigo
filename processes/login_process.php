<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Buscar usuario por username o email
    $sql = "SELECT * FROM usuarios WHERE username = '$username' OR email = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verificar contraseña
        if (password_verify($password, $user['password'])) {
            // Iniciar sesión
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['tipo'];
            $_SESSION['logged_in'] = true;

            header("Location: ../pages/profile.php");
            exit();
        }
    }
    
    // Si falla el login
    header("Location: ../pages/login.php?error=credenciales_incorrectas");
    exit();
}

$conn->close();
?>