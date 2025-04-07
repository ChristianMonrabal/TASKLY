CREATE DATABASE IF NOT EXISTS taskly;
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE taskly;

-- Tabla de roles (un rol por usuario)
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

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
    dni VARCHAR(9) UNIQUE,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    rol_id INT NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
) ENGINE=InnoDB;

-- Datos bancarios
CREATE TABLE datos_bancarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titular VARCHAR(100),
    iban VARCHAR(34) UNIQUE,
    banco VARCHAR(100),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Estados de trabajo
CREATE TABLE estados_trabajo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Estados de postulación
CREATE TABLE estados_postulacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Estados de pago
CREATE TABLE estados_pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Métodos de pago
CREATE TABLE metodos_pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Trabajos publicados por clientes
CREATE TABLE trabajos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    direccion VARCHAR(255),
    estado_id INT NOT NULL,
    fecha_publicacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_limite DATE,
    categoria_id INT NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (estado_id) REFERENCES estados_trabajo(id)
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Postulaciones de trabajadores
CREATE TABLE postulaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trabajo_id INT NOT NULL,
    trabajador_id INT NOT NULL,
    fecha_postulacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado_id INT NOT NULL,
    UNIQUE KEY trabajo_trabajador_unico (trabajo_id, trabajador_id),
    FOREIGN KEY (trabajo_id) REFERENCES trabajos(id) ON DELETE CASCADE,
    FOREIGN KEY (trabajador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (estado_id) REFERENCES estados_postulacion(id)
) ENGINE=InnoDB;

-- Chats entre cliente y trabajador
CREATE TABLE chats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trabajo_id INT NOT NULL,
    cliente_id INT NOT NULL,
    trabajador_id INT NOT NULL,
    fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trabajo_id) REFERENCES trabajos(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id),
    FOREIGN KEY (trabajador_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

-- Mensajes en los chats
CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chat_id INT NOT NULL,
    emisor_id INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chat_id) REFERENCES chats(id) ON DELETE CASCADE,
    FOREIGN KEY (emisor_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

-- Valoraciones (solo del cliente al trabajador)
CREATE TABLE valoraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trabajo_id INT NOT NULL UNIQUE,
    trabajador_id INT NOT NULL,
    puntuacion TINYINT NOT NULL CHECK (puntuacion BETWEEN 1 AND 5),
    img_valoracion VARCHAR(255),
    fecha_valoracion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trabajo_id) REFERENCES trabajos(id) ON DELETE CASCADE,
    FOREIGN KEY (trabajador_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Pagos entre cliente y trabajador
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trabajo_id INT NOT NULL,
    cliente_id INT NOT NULL,
    trabajador_id INT NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    estado_id INT NOT NULL,
    metodo_id INT NOT NULL,
    fecha_pago DATETIME,
    FOREIGN KEY (trabajo_id) REFERENCES trabajos(id),
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id),
    FOREIGN KEY (trabajador_id) REFERENCES usuarios(id),
    FOREIGN KEY (estado_id) REFERENCES estados_pago(id),
    FOREIGN KEY (metodo_id) REFERENCES metodos_pago(id)
) ENGINE=InnoDB;

-- Habilidades de los trabajadores
CREATE TABLE habilidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trabajador_id INT NOT NULL,
    tag VARCHAR(100),
    FOREIGN KEY (trabajador_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Notificaciones del sistema
CREATE TABLE notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    mensaje TEXT NOT NULL,
    leido BOOLEAN DEFAULT FALSE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Logros del usuario
CREATE TABLE logros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nombre VARCHAR(100),
    descripcion TEXT,
    fecha_obtenido DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Codigos promocionales
CREATE TABLE codigos_promocionales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    descripcion TEXT,
    porcentaje_descuento INT NOT NULL,
    fecha_expiracion DATE,
    estado BOOLEAN DEFAULT TRUE
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Relación entre códigos promocionales y trabajos
CREATE TABLE codigos_promocionales_trabajos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_id INT NOT NULL,
    trabajo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    FOREIGN KEY (codigo_id) REFERENCES codigos_promocionales(id) ON DELETE CASCADE,
    FOREIGN KEY (trabajo_id) REFERENCES trabajos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;