<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $refugio_id = $_POST['refugio_id'];

    if ($action === 'agregar') {
        // Recoger datos del formulario
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $tipo = $conn->real_escape_string($_POST['tipo']);
        $edad_categoria = $conn->real_escape_string($_POST['edad_categoria']);
        $sexo = $conn->real_escape_string($_POST['sexo']);
        $tamano = $conn->real_escape_string($_POST['tamano']);
        $raza = $conn->real_escape_string($_POST['raza'] ?? '');
        $descripcion = $conn->real_escape_string($_POST['descripcion']);
        $vacunado = isset($_POST['vacunado']) ? 1 : 0;
        $vacunas = $conn->real_escape_string($_POST['vacunas'] ?? '');
        $esterilizado = isset($_POST['esterilizado']) ? 1 : 0;
        $nivel_energia = $conn->real_escape_string($_POST['nivel_energia']);
        $relacion_ninos = $conn->real_escape_string($_POST['relacion_ninos']);
        $relacion_otros_animales = $conn->real_escape_string($_POST['relacion_otros_animales']);
        $peso = $_POST['peso'] ? $conn->real_escape_string($_POST['peso']) : 'NULL';
        $necesidades_especiales = $conn->real_escape_string($_POST['necesidades_especiales'] ?? '');

        // Insertar animal en la base de datos
        $sql = "INSERT INTO animales (nombre, tipo, edad_categoria, sexo, raza, tamano, descripcion, 
                                     vacunado, vacunas, esterilizado, nivel_energia, relacion_ninos, 
                                     relacion_otros_animales, peso, necesidades_especiales, id_refugio, estado) 
                VALUES ('$nombre', '$tipo', '$edad_categoria', '$sexo', '$raza', '$tamano', '$descripcion',
                        $vacunado, '$vacunas', $esterilizado, '$nivel_energia', '$relacion_ninos',
                        '$relacion_otros_animales', $peso, '$necesidades_especiales', $refugio_id, 'disponible')";

        if ($conn->query($sql)) {
            $animal_id = $conn->insert_id;
            
            // Procesar fotos si se subieron
            if (!empty($_FILES['fotos']['name'][0])) {
                procesarFotos($animal_id, $conn);
            }

            header("Location: ../pages/animals.php?success=animal_agregado");
        } else {
            header("Location: ../pages/agregar_animal.php?error=guardar_fallo");
        }
    }
}

function procesarFotos($animal_id, $conn) {
    $upload_dir = '../uploads/animals/';
    
    // Crear directorio si no existe
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['fotos']['error'][$key] === 0) {
            $file_name = uniqid() . '_' . basename($_FILES['fotos']['name'][$key]);
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($tmp_name, $file_path)) {
                // La primera foto será la principal
                $es_principal = ($key === 0) ? 1 : 0;
                
                $sql = "INSERT INTO fotos_animales (id_animal, ruta_foto, es_principal, orden) 
                        VALUES ($animal_id, '$file_name', $es_principal, $key)";
                $conn->query($sql);
            }
        }
    }
}

$conn->close();
?>