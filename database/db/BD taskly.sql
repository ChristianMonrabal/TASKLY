DROP DATABASE taskly_final;

CREATE DATABASE taskly_final DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE taskly_final;

-- Tabla de roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE = InnoDB;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    codigo_postal VARCHAR(10),
    password_hash VARCHAR(255) NOT NULL,
    google_id VARCHAR(100),
    fecha_nacimiento DATE,
    foto_perfil VARCHAR(255),
    descripcion TEXT,
    dni VARCHAR(9) UNIQUE NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    rol_id INT NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
) ENGINE = InnoDB;

-- Datos bancarios
CREATE TABLE datos_bancarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titular VARCHAR(100),
    iban VARCHAR(34) UNIQUE,
    banco VARCHAR(100),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE = InnoDB;

-- Estados de trabajo
CREATE TABLE estados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    estado_para TEXT NOT NULL
) ENGINE = InnoDB;

-- Métodos de pago
CREATE TABLE metodos_pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE = InnoDB;

-- Categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE = InnoDB;

-- Trabajos publicados por clientes
CREATE TABLE trabajos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    direccion VARCHAR(255),
    estado_id INT NOT NULL,
    fecha_publicacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_limite DATE,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id),
    FOREIGN KEY (estado_id) REFERENCES estados(id)
) ENGINE = InnoDB;

-- Fotos trabajos
CREATE TABLE img_trabajos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_img INT NOT NULL,
    trabajo_id INT NOT NULL,
    FOREIGN KEY (trabajo_id) REFERENCES trabajos(id)
) ENGINE = InnoDB;

-- Categorias trabajo
CREATE TABLE categorias_tipo_trabajo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trabajo_id INT NOT NULL,
    categoria_id INT NOT NULL,
    FOREIGN KEY (trabajo_id) REFERENCES trabajos(id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
) ENGINE = InnoDB;

-- Postulaciones de trabajadores
CREATE TABLE postulaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trabajo_id INT NOT NULL,
    trabajador_id INT NOT NULL,
    fecha_postulacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado_id INT NOT NULL,
    UNIQUE KEY trabajo_trabajador_unico (trabajo_id, trabajador_id),
    FOREIGN KEY (trabajo_id) REFERENCES trabajos(id),
    FOREIGN KEY (trabajador_id) REFERENCES usuarios(id),
    FOREIGN KEY (estado_id) REFERENCES estados(id)
) ENGINE = InnoDB;

-- Chats entre cliente y trabajador
CREATE TABLE chats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trabajo_id INT NOT NULL,
    trabajador_id INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trabajo_id) REFERENCES trabajos(id),
    FOREIGN KEY (trabajador_id) REFERENCES usuarios(id)
) ENGINE = InnoDB;

-- Valoraciones (solo del cliente al trabajador)
CREATE TABLE valoraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trabajo_id INT NOT NULL UNIQUE,
    trabajador_id INT NOT NULL,
    puntuacion TINYINT NOT NULL,
    img_valoracion VARCHAR(255),
    fecha_valoracion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trabajo_id) REFERENCES trabajos(id),
    FOREIGN KEY (trabajador_id) REFERENCES usuarios(id)
) ENGINE = InnoDB;

-- Pagos entre cliente y trabajador
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trabajo_id INT NOT NULL,
    trabajador_id INT NOT NULL,
    cantidad DECIMAL(10, 2) NOT NULL,
    estado_id INT NOT NULL,
    metodo_id INT NOT NULL,
    fecha_pago DATETIME,
    FOREIGN KEY (trabajo_id) REFERENCES trabajos(id),
    FOREIGN KEY (trabajador_id) REFERENCES usuarios(id),
    FOREIGN KEY (estado_id) REFERENCES estados(id),
    FOREIGN KEY (metodo_id) REFERENCES metodos_pago(id)
) ENGINE = InnoDB;

-- Habilidades de los trabajadores
CREATE TABLE habilidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trabajador_id INT NOT NULL,
    categoria_id INT NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (trabajador_id) REFERENCES usuarios(id)
) ENGINE = InnoDB;

-- Notificaciones del sistema
CREATE TABLE notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    mensaje TEXT NOT NULL,
    leido BOOLEAN DEFAULT FALSE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE = InnoDB;

-- Logros 
CREATE TABLE logros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    descuento decimal(3)
) ENGINE = InnoDB;

-- Logros del usuario
CREATE TABLE logros_completos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(100) NOT NULL UNIQUE,
    estado BOOLEAN DEFAULT TRUE,
    logro_id INT NOT NULL,
    usuario_id INT NOT NULL,
    FOREIGN KEY (logro_id) REFERENCES logros(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE = InnoDB;