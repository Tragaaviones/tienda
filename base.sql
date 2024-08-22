DROP DATABASE if EXISTS db_niki;
CREATE DATABASE db_niki;

USE db_niki;

CREATE TABLE tb_clientes (
  id_cliente INT AUTO_INCREMENT PRIMARY KEY,
  clave_cliente VARCHAR (100) NOT NULL UNIQUE,
  nombre_cliente VARCHAR(50) NOT NULL,
  apellido_cliente VARCHAR(50) NOT NULL,
  correo_cliente VARCHAR(50) UNIQUE NOT NULL,
  telefono_cliente VARCHAR(50) NOT NULL,
  direccion_cliente VARCHAR(100) NOT NULL,
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
  fecha_venta DATE DEFAULT NOW(),
  estado_pedido ENUM('Pendiente','Entregado','En camino','Cancelado') DEFAULT 'Pendiente',
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
  id_cliente INT NOT NULL
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

ALTER TABLE tb_comentarios ADD FOREIGN KEY (id_cliente) REFERENCES tb_clientes (id_cliente);

ALTER TABLE tb_productos ADD FOREIGN KEY (id_administrador) REFERENCES tb_administradores (id_administrador);

ALTER TABLE tb_administradores ADD FOREIGN KEY (id_tipo) REFERENCES tb_tipousuarios (id_tipo);

ALTER TABLE tb_detalle_productos ADD FOREIGN KEY (id_producto) REFERENCES tb_productos (id_producto);

ALTER TABLE tb_detalle_productos ADD FOREIGN KEY (id_talla) REFERENCES tb_tallas (id_talla);

ALTER TABLE tb_detalle_pedidos ADD FOREIGN KEY (id_detalle_producto) REFERENCES tb_detalle_productos (id_detalle_producto);

INSERT INTO tb_tipousuarios (tipo_usuario,descripcion_usuario) VALUES('Administrador','Cargo mas alto');

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



DELIMITER $$

DROP PROCEDURE IF EXISTS insertar_orden_validado;
CREATE PROCEDURE insertar_orden_validado(
    IN p_id_cliente INT,
    IN p_cantidad_comprada INT,
    IN p_id_detalle_producto INT
)
BEGIN
    DECLARE p_precio_producto DECIMAL(5,2);
    DECLARE p_id_pedido INT;
    DECLARE pedido_existente INT;
    DECLARE detalle_existente INT;
    DECLARE mensaje VARCHAR(255);
    DECLARE stock_producto INT;
    DECLARE precio_unitario DECIMAL(5,2);

    -- Obtener el precio unitario del producto y verificar el stock
    SELECT dp.stock_producto, p.precio_unitario 
    INTO stock_producto, precio_unitario
    FROM tb_detalle_productos dp
    JOIN tb_productos p ON dp.id_producto = p.id_producto
    WHERE dp.id_detalle_producto = p_id_detalle_producto;

    -- Verificar si hay suficiente stock
    IF stock_producto < p_cantidad_comprada THEN
        SET mensaje = 'No hay suficiente stock para agregar al carrito.';
        SELECT mensaje;
    END IF;

    -- Calcular el precio total para la cantidad
    SET p_precio_producto = precio_unitario * p_cantidad_comprada;

    -- Verificar si hay un pedido pendiente para el cliente
    SELECT id_pedido INTO pedido_existente 
    FROM tb_pedidos 
    WHERE id_cliente = p_id_cliente AND estado_pedido = 'Pendiente'
    LIMIT 1;

    IF pedido_existente IS NOT NULL THEN
        -- Si hay un pedido pendiente, usar ese ID de pedido
        SET p_id_pedido = pedido_existente;
    ELSE
        -- Si no hay un pedido pendiente, insertar un nuevo pedido
        INSERT INTO tb_pedidos (id_cliente, direccion_pedido)
        VALUES (p_id_cliente, (SELECT direccion_cliente FROM tb_clientes WHERE id_cliente = p_id_cliente)); -- Reemplazar 'Dirección predeterminada' con la dirección real si está disponible

        -- Obtener el ID del nuevo pedido
        SET p_id_pedido = LAST_INSERT_ID();
    END IF;

    -- Verificar si el detalle del pedido ya existe para el mismo producto en el mismo pedido
    SELECT id_detalle_pedido INTO detalle_existente
    FROM tb_detalle_pedidos
    WHERE id_pedido = p_id_pedido AND id_detalle_producto = p_id_detalle_producto
    LIMIT 1;

    IF detalle_existente IS NOT NULL THEN
        -- Si ya existe, actualizar la cantidad y el precio
        UPDATE tb_detalle_pedidos 
        SET cantidad_producto = cantidad_producto + p_cantidad_comprada,
            precio_producto = precio_producto + p_precio_producto
        WHERE id_detalle_pedido = detalle_existente;
        SET mensaje = 'Producto actualizado en el carrito correctamente.';
    ELSE
        -- Si no existe, insertar el detalle del pedido
        INSERT INTO tb_detalle_pedidos (id_pedido, precio_producto, cantidad_producto, id_detalle_producto)
        VALUES (p_id_pedido, p_precio_producto, p_cantidad_comprada, p_id_detalle_producto);
        SET mensaje = 'Producto agregado al carrito correctamente.';
    END IF;

    -- Actualizar el stock en la tabla tb_detalle_productos
    UPDATE tb_detalle_productos
    SET stock_producto = stock_producto - p_cantidad_comprada
    WHERE id_detalle_producto = p_id_detalle_producto;

    SELECT mensaje;
END $$

DELIMITER ;

DELIMITER $$
 
DROP PROCEDURE IF EXISTS actualizar_orden_validado;
CREATE PROCEDURE actualizar_orden_validado(
    IN p_nueva_cantidad INT,
    IN p_id_detalle_pedido INT,
    IN p_id_cliente INT
)
BEGIN
    DECLARE p_cantidad_previa INT;
    DECLARE p_id_detalle_producto INT;
    DECLARE diferencia INT;
    DECLARE p_precio_unitario DECIMAL(5,2);
    -- Obtener la cantidad previa y el ID del producto
    SELECT dp.cantidad_producto, dp.id_detalle_producto INTO p_cantidad_previa, p_id_detalle_producto
    FROM tb_detalle_pedidos dp
    JOIN tb_pedidos p ON dp.id_pedido = p.id_pedido
    JOIN tb_detalle_productos dprod ON dp.id_detalle_producto = dprod.id_detalle_producto
    WHERE dp.id_detalle_pedido = p_id_detalle_pedido
      AND p.id_cliente = p_id_cliente
      AND p.estado_pedido = 'Pendiente'
    LIMIT 1;
    -- Calcular la diferencia
    SET diferencia = p_nueva_cantidad - p_cantidad_previa;
    -- Actualizar la cantidad comprada en tb_detalle_pedidos
    UPDATE tb_detalle_pedidos
    SET cantidad_producto = p_nueva_cantidad
    WHERE id_detalle_pedido = p_id_detalle_pedido;
    -- Ajustar el stock en la tabla tb_detalle_productos
    UPDATE tb_detalle_productos
    SET stock_producto = stock_producto - diferencia
    WHERE id_detalle_producto = p_id_detalle_producto;
END $$
 
DELIMITER ;

DELIMITER $$
 
DROP PROCEDURE IF EXISTS eliminar_orden_validado;
 
CREATE PROCEDURE eliminar_orden_validado(
    IN p_id_detalle_pedido INT,
    IN p_id_cliente INT
)
BEGIN
    DECLARE p_cantidad_previa INT;
    DECLARE p_id_detalle_producto INT;
 
    -- Obtener la cantidad previa y el ID del producto del detalle del pedido a eliminar
    SELECT dp.cantidad_producto, dp.id_detalle_producto INTO p_cantidad_previa, p_id_detalle_producto
    FROM tb_detalle_pedidos dp
    JOIN tb_pedidos p ON dp.id_pedido = p.id_pedido
    WHERE dp.id_detalle_pedido = p_id_detalle_pedido
      AND p.id_cliente = p_id_cliente
      AND p.estado_pedido = 'Pendiente'
    LIMIT 1;
 
    -- Actualizar el stock en la tabla tb_detalle_productos
    UPDATE tb_detalle_productos
    SET stock_producto = stock_producto + p_cantidad_previa
    WHERE id_detalle_producto = p_id_detalle_producto;
 
    -- Eliminar el detalle del pedido
    DELETE FROM tb_detalle_pedidos
    WHERE id_detalle_pedido = p_id_detalle_pedido;
 
    -- Mensaje de confirmación
    SELECT CONCAT('El detalle del pedido con ID ', p_id_detalle_pedido, ' ha sido eliminado y ', p_cantidad_previa, ' unidades han sido devueltas al inventario.') AS mensaje;
END $$
 
DELIMITER ;

INSERT INTO tb_clientes (clave_cliente, nombre_cliente, apellido_cliente, correo_cliente, telefono_cliente, estado_cliente) VALUES
('$2y$10$/8JZKxjAdlo4jTfS7uri7OzhZZ1/yNiw2DCurBt/Tb9h/c7L7P/Y6', 'Juan', 'Pérez', 'juan.perez@example.com', '555-1234', TRUE),
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



INSERT INTO tb_detalle_pedidos (id_pedido, precio_producto, id_detalle_producto, cantidad_producto) VALUES
(1, 19.99, 1, 2),
(2, 29.99, 2, 1),
(3, 15.50, 1, 3),
(4, 45.00, 2, 1),
(5, 10.00, 1, 5);



INSERT INTO tb_detalle_productos (id_producto, id_talla, stock_producto) VALUES
(1, 1, 50),
(2, 1, 30);

INSERT INTO tb_comentarios (comentario, calificacion_producto, fecha_comentario, estado_comentario, id_cliente) VALUES
('Producto excelente, muy recomendado', '5', '2023-05-01 14:30:00', TRUE, 1),
('El producto llegó dañado, no lo recomiendo', '1', '2023-05-02 10:45:00', FALSE, 1),
('Buena relación calidad-precio', '4', '2023-05-03 09:20:00', TRUE, 1),
('El producto es aceptable, pero tardó en llegar', '3', '2023-05-04 13:15:00', TRUE, 1),
('No estoy satisfecho con la compra', '2', '2023-05-05 16:40:00', FALSE, 1);

SELECT id_comentario AS ID, comentario AS COMENTARIO, calificacion_producto AS CALIFICACION, fecha_comentario AS FECHA, nombre_cliente AS CLIENTE,  CASE 
        WHEN estado_comentario = 1 THEN "Activo"
        WHEN estado_comentario = 0 THEN "Bloqueado"
        END AS ESTADO
        FROM tb_comentarios
        INNER JOIN tb_clientes USING(id_cliente)
        ORDER BY COMENTARIO;


SELECT dp.id_detalle_pedido AS ID,
       p.nombre_producto AS NOMBRE,
       dp.cantidad_producto AS CANTIDAD,
       dp.precio_producto AS PRECIO,
       ROUND(dp.precio_producto * dp.cantidad_producto, 2) AS TOTAL
FROM tb_detalle_pedidos dp
JOIN tb_detalle_productos dpd ON dp.id_detalle_producto = dpd.id_detalle_producto
JOIN tb_productos p ON dpd.id_producto = p.id_producto
WHERE dp.id_pedido = (SELECT id_pedido FROM tb_pedidos WHERE id_cliente = 1 AND estado_pedido = 'Pendiente' LIMIT 1);

SELECT * FROM tb_pedidos; SELECT * FROM tb_detalle_pedidos