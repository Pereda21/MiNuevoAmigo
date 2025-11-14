-- 1. USUARIOS
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    tipo ENUM('adoptante', 'refugio') NOT NULL
);

-- 2. ADOPTANTES  
CREATE TABLE adoptantes (
    id INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    ciudad VARCHAR(100),
    FOREIGN KEY (id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- 3. REFUGIOS
CREATE TABLE refugios (
    id INT PRIMARY KEY,
    nombre_refugio VARCHAR(200) NOT NULL,
    nombre_contacto VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    ciudad VARCHAR(100),
    descripcion TEXT,
    FOREIGN KEY (id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- 4. ANIMALES
CREATE TABLE animales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('perro', 'gato', 'otro') NOT NULL,
    edad_categoria ENUM('cachorro', 'joven', 'adulto', 'mayor') NOT NULL,
    sexo ENUM('macho', 'hembra') NOT NULL,
    raza VARCHAR(100),
    tamaño ENUM('pequeño', 'mediano', 'grande') NOT NULL,
    descripcion TEXT,
    vacunado BOOLEAN DEFAULT FALSE,
    vacunas TEXT,
    esterilizado BOOLEAN DEFAULT FALSE,
    nivel_energia ENUM('bajo', 'medio', 'alto') NOT NULL,
    relacion_niños ENUM('excelente', 'buena', 'regular', 'mala') NOT NULL,
    relacion_otros_animales ENUM('excelente', 'buena', 'regular', 'mala') NOT NULL,
    fecha_nacimiento DATE,
    peso DECIMAL(4,1),
    necesidades_especiales TEXT,
    id_refugio INT NOT NULL,
    estado ENUM('disponible', 'adoptado', 'pendiente') DEFAULT 'disponible',
    FOREIGN KEY (id_refugio) REFERENCES usuarios(id)
);

-- 5. FOTOS_ANIMALES
CREATE TABLE fotos_animales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_animal INT NOT NULL,
    ruta_foto VARCHAR(255) NOT NULL,
    es_principal BOOLEAN DEFAULT FALSE,
    orden INT DEFAULT 0,
    FOREIGN KEY (id_animal) REFERENCES animales(id) ON DELETE CASCADE
);

-- 6. SOLICITUDES_ADOPCION
CREATE TABLE solicitudes_adopcion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_animal INT NOT NULL,
    id_adoptante INT NOT NULL,
    estado ENUM('pendiente', 'aceptada', 'rechazada', 'cancelada') DEFAULT 'pendiente',
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_resolucion TIMESTAMP NULL,
    mensaje_adoptante TEXT,
    FOREIGN KEY (id_animal) REFERENCES animales(id),
    FOREIGN KEY (id_adoptante) REFERENCES usuarios(id)
);