> [!NOTE]
> Demo en:
> https://frontendstore.jmsweb.site/

# 🛍️ FrontEnd Store

**FrontEnd Store** es una tienda virtual creada como un proyecto escolar con fines académicos y prácticos. El objetivo es ofrecer una experiencia de compra real, enfocada en desarrolladores y estudiantes de programación. Esta aplicación demuestra el uso conjunto de tecnologías frontend y backend modernas, incluyendo PHP, MySQL, y Docker.

---

## 🚀 Tecnologías utilizadas

- **PHP 8.1 (Apache)**
- **MySQL 8.0**
- **phpMyAdmin**
- **Docker + Docker Compose**
- **HTML5 + CSS3**
- **MongoDB (en fase anterior, reemplazado por MySQL)**

---

## 📦 ¿Qué incluye este proyecto?

- Autenticación de usuarios
- Carrito de compras funcional
- Gestión de productos (admin)
- Historial de compras
- Manejo de sesiones
- Persistencia con base de datos MySQL
- Interfaz moderna responsiva
- CRUD con Docker y volúmenes para persistencia

---

## ⚙️ Instalación rápida
1.- Clona el repositorio o descarga el .zip desde GitHub:

```bash
git clone https://github.com/joel12-Sant/Mysql-store.git
cd Mysql-store
```

2.- Crea un archivo/edita .env con tus variables personalizadas. Ejemplo:
```env
MYSQL_ROOT_PASSWORD=rootpass
MYSQL_DATABASE=tienda
MYSQL_USER=usuario
MYSQL_PASSWORD=usuario123
```

3.- Construye y levanta los contenedores:
```bash
docker compose up --build
```
4.- Accede a tu aplicación:

- FrontEnd Store: http://localhost:8085

- phpMyAdmin: http://localhost:8086

---

## 🐬 Servicios Docker
### MySQL
- Imagen: mysql:8.0

- Puerto: 3316 -> 3306

- Volumen persistente: dbstore/

- Plugin de autenticación nativo activado

### Apache + PHP
- Imagen personalizada desde php:8.1-apache

- Extensiones: pdo_mysql, mongodb

- Puerto: 8085 -> 80

- Código fuente montado en /var/www/html

### phpMyAdmin
- Imagen: phpmyadmin/phpmyadmin:latest

- Puerto: 8086 -> 80

---

## ✅ Funcionalidades
- Registro e inicio de sesión de usuarios

- Roles (admin y cliente)

- Agregar y quitar productos del carrito

- Comprar productos (individual o todos)

- Historial de compras con detalle

- Eliminación de cuenta

- Administración de productos para admin

---

## 🔒 Seguridad
- Sanitización y validación de entradas

- Hashing de contraseñas con password_hash

- Control de acceso por sesión

- Protección contra SQL Injection mediante PDO

---

## 📌 Notas
- MongoDB fue usado en fases previas del proyecto y ya no está activo.

- El sistema actual solo requiere PHP, MySQL y Docker, si deseas probar el proyecto con MongoDB ve a: https://github.com/joel12-Sant/mongodb-store

---

## 🧑‍💻 Autor
Desarrollado por Joel Matias Santiago & Alejandro Martinez Galeano.

---
## 📄 Licencia
Este proyecto es de uso libre con fines educativos.
