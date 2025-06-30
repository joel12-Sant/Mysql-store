Drop DATABASE if EXISTS tienda;
CREATE DATABASE IF NOT EXISTS tienda;
USE tienda;

-- Tabla: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'cliente') DEFAULT 'cliente',
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: playeras
CREATE TABLE playeras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255)
);

-- Tabla: carritos
CREATE TABLE carritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla: carrito_items
CREATE TABLE carrito_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    carrito_id INT,
    producto_id INT,
    cantidad INT,
    talla VARCHAR(50),
    fecha_agregado DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (carrito_id) REFERENCES carritos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES playeras(id) ON DELETE CASCADE
);

-- Tabla: compras
CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    total DECIMAL(10,2),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Tabla: compra_items
CREATE TABLE compra_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    compra_id INT,
    playera_id INT,
    talla VARCHAR(50),
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    FOREIGN KEY (compra_id) REFERENCES compras(id) ON DELETE CASCADE,
    FOREIGN KEY (playera_id) REFERENCES playeras(id) ON DELETE SET NULL
);
