DROP DATABASE IF EXISTS tienda;
-- Crear la base de datos
CREATE DATABASE tienda;
USE tienda;

-- Crear la tabla de playeras
CREATE TABLE IF NOT EXISTS playeras (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL,
  imagen VARCHAR(255),
  stock INT DEFAULT 0
);

