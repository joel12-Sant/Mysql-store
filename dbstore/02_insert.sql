USE tienda;

INSERT INTO playeras (id, nombre, precio, cantidad, descripcion, imagen) VALUES
(1, 'VueJS', 25.00, 10, 'Playera oficial de VueJS, cómoda y ligera.', '1.jpg'),
(2, 'AngularJS', 25.00, 8, 'Playera AngularJS para fanáticos del framework.', '2.jpg'),
(3, 'ReactJS', 25.00, 15, 'Playera ReactJS con diseño moderno.', '3.jpg'),
(4, 'ReduxJS', 25.00, 12, 'Playera Redux para manejar el estado con estilo.', '4.jpg'),
(5, 'NodeJS', 25.00, 20, 'Playera NodeJS para amantes del backend.', '5.jpg'),
(6, 'SazJS', 25.00, 7, 'Playera SazJS con diseño exclusivo.', '6.jpg'),
(7, 'HTML5', 25.00, 18, 'Playera HTML5 para diseñadores y desarrolladores web.', '7.jpg'),
(8, 'GitHub', 25.00, 25, 'Playera GitHub para colaboraciones y código abierto.', '8.jpg'),
(9, 'Bulma', 25.00, 10, 'Playera Bulma con estilo CSS moderno.', '9.jpg'),
(10, 'TypeScript', 25.00, 14, 'Playera TypeScript para programadores estrictos.', '10.jpg'),
(11, 'Drupal', 25.00, 9, 'Playera Drupal para entusiastas de CMS.', '11.jpg'),
(12, 'JavaScript', 25.00, 30, 'Playera JavaScript, la reina del frontend.', '12.jpg'),
(13, 'GraphQL', 25.00, 6, 'Playera GraphQL para APIs modernas.', '13.jpg'),
(14, 'WordPress', 25.00, 11, 'Playera WordPress para creadores de sitios web.', '14.jpg');

INSERT INTO usuarios (id, nombre, correo, password, direccion, rol, creado_en) VALUES (
    1,
    'admin',
    'admin@tienda.com',
    '$2y$10$uq/g.lnNidYM92Wg/tCMJuFOvramsZhfXUis3cj5xxawVhq5RXqwC',
    'sin',
    'admin',
    '2025-06-25 00:00:00'
);
