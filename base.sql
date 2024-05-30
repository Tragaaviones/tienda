DROP DATABASE if EXISTS db_niki;
CREATE DATABASE db_niki;

USE db_niki;

CREATE TABLE tb_clientes (
  id_cliente INT AUTO_INCREMENT PRIMARY KEY,
  clave_cliente VARCHAR (50) NOT NULL UNIQUE,
  nombre_cliente VARCHAR(50) NOT NULL,99999999999999999999999999
  apellido_cliente VARCHAR(50) NOT NULL,
  correo_cliente VARCHAR(50) UNIQUE NOT NULL,
  telefono_cliente VARCHAR(50) NOT NULL,
  estado_cliente BOOLEAN NOT NULL
);

CREATE TABLE tb_productos (
  id_producto INT AUTO_INCREMENT PRIMARY KEY,
  nombre_producto VARCHAR(25) NOT NULL,
  id_categoria INT,
  precio_unitario DECIMAL(5,2) NOT NULL,
  descripcion VARCHAR(255) NOT NULL,
  id_marca INT,
  imagen VARCHAR(50),
  id_administrador INT
);

CREATE TABLE tb_pedidos (
  id_pedido INT AUTO_INCREMENT PRIMARY KEY,
  id_cliente INT,
  fecha_venta DATETIME DEFAULT NOW(),
  estado_pedido BOOLEAN DEFAULT 1,
  direccion_pedido VARCHAR(255) NOT NULL
);

CREATE TABLE tb_categorias (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(25) NOT NULL,
  imagen VARCHAR(50) NOT NULL
);

CREATE TABLE tb_detalle_pedidos (
  id_detalle_pedido INT AUTO_INCREMENT PRIMARY KEY,
  id_pedido INT,
  precio_producto DECIMAL(5,2) NOT NULL,
  id_detalle_producto INT,
  cantidad_producto INT
);

CREATE TABLE tb_tipousuarios (
  id_tipo INT AUTO_INCREMENT PRIMARY KEY,
  tipo_usuario VARCHAR(25) NOT NULL,
  descripcion_usuario VARCHAR(100)
);

CREATE TABLE tb_marcas (
  id_marca INT AUTO_INCREMENT PRIMARY KEY,
  nombre_marca VARCHAR(25) NOT NULL,
  telefono_marca VARCHAR(10) NOT NULL,
  logo_marca VARCHAR(50)
);

CREATE TABLE tb_comentarios (
  id_comentario INT AUTO_INCREMENT PRIMARY KEY,
  comentario VARCHAR(255) NOT NULL,
  calificacion_producto INT NOT NULL,
  fecha_comentario DATETIME DEFAULT NOW(),
  estado_comentario BOOLEAN NOT NULL,
  id_detalle_pedidos INT
);

CREATE TABLE tb_administradores (
  id_administrador INT AUTO_INCREMENT PRIMARY KEY,
  nombre_administrador VARCHAR(50) NOT NULL,
  apellido_administrador VARCHAR(50) NOT NULL,
  clave_administrador VARCHAR(100) NOT NULL,
  correo_administrador VARCHAR(200) NOT NULL UNIQUE,
  id_tipo INT NULL 
);

CREATE TABLE tb_tallas (
  id_talla INT AUTO_INCREMENT PRIMARY KEY,
  talla VARCHAR(10) NOT NULL
);

CREATE TABLE tb_detalle_productos (
  id_detalle_producto INT AUTO_INCREMENT PRIMARY KEY,
  id_producto INT NOT NULL,
  id_talla INT NOT NULL,
  stock_producto INT NOT NULL
);

ALTER TABLE tb_productos ADD FOREIGN KEY (id_categoria) REFERENCES tb_categorias (id_categoria);

ALTER TABLE tb_pedidos ADD FOREIGN KEY (id_cliente) REFERENCES tb_clientes (id_cliente);

ALTER TABLE tb_productos ADD FOREIGN KEY (id_marca) REFERENCES tb_marcas (id_marca);

ALTER TABLE tb_detalle_pedidos ADD FOREIGN KEY (id_pedido) REFERENCES tb_pedidos (id_pedido);

ALTER TABLE tb_comentarios ADD FOREIGN KEY (id_detalle_pedidos) REFERENCES tb_detalle_pedidos (id_detalle_pedido);

ALTER TABLE tb_productos ADD FOREIGN KEY (id_administrador) REFERENCES tb_administradores (id_administrador);

ALTER TABLE tb_administradores ADD FOREIGN KEY (id_tipo) REFERENCES tb_tipousuarios (id_tipo);

ALTER TABLE tb_detalle_productos ADD FOREIGN KEY (id_producto) REFERENCES tb_productos (id_producto);

ALTER TABLE tb_detalle_productos ADD FOREIGN KEY (id_talla) REFERENCES tb_tallas (id_talla);

ALTER TABLE tb_detalle_pedidos ADD FOREIGN KEY (id_detalle_producto) REFERENCES tb_detalle_productos (id_detalle_producto);

INSERT INTO tb_tipousuarios (tipo_usuario,descripcion_usuario) VALUES('Administrador','Cargo mas alto');

INSERT INTO tb_clientes (clave_cliente, nombre_cliente, apellido_cliente, correo_cliente, telefono_cliente, estado_cliente) VALUES
('CL001', 'Juan', 'Pérez', 'juan.perez@example.com', '555-1234', TRUE),
('CL002', 'María', 'Gómez', 'maria.gomez@example.com', '555-5678', TRUE),
('CL003', 'Carlos', 'Sánchez', 'carlos.sanchez@example.com', '555-8765', FALSE),
('CL004', 'Ana', 'Martínez', 'ana.martinez@example.com', '555-4321', TRUE),
('CL005', 'Luis', 'Rodríguez', 'luis.rodriguez@example.com', '555-1122', FALSE),
('CL006', 'Elena', 'Fernández', 'elena.fernandez@example.com', '555-3344', TRUE),
('CL007', 'Marta', 'López', 'marta.lopez@example.com', '555-5566', TRUE),
('CL008', 'Miguel', 'García', 'miguel.garcia@example.com', '555-7788', FALSE),
('CL009', 'Jorge', 'Hernández', 'jorge.hernandez@example.com', '555-9900', TRUE),
('CL010', 'Lucía', 'Ramírez', 'lucia.ramirez@example.com', '555-2233', TRUE),
('CL011', 'Ricardo', 'Torres', 'ricardo.torres@example.com', '555-4455', FALSE),
('CL012', 'Isabel', 'Díaz', 'isabel.diaz@example.com', '555-6677', TRUE),
('CL013', 'José', 'Cruz', 'jose.cruz@example.com', '555-8899', TRUE);

INSERT INTO tb_pedidos (id_cliente, fecha_venta, estado_pedido, direccion_pedido) VALUES
(1, '2023-05-01 10:30:00', TRUE, '123 Calle Falsa, Ciudad Ejemplo'),
(2, '2023-05-02 14:45:00', TRUE, '456 Avenida Siempreviva, Ciudad Ejemplo'),
(3, '2023-05-03 09:15:00', FALSE, '789 Calle Verdadera, Ciudad Ejemplo'),
(4, '2023-05-04 11:50:00', TRUE, '1011 Calle Principal, Ciudad Ejemplo'),
(5, '2023-05-05 13:30:00', FALSE, '1213 Avenida Secundaria, Ciudad Ejemplo'),
(6, '2023-05-06 16:20:00', TRUE, '1415 Calle Tercera, Ciudad Ejemplo'),
(7, '2023-05-07 08:05:00', TRUE, '1617 Calle Cuarta, Ciudad Ejemplo'),
(8, '2023-05-08 12:10:00', FALSE, '1819 Calle Quinta, Ciudad Ejemplo'),
(9, '2023-05-09 15:40:00', TRUE, '2021 Calle Sexta, Ciudad Ejemplo'),
(10, '2023-05-10 17:25:00', TRUE, '2223 Avenida Séptima, Ciudad Ejemplo');

INSERT INTO tb_detalle_productos (id_producto, id_talla, stock_producto) VALUES
(1, 2, 50),
(2, 3, 30);

SELECT * FROM tb_detalle_productos
SELECT * FROM tb_pedidos

INSERT INTO tb_detalle_pedidos (id_pedido, precio_producto, id_detalle_producto, cantidad_producto) VALUES
(1, 19.99, 1, 2),
(2, 29.99, 2, 1);


INSERT INTO tb_comentarios (comentario, calificacion_producto, fecha_comentario, estado_comentario, id_detalle_pedidos) VALUES
('Producto excelente, muy recomendado', '5', '2023-05-01 14:30:00', TRUE, 1),
('El producto llegó dañado, no lo recomiendo', '1', '2023-05-02 10:45:00', FALSE, 2),
('Buena relación calidad-precio', '4', '2023-05-03 09:20:00', TRUE, 1),
('El producto es aceptable, pero tardó en llegar', '3', '2023-05-04 13:15:00', TRUE, 2),
('No estoy satisfecho con la compra', '2', '2023-05-05 16:40:00', FALSE, 2);

SELECT * FROM tb_detalle_pedidos

DELIMITER $$
CREATE PROCEDURE cambiar_estado_cliente(IN cliente_id INT)
BEGIN
    DECLARE cliente_estado BOOLEAN;
    -- Obtener el estado actual del administrador
    SELECT estado_cliente INTO cliente_estado
    FROM tb_clientes
    WHERE id_cliente = cliente_id;
    -- Actualizar el estado del administrador
    IF cliente_estado = 1 THEN
        UPDATE tb_clientes
        SET estado_cliente = 0
        WHERE id_cliente = cliente_id;
    ELSE
        UPDATE tb_clientes
        SET estado_cliente = 1
        WHERE id_cliente = cliente_id;
    END IF;
END $$

DELIMITER $$
CREATE PROCEDURE cambiar_estado_pedido(IN pedido_id INT)
BEGIN
    DECLARE pedido_estado BOOLEAN;
    -- Obtener el estado actual del administrador
    SELECT estado_pedido INTO pedido_estado
    FROM tb_pedidos
    WHERE id_pedido = pedido_id;
    -- Actualizar el estado del administrador
    IF pedido_estado = 1 THEN
        UPDATE tb_pedidos
        SET estado_pedido = 0
        WHERE id_pedido = pedido_id;
    ELSE
        UPDATE tb_pedidos
        SET estado_pedido = 1
        WHERE id_pedido = pedido_id;
    END IF;
END $$

DELIMITER $$
CREATE PROCEDURE cambiar_estado_comentario(IN comentario_id INT)
BEGIN
    DECLARE comentario_estado BOOLEAN;
    -- Obtener el estado actual del administrador
    SELECT estado_comentario INTO comentario_estado
    FROM tb_comentarios
    WHERE id_comentario = comentario_id;
    -- Actualizar el estado del administrador
    IF comentario_estado = 1 THEN
        UPDATE tb_comentarios
        SET estado_comentario = 0
        WHERE id_comentario = comentario_id;
    ELSE
        UPDATE tb_comentarios
        SET estado_comentario = 1
        WHERE id_comentario = comentario_id;
    END IF;
END $$