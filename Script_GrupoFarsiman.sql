-- Crear la base de datos
CREATE DATABASE grupo_farsiman;
GO

-- Seleccionar la base de datos
USE grupo_farsiman;
GO

-- Crear las tablas
CREATE TABLE Colaboradores (
    colaborador_id INT PRIMARY KEY IDENTITY(1,1),
    nombre VARCHAR(100) NOT NULL,
    identidad VARCHAR(15) NOT NULL,
    fecha_nacimiento DATE,
    direccion VARCHAR(255) NOT NULL,
	email VARCHAR(100) UNIQUE,
	celular CHAR(13),
	estado CHAR(1),
	usuario_registro INT NOT NULL,
	fecha_registro DATETIME NOT NULL,
	usuario_actualiza INT,
	fecha_actualiza DATETIME
);

CREATE TABLE Roles (
    rol_id INT PRIMARY KEY IDENTITY(1,1),
    nombre_rol VARCHAR(50) NOT NULL UNIQUE,
	estado CHAR(1),
	usuario_registro INT NOT NULL,
	fecha_registro DATETIME NOT NULL,
	usuario_actualiza INT,
	fecha_actualiza DATETIME
);

CREATE TABLE Modulos (
    modulo_id INT PRIMARY KEY IDENTITY(1,1),
    nombre_modulo VARCHAR(100) NOT NULL UNIQUE, 
    descripcion VARCHAR(255),
	estado CHAR(1),
	usuario_registro INT NOT NULL,
	fecha_registro DATETIME NOT NULL,
	usuario_actualiza INT,
	fecha_actualiza DATETIME
);

CREATE TABLE Pantallas (
    pantalla_id INT PRIMARY KEY IDENTITY(1,1),
    nombre_pantalla VARCHAR(100) NOT NULL UNIQUE,
    modulo_id INT NOT NULL, 
    descripcion VARCHAR(255),
	estado CHAR(1),
	usuario_registro INT NOT NULL,
	fecha_registro DATETIME NOT NULL,
	usuario_actualiza INT,
	fecha_actualiza DATETIME,
	FOREIGN KEY (modulo_id) REFERENCES Modulos(modulo_id)
);

CREATE TABLE Permisos (
    permiso_id INT PRIMARY KEY IDENTITY(1,1),
	usuario VARCHAR(25) NOT NULL,
	pantalla_id INT NOT NULL, 
    rol_id INT NOT NULL, 
    modulo_id INT NOT NULL,   
    FOREIGN KEY (rol_id) REFERENCES Roles(rol_id),
    FOREIGN KEY (modulo_id) REFERENCES Modulos(modulo_id),
	FOREIGN KEY (pantalla_id) REFERENCES Pantallas(pantalla_id),
	estado CHAR(1),
	usuario_registro INT NOT NULL,
	fecha_registro DATETIME NOT NULL
);

CREATE TABLE Usuarios (
    usuario_id INT PRIMARY KEY IDENTITY(1,1),
	usuario VARCHAR(25) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    colaborador_id INT NOT NULL, 
    rol_id INT NOT NULL,  
    FOREIGN KEY (colaborador_id) REFERENCES Colaboradores(colaborador_id),
    FOREIGN KEY (rol_id) REFERENCES Roles(rol_id),
	estado CHAR(1),
	usuario_registro INT NOT NULL,
	fecha_registro DATETIME NOT NULL,
	usuario_actualiza INT,
	fecha_actualiza DATETIME
);

CREATE TABLE Sucursales (
    sucursal_id INT PRIMARY KEY IDENTITY(1,1),
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
	estado CHAR(1),
	usuario_registro INT NOT NULL,
	fecha_registro DATETIME NOT NULL,
	usuario_actualiza INT,
	fecha_actualiza DATETIME
);

CREATE TABLE Colaboradores_Sucursales (
    colaborador_sucursal_id INT PRIMARY KEY IDENTITY(1,1),
    colaborador_id INT NOT NULL,
    sucursal_id INT NOT NULL,
    distancia DECIMAL(5,2) NOT NULL CHECK (distancia > 0 AND distancia <= 50),
    FOREIGN KEY (colaborador_id) REFERENCES Colaboradores(colaborador_id),
    FOREIGN KEY (sucursal_id) REFERENCES Sucursales(sucursal_id),
    UNIQUE (colaborador_id, sucursal_id),
	usuario_registro INT NOT NULL,
	fecha_registro DATETIME NOT NULL,
	usuario_actualiza INT,
	fecha_actualiza DATETIME
);

CREATE TABLE Transportistas (
    transportista_id INT PRIMARY KEY IDENTITY(1,1),
    nombre VARCHAR(100) NOT NULL,
    tarifa_por_km DECIMAL(5,2) NOT NULL,
	estado CHAR(1),
	usuario_registro INT NOT NULL,
	fecha_registro DATETIME NOT NULL,
	usuario_actualiza INT,
	fecha_actualiza DATETIME
);

CREATE TABLE Viajes (
    viaje_id INT PRIMARY KEY IDENTITY(1,1),
    colaborador_id INT NOT NULL, 
    sucursal_id INT NOT NULL, 
    transportista_id INT NOT NULL,  
    fecha_viaje DATE NOT NULL, 
    kilometros_viajados DECIMAL(5,2) NOT NULL CHECK (kilometros_viajados > 0 AND kilometros_viajados <= 100),  
    FOREIGN KEY (colaborador_id) REFERENCES Colaboradores(colaborador_id),
    FOREIGN KEY (sucursal_id) REFERENCES Sucursales(sucursal_id),
    FOREIGN KEY (transportista_id) REFERENCES Transportistas(transportista_id),
    FOREIGN KEY (usuario_registro) REFERENCES Usuarios(usuario_id),
	usuario_registro INT NOT NULL,
	fecha_registro DATETIME NOT NULL,
	usuario_actualiza INT,
	fecha_actualiza DATETIME
);

-- Inserciones de datos
INSERT INTO Colaboradores (nombre, identidad, fecha_nacimiento, direccion, email, celular, estado, usuario_registro, fecha_registro)
VALUES
    ('DARWIN ALBERTO HERRERA HERNANDEZ', '0502-1998-04452', '1998-10-26', 'RESIDENCIAL VILLAVALENCIA CHOLOMA CORTES', 'dherrera@gmail.com', '93110022', 'A', 1, GETDATE()),
    ('GERMAN ZALDIVAR', '0502-1997-02233', '1997-09-05', 'RESIDENCIAL LOS ALAMOS SPS', 'german.zaldivar@gmail.com', '99101238', 'A', 1, GETDATE()),
    ('KEILY NATALY CARDOZA', '0502-1997-07847', '1997-09-05', 'RESIDENCIAL CERRO VERDE, SECTOR LOPEZ ARELLANO', 'keilycardoza@gmail.com', '97251400', 'A', 1, GETDATE());

INSERT INTO Roles (nombre_rol, estado, usuario_registro, fecha_registro)
VALUES
    ('ADMINISTRADOR', 'A', 1, GETDATE()),
    ('GERENTE DE TIENDA', 'A', 1, GETDATE()),
    ('COLABORADOR', 'A', 1, GETDATE());

INSERT INTO Usuarios (usuario, password, colaborador_id, rol_id, estado, usuario_registro, fecha_registro)
VALUES
    ('dherrera', '123', 1, 1, 'A', 1, GETDATE()),
    ('gzaldivar', '123', 2, 2, 'A', 1, GETDATE()),
    ('kcardoza', '123', 3, 1, 'A', 1, GETDATE());

INSERT INTO Modulos (nombre_modulo, descripcion, estado, usuario_registro, fecha_registro)
VALUES
    ('REGISTROS', 'REGISTROS GENERALES', 'A', 1, GETDATE()),
    ('REPORTES', 'REPORTES GENERALES', 'A', 2, GETDATE());

INSERT INTO Pantallas (nombre_pantalla, modulo_id, descripcion, estado, usuario_registro, fecha_registro)
VALUES
    ('ASIGNACIONES', 1, 'REGISTRAR ASIGNACIONES', 'A', 1, GETDATE()),
    ('VIAJES DIARIOS', 1, 'REGISTRAR VIAJES DIARIOS', 'A', 1, GETDATE()),
    ('VIAJES POR TRANSPORTISTA', 2, 'REPORTE DE VIAJES REALIZADOS POR TRANSPORTISTA', 'A', 1, GETDATE());

INSERT INTO Sucursales (nombre, direccion, estado, usuario_registro, fecha_registro)
VALUES
    ('SIMAN 1', 'BO. CENTRO, CHOLOMA, CORTES', 'A', 1, GETDATE()),
    ('SIMAN 2', 'CALLE PRINCIPAL A TIKAMAYA, CHOLOMA, CORTES', 'A', 1, GETDATE()),
    ('SIMAN 3', 'CALLE PRINCIPAL LOPEZ ARELLANO, CHOLOMA, CORTES', 'A', 1, GETDATE());

INSERT INTO Transportistas (nombre, tarifa_por_km, estado, usuario_registro, fecha_registro)
VALUES
    ('JOSE HERRERA', 10, 'A', 1, GETDATE()),
    ('JOSUE ALEMAN', 12, 'A', 1, GETDATE()),
    ('DAYANA GUEVARA', 15, 'A', 1, GETDATE());

INSERT INTO Permisos (pantalla_id, rol_id, modulo_id, estado, usuario_registro, fecha_registro, usuario)
VALUES
    (1, 1, 1, 'A', 1, GETDATE(), 'dherrera'),
	(3, 1, 2, 'A', 1, GETDATE(), 'dherrera'),
	(2, 2, 1, 'A', 1, GETDATE(), 'gzaldivar'),
	(3, 2, 2, 'A', 1, GETDATE(), 'gzaldivar'),
	(1, 1, 1, 'A', 1, GETDATE(), 'kcardoza'),
	(3, 1, 2, 'A', 1, GETDATE(), 'kcardoza');

