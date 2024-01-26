DROP DATABASE IF EXISTS db_pedidos;

CREATE DATABASE db_pedidos;

USE db_pedidos;

CREATE TABLE Usuarios (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
	contraseña VARCHAR(255) NOT NULL,
    rol VARCHAR(50) NOT NULL,
    dni VARCHAR(9) UNIQUE NOT NULL,
    dirección VARCHAR(255) NOT NULL,
    telefono INT(9)
	);
	
CREATE TABLE Pedidos (
	id BIGINT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
	dni VARCHAR(9) NOT NULL,
	dirección VARCHAR(255) NOT NULL,
	precioTotal float,
	fecha DATE,
	id_usuario BIGINT,
    estado VARCHAR(255),
	FOREIGN KEY (id_usuario) REFERENCES Usuarios(id)
	);

CREATE TABLE Categorias (
	id BIGINT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(255) NOT NULL
	);
    
    CREATE TABLE Productos (
	id BIGINT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
	precioUnitario FLOAT NOT NULL,
	imagen VARCHAR(255)
	);

CREATE TABLE LineaPedidos (
	id BIGINT AUTO_INCREMENT PRIMARY KEY,
	cantidad INT,
	precioLinea FLOAT,
	id_pedidos BIGINT,
	id_productos BIGINT,
	FOREIGN KEY (id_Pedidos) REFERENCES Pedidos(id),
	FOREIGN KEY (id_productos) REFERENCES Productos(id)
	);
	
CREATE TABLE ProductosCategorias (
	id_categorias BIGINT,
	id_productos BIGINT NOT NULL,
	PRIMARY KEY (id_categorias, id_productos),
	FOREIGN KEY (id_categorias) REFERENCES Categorias(id),
	FOREIGN KEY (id_productos) REFERENCES Productos(id)
	);
    

	-- USUARIOS --
	INSERT INTO usuarios(nombre, apellidos, email, contraseña, rol, dni, dirección, telefono) VALUES ("Ivan", "Arias", "123@gmail.com", "123", "UsuarioNormal", "123456789", "adsf","123");
	INSERT INTO usuarios(nombre, apellidos, email, contraseña, rol, dni, dirección, telefono) VALUES ("Admin", "Admin", "admin@gmail.com", "123", "Admin", "987654321", "adsf","123");

    -- CATEGORIAS --
    INSERT INTO categorias(nombre) VALUES ("Rollo");
    INSERT INTO categorias(nombre) VALUES ("Box");
    INSERT INTO categorias(nombre) VALUES ("Falafel");
    
    -- PRODUCTOS --
    INSERT INTO productos(nombre, descripcion, precioUnitario, imagen) VALUES ("Ternera", "Ternera, Cebolla, Olivas, Oregáno, Lechuga, Salsa de Yogur", 3.5, " ");
    INSERT INTO productos(nombre, descripcion, precioUnitario, imagen) VALUES ("Pollo", "Pollo, Cebolla, Olivas, Oregáno, Lechuga, Salsa de Yogur", 3.5, " ");
    INSERT INTO productos(nombre, descripcion, precioUnitario, imagen) VALUES ("Mixto", "Pollo, Ternera, Cebolla, Olivas, Oregáno, Lechuga, Salsa de Yogur", 4, " ");
    
    INSERT INTO productos(nombre, descripcion, precioUnitario, imagen) VALUES ("Ternera", "Ternera, Patatas, Arroz Cebolla, Olivas, Oregáno, Lechuga, Salsa de Yogur", 4, " ");
    INSERT INTO productos(nombre, descripcion, precioUnitario, imagen) VALUES ("Pollo", "Pollo, Patatas, Arroz, Cebolla, Olivas, Oregáno, Lechuga, Salsa de Yogur", 4, " ");
    INSERT INTO productos(nombre, descripcion, precioUnitario, imagen) VALUES ("Mixto", "Pollo, Ternera, Patatas, Arroz, Cebolla, Olivas, Oregáno, Lechuga, Salsa de Yogur", 4, " ");
    
    INSERT INTO productos(nombre, descripcion, precioUnitario, imagen) VALUES ("Tradicional", "Garbanzos, Cebolla, Perejil, Cilantro, Pan rayado, Comino, Sal, Pimienta, Ajo picado", 4, " ");
    INSERT INTO productos(nombre, descripcion, precioUnitario, imagen) VALUES ("Vegano", "Garbanzos, Perejil, Ajo picado, Chalotas, Semillas de sésamo, Comino, Sal, Harina", 4, " ");
    
    
    
	-- PRODUCTOS Y CATEGORIAS --
    INSERT INTO productoscategorias(id_categorias, id_productos) VALUES (1,1);
    INSERT INTO productoscategorias(id_categorias, id_productos) VALUES (1,2);
    INSERT INTO productoscategorias(id_categorias, id_productos) VALUES (1,3);
    
    INSERT INTO productoscategorias(id_categorias, id_productos) VALUES (2,4);
    INSERT INTO productoscategorias(id_categorias, id_productos) VALUES (2,5);
    INSERT INTO productoscategorias(id_categorias, id_productos) VALUES (2,6);
    
    INSERT INTO productoscategorias(id_categorias, id_productos) VALUES (3,7);
    INSERT INTO productoscategorias(id_categorias, id_productos) VALUES (3,8);