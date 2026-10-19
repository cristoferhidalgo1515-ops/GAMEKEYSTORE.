DROP DATABASE IF EXISTS gamekey_store;

CREATE DATABASE gamekey_store
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE gamekey_store;


/* =========================================================
   TABLA: CATEGORÍAS
========================================================= */

CREATE TABLE categorias (
    id_categoria INT NOT NULL AUTO_INCREMENT,

    nombre VARCHAR(100) NOT NULL,

    descripcion VARCHAR(255) DEFAULT NULL,

    estado ENUM(
        'activa',
        'inactiva'
    ) NOT NULL DEFAULT 'activa',

    fecha_registro TIMESTAMP
    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_categoria),

    UNIQUE KEY uk_categoria_nombre (nombre)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;


/* =========================================================
   DATOS REGISTRADOS: CATEGORÍAS
========================================================= */

INSERT INTO categorias (
    id_categoria,
    nombre,
    descripcion,
    estado,
    fecha_registro
)
VALUES
(
    1,
    'Acción',
    'Videojuegos centrados en combate y movimientos rápidos.',
    'activa',
    '2026-07-29 02:29:49'
),
(
    2,
    'Aventura',
    'Videojuegos basados en exploración e historia.',
    'activa',
    '2026-07-29 02:29:49'
),
(
    3,
    'Deportes',
    'Videojuegos relacionados con disciplinas deportivas.',
    'activa',
    '2026-07-29 02:29:49'
),
(
    4,
    'Carreras',
    'Videojuegos de automóviles, motocicletas y competencias.',
    'activa',
    '2026-07-29 02:29:49'
),
(
    5,
    'RPG',
    'Videojuegos de rol, progreso y desarrollo de personajes.',
    'activa',
    '2026-07-29 02:29:49'
),
(
    6,
    'karaojke',
    'cantar es mi pasion..',
    'activa',
    '2026-07-29 02:36:52'
);


/* =========================================================
   TABLA: PLATAFORMAS
========================================================= */

CREATE TABLE plataformas (
    id_plataforma INT NOT NULL AUTO_INCREMENT,

    nombre VARCHAR(100) NOT NULL,

    descripcion VARCHAR(255) DEFAULT NULL,

    estado ENUM(
        'activa',
        'inactiva'
    ) NOT NULL DEFAULT 'activa',

    fecha_registro TIMESTAMP
    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_plataforma),

    UNIQUE KEY uk_plataforma_nombre (nombre)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;


/* =========================================================
   DATOS REGISTRADOS: PLATAFORMAS
========================================================= */

INSERT INTO plataformas (
    id_plataforma,
    nombre,
    descripcion,
    estado,
    fecha_registro
)
VALUES
(
    1,
    'PlayStation 5',
    'Videojuegos disponibles para la consola PlayStation 5.',
    'activa',
    '2026-07-29 02:49:22'
),
(
    2,
    'PlayStation 4',
    'Videojuegos disponibles para la consola PlayStation 4.',
    'activa',
    '2026-07-29 02:49:22'
),
(
    3,
    'Xbox Series X/S',
    'Videojuegos disponibles para Xbox Series X y Xbox Series S.',
    'activa',
    '2026-07-29 02:49:22'
),
(
    4,
    'Nintendo Switch',
    'Videojuegos disponibles para Nintendo Switch.',
    'activa',
    '2026-07-29 02:49:22'
),
(
    5,
    'PC',
    'Videojuegos disponibles para computadoras.',
    'activa',
    '2026-07-29 02:49:22'
),
(
    6,
    'Steam',
    'La mejor.',
    'activa',
    '2026-07-29 02:57:42'
);


/* =========================================================
   TABLA: USUARIOS
========================================================= */

CREATE TABLE usuarios (
    id_usuario INT NOT NULL AUTO_INCREMENT,

    nombres VARCHAR(80) NOT NULL,

    apellidos VARCHAR(80) NOT NULL,

    correo VARCHAR(150) NOT NULL,

    telefono VARCHAR(20) DEFAULT NULL,

    contrasena VARCHAR(255) NOT NULL,

    direccion VARCHAR(255) DEFAULT NULL,

    rol ENUM(
        'administrador',
        'cliente',
        'empleado'
    ) NOT NULL DEFAULT 'cliente',

    estado ENUM(
        'activo',
        'inactivo'
    ) NOT NULL DEFAULT 'activo',

    fecha_registro TIMESTAMP
    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_usuario),

    UNIQUE KEY uk_usuario_correo (correo)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;


/* =========================================================
   DATOS REGISTRADOS: USUARIOS
========================================================= */

INSERT INTO usuarios (
    id_usuario,
    nombres,
    apellidos,
    correo,
    telefono,
    contrasena,
    direccion,
    rol,
    estado,
    fecha_registro
)
VALUES
(
    3,
    'Brayan Alexis',
    'Sandoval Rodriguez',
    'bsandovalrodriguez0@gmail.com',
    '69316535',
    '$2y$10$eBRM7wVLHNK6v2akBnnWCOuHh7ZrQQcnRVB9Y1SQMZ8OI/TvIQUhy',
    'San Lorenzo\r\nAhuachapán',
    'administrador',
    'activo',
    '2026-07-29 23:07:25'
);


/* =========================================================
   TABLA: VIDEOJUEGOS
========================================================= */

CREATE TABLE videojuegos (
    id_videojuego INT NOT NULL AUTO_INCREMENT,

    titulo VARCHAR(150) NOT NULL,

    descripcion TEXT DEFAULT NULL,

    precio DECIMAL(10, 2)
    NOT NULL DEFAULT 0.00,

    stock INT
    NOT NULL DEFAULT 0,

    imagen VARCHAR(255) DEFAULT NULL,

    id_categoria INT NOT NULL,

    id_plataforma INT NOT NULL,

    estado ENUM(
        'activo',
        'inactivo'
    ) NOT NULL DEFAULT 'activo',

    fecha_registro TIMESTAMP
    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_videojuego),

    KEY idx_videojuego_categoria (id_categoria),

    KEY idx_videojuego_plataforma (id_plataforma),

    CONSTRAINT fk_videojuego_categoria
        FOREIGN KEY (id_categoria)
        REFERENCES categorias (id_categoria)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_videojuego_plataforma
        FOREIGN KEY (id_plataforma)
        REFERENCES plataformas (id_plataforma)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_general_ci;


/* =========================================================
   DATOS REGISTRADOS: VIDEOJUEGOS
========================================================= */

INSERT INTO videojuegos (
    id_videojuego,
    titulo,
    descripcion,
    precio,
    stock,
    imagen,
    id_categoria,
    id_plataforma,
    estado,
    fecha_registro
)
VALUES
(
    1,
    'Marvel Spider-Man 2',
    'Videojuego de acción y aventura protagonizado por Spider-Man.',
    69.99,
    15,
    'img/videojuegos/videojuego_1785373022_3b32c31a.jpg',
    1,
    1,
    'activo',
    '2026-07-29 23:40:32'
),
(
    2,
    'Minecraft',
    'Videojuego de construcción, supervivencia y exploración.',
    29.99,
    40,
    'img/videojuegos/videojuego_1785373064_f1fb5aa0.jpg',
    2,
    3,
    'activo',
    '2026-07-29 23:40:32'
),
(
    3,
    'Hollow Knight',
    'Videojuego de acción, aventura y exploración.',
    14.99,
    20,
    'img/videojuegos/videojuego_1785372843_34b7ea6a.webp',
    2,
    5,
    'activo',
    '2026-07-30 00:33:14'
);


/* =========================================================
   CONFIGURACIÓN DE LOS SIGUIENTES IDENTIFICADORES
========================================================= */

ALTER TABLE categorias
AUTO_INCREMENT = 7;

ALTER TABLE plataformas
AUTO_INCREMENT = 7;

ALTER TABLE usuarios
AUTO_INCREMENT = 4;

ALTER TABLE videojuegos
AUTO_INCREMENT = 4;


/* =========================================================
   COMPROBACIÓN FINAL
========================================================= */

SELECT
    'Base de datos creada correctamente' AS resultado;

SELECT
    COUNT(*) AS total_categorias
FROM categorias;

SELECT
    COUNT(*) AS total_plataformas
FROM plataformas;

SELECT
    COUNT(*) AS total_usuarios
FROM usuarios;

SELECT
    COUNT(*) AS total_videojuegos
FROM videojuegos;