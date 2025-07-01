-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-07-2025 a las 02:25:13
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestionbiblioteca`
--
CREATE DATABASE IF NOT EXISTS `gestionbiblioteca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gestionbiblioteca`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `alquilarCubiculo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `alquilarCubiculo` (IN `lectorID` INT, IN `cubiculoID` INT)   BEGIN
  -- Insertar el alquiler
  INSERT INTO alquilercubiculo (tiempo, lectorID, cubiculoID, fecha_inicio)
  VALUES ('1h', lectorID, cubiculoID, NOW());

  -- Actualizar la disponibilidad del cubículo a '2' (ocupado)
  UPDATE cubiculo
  SET disponibilidadID = 2
  WHERE cubiculo.cubiculoID = cubiculoID;
END$$

DROP PROCEDURE IF EXISTS `buscarExistencias`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `buscarExistencias` (IN `campo` VARCHAR(50), IN `valor` VARCHAR(255))   BEGIN
    IF campo = 'titulo' THEN
        SELECT e.existenciaID, l.libroID, l.title, 
               CONCAT(u.section, '-', u.aisle, '-', u.shelving, '-', u.level) AS ubicacion,
               ee.status AS estado,
               d.status AS disponibilidad
        FROM existenciaLibro e
        INNER JOIN libro l ON e.libroID = l.libroID
        INNER JOIN ubicacion u ON e.ubicacionID = u.ubicacionID
        INNER JOIN estadoExistencia ee ON e.estadoExistenciaID = ee.statusExistenceID
        INNER JOIN disponibilidadExistencia d ON e.disponibilidadExistenciaID = d.disponibilidadID
        WHERE l.title LIKE CONCAT('%', valor, '%');
    ELSEIF campo = 'existenciaID' THEN
        SELECT e.existenciaID, l.libroID, l.title, 
               CONCAT(u.section, '-', u.aisle, '-', u.shelving, '-', u.level) AS ubicacion,
               ee.status AS estado,
               d.status AS disponibilidad
        FROM existenciaLibro e
        INNER JOIN libro l ON e.libroID = l.libroID
        INNER JOIN ubicacion u ON e.ubicacionID = u.ubicacionID
        INNER JOIN estadoExistencia ee ON e.estadoExistenciaID = ee.statusExistenceID
        INNER JOIN disponibilidadExistencia d ON e.disponibilidadExistenciaID = d.disponibilidadID
        WHERE e.existenciaID = valor;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `devolverCubiculo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `devolverCubiculo` (IN `cubiculoIDInput` INT)   BEGIN
  -- Declarar variables locales
  DECLARE alquilerID INT;
  DECLARE horasOcupado INT;

  -- Obtener el ID del alquiler activo
  SELECT alquilercubiculoID
  INTO alquilerID
  FROM alquilercubiculo
  WHERE cubiculoID = cubiculoIDInput AND fecha_fin IS NULL
  LIMIT 1;

  -- Calcular las horas entre fecha_inicio y ahora
  SELECT TIMESTAMPDIFF(HOUR, fecha_inicio, NOW())
  INTO horasOcupado
  FROM alquilercubiculo
  WHERE alquilercubiculoID = alquilerID;

  -- Actualizar el registro del alquiler con fecha_fin y tiempo
  UPDATE alquilercubiculo
  SET fecha_fin = NOW(),
      tiempo = CONCAT(horasOcupado, 'h')
  WHERE alquilercubiculoID = alquilerID;

  -- Actualizar disponibilidad del cubículo a 1 (Disponible)
  UPDATE cubiculo
  SET disponibilidadID = 1
  WHERE cubiculoID = cubiculoIDInput;
END$$

DROP PROCEDURE IF EXISTS `insertarExistencia`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertarExistencia` (IN `p_libroID` INT, IN `p_ubicacionID` INT, IN `p_estadoExistenciaID` INT, IN `p_disponibilidadExistenciaID` INT)   BEGIN
    INSERT INTO existenciaLibro (libroID, ubicacionID, estadoExistenciaID, disponibilidadExistenciaID)
    VALUES (p_libroID, p_ubicacionID, p_estadoExistenciaID, p_disponibilidadExistenciaID);
END$$

DROP PROCEDURE IF EXISTS `listarCubiculos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `listarCubiculos` ()   BEGIN
  SELECT 
    c.*,
    CASE 
      WHEN c.disponibilidadID = 1 THEN 1
      WHEN c.disponibilidadID = 2 THEN 0
      ELSE NULL
    END AS disponible
  FROM cubiculo c
  WHERE c.disponibilidadID IN (1, 2);
END$$

DROP PROCEDURE IF EXISTS `obtenerDisponibilidades`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerDisponibilidades` ()   BEGIN
    SELECT * FROM disponibilidadExistencia;
END$$

DROP PROCEDURE IF EXISTS `obtenerEstadosExistencia`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerEstadosExistencia` ()   BEGIN
    SELECT * FROM estadoExistencia;
END$$

DROP PROCEDURE IF EXISTS `obtenerTodasExistencias`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerTodasExistencias` ()   BEGIN
    SELECT e.existenciaID, l.libroID, l.title, 
           CONCAT(u.section, '-', u.aisle, '-', u.shelving, '-', u.level) AS ubicacion,
           ee.status AS estado,
           d.status AS disponibilidad
    FROM existenciaLibro e
    INNER JOIN libro l ON e.libroID = l.libroID
    INNER JOIN ubicacion u ON e.ubicacionID = u.ubicacionID
    INNER JOIN estadoExistencia ee ON e.estadoExistenciaID = ee.statusExistenceID
    INNER JOIN disponibilidadExistencia d ON e.disponibilidadExistenciaID = d.disponibilidadID;
END$$

DROP PROCEDURE IF EXISTS `obtenerUbicaciones`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerUbicaciones` ()   BEGIN
    SELECT * FROM ubicacion;
END$$

DROP PROCEDURE IF EXISTS `registrarCubiculo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarCubiculo` (IN `nombre` VARCHAR(100), IN `equipamiento` TEXT, IN `capacidad` INT)   BEGIN
  INSERT INTO cubiculo(nombre, equipamento, capacidad, disponibilidadID) VALUES (nombre, equipamiento, capacidad, 1);
END$$

DROP PROCEDURE IF EXISTS `sp_actualizar_existencia`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_actualizar_existencia` (IN `p_existenciaID` INT, IN `p_libroID` INT, IN `p_ubicacionID` INT, IN `p_estadoExistenciaID` INT, IN `p_disponibilidadExistenciaID` INT)   BEGIN
    UPDATE existenciaLibro
    SET
        libroID = p_libroID,
        ubicacionID = p_ubicacionID,
        estadoExistenciaID = p_estadoExistenciaID,
        disponibilidadExistenciaID = p_disponibilidadExistenciaID
    WHERE existenciaID = p_existenciaID;
END$$

DROP PROCEDURE IF EXISTS `sp_bloquear_lector`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_bloquear_lector` (IN `lectorID_input` INT)   BEGIN
    UPDATE lector
    SET estadoLectorID = 2 -- Bloqueado
    WHERE lectorID = lectorID_input;

    SELECT ROW_COUNT() AS filasAfectadas;
END$$

DROP PROCEDURE IF EXISTS `sp_buscar_bibliotecarios`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_bibliotecarios` (IN `p_campo` VARCHAR(20), IN `p_valor` VARCHAR(255))   BEGIN
    -- Validar parámetros de entrada
    IF p_campo NOT IN ('nombre', 'apellido', 'email', 'usuario', 'id') THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Campo de búsqueda no válido. Use: nombre, apellido, email, usuario o id';
    END IF;
    
    -- Búsqueda por nombre
    IF p_campo = 'nombre' THEN
        SELECT bibliotecarioID, nombre, apellido, email, usuario, rolID
        FROM bibliotecario
        WHERE rolID = 2 
        AND nombre LIKE CONCAT('%', p_valor, '%');
    
    -- Búsqueda por apellido
    ELSEIF p_campo = 'apellido' THEN
        SELECT bibliotecarioID, nombre, apellido, email, usuario, rolID
        FROM bibliotecario
        WHERE rolID = 2 
        AND apellido LIKE CONCAT('%', p_valor, '%');
    
    -- Búsqueda por email
    ELSEIF p_campo = 'email' THEN
        SELECT bibliotecarioID, nombre, apellido, email, usuario, rolID
        FROM bibliotecario
        WHERE rolID = 2 
        AND email LIKE CONCAT('%', p_valor, '%');
    
    -- Búsqueda por usuario
    ELSEIF p_campo = 'usuario' THEN
        SELECT bibliotecarioID, nombre, apellido, email, usuario, rolID
        FROM bibliotecario
        WHERE rolID = 2 
        AND usuario LIKE CONCAT('%', p_valor, '%');
    
    -- Búsqueda por ID (numérica)
    ELSE
        SELECT bibliotecarioID, nombre, apellido, email, usuario, rolID, bloqueado
        FROM bibliotecario
        WHERE rolID = 2 
        AND bloqueado = 0
        AND bibliotecarioID = CAST(p_valor AS UNSIGNED);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_buscar_existencias_libro`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_existencias_libro` (IN `p_busqueda` VARCHAR(255))   BEGIN
    SELECT 
        e.existenciaID,
        u.section AS Seccion,
        u.aisle AS Pasillo,
        u.shelving AS Estanteria,
        u.level AS Nivel,
        es.status AS EstadoExistencia,
        de.status AS Disponibilidad,
        l.libroID,
        l.title AS TituloLibro  -- Cambiado de l.titulo a l.title
    FROM 
        existencialibro e
    JOIN 
        libro l ON e.libroID = l.libroID
    JOIN 
        ubicacion u ON e.ubicacionID = u.ubicacionID
    JOIN 
        estadoexistencia es ON e.estadoExistenciaID = es.statusExistenceID
    JOIN 
        disponibilidadexistencia de ON e.disponibilidadExistenciaID = de.disponibilidadID
    WHERE 
        (l.title LIKE CONCAT('%', p_busqueda, '%') OR  -- Cambiado de l.titulo a l.title
        l.libroID = IF(p_busqueda REGEXP '^[0-9]+$', CAST(p_busqueda AS UNSIGNED), NULL))
        AND de.status = 'Disponible'
    ORDER BY 
        l.title, e.existenciaID;  -- Cambiado de l.titulo a l.title
END$$

DROP PROCEDURE IF EXISTS `sp_buscar_lector`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_lector` (IN `p_cedula` VARCHAR(20))   BEGIN
    SELECT 
        lectorID,
        nombre,
        email
    FROM 
        lector
    WHERE 
        cedula = p_cedula
    AND estadolectorID = 1;
END$$

DROP PROCEDURE IF EXISTS `sp_buscar_lectores`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_lectores` (IN `campo_busqueda` VARCHAR(20), IN `valor_busqueda` VARCHAR(255))   BEGIN
    -- Validar parámetros de entrada
    IF campo_busqueda NOT IN ('nombre', 'apellido', 'cedula', 'email', 'id') THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Campo de búsqueda no válido. Use: nombre, apellido, cedula, email o id';
    END IF;
    
    -- Búsqueda por nombre
    IF campo_busqueda = 'nombre' THEN
        SELECT lectorID, nombre, apellido, cedula, email, telefono, direccion, estadoLectorID
        FROM lector
        WHERE nombre LIKE CONCAT('%', valor_busqueda, '%');
    
    -- Búsqueda por apellido
    ELSEIF campo_busqueda = 'apellido' THEN
        SELECT lectorID, nombre, apellido, cedula, email, telefono, direccion, estadoLectorID
        FROM lector
        WHERE apellido LIKE CONCAT('%', valor_busqueda, '%');
    
    -- Búsqueda por cédula
    ELSEIF campo_busqueda = 'cedula' THEN
        SELECT lectorID, nombre, apellido, cedula, email, telefono, direccion, estadoLectorID
        FROM lector
        WHERE cedula LIKE CONCAT('%', valor_busqueda, '%');
    
    -- Búsqueda por email
    ELSEIF campo_busqueda = 'email' THEN
        SELECT lectorID, nombre, apellido, cedula, email, telefono, direccion, estadoLectorID
        FROM lector
        WHERE email LIKE CONCAT('%', valor_busqueda, '%');
    
    -- Búsqueda por ID (numérica)
    ELSE
        SELECT lectorID, nombre, apellido, cedula, email, telefono, direccion, estadoLectorID
        FROM lector
        WHERE lectorID = CAST(valor_busqueda AS UNSIGNED);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_buscar_libros`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_libros` (IN `campo_busqueda` VARCHAR(20), IN `valor_busqueda` VARCHAR(100))   BEGIN
    -- Validar parámetros
    IF campo_busqueda NOT IN ('titulo', 'autor', 'id') THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Campo de búsqueda no válido';
    END IF;
    
    -- Búsqueda por título
    IF campo_busqueda = 'titulo' THEN
        SELECT 
            l.libroID, l.title, l.author, l.year, 
            l.pages_no, g.gender, l.estado
        FROM libro l
        INNER JOIN genero g ON l.genderID = g.generoID
        WHERE l.title LIKE CONCAT('%', valor_busqueda, '%') COLLATE utf8mb4_general_ci;
    
    -- Búsqueda por autor
    ELSEIF campo_busqueda = 'autor' THEN
        SELECT 
            l.libroID, l.title, l.author, l.year, 
            l.pages_no, g.gender, l.estado
        FROM libro l
        INNER JOIN genero g ON l.genderID = g.generoID
        WHERE l.author LIKE CONCAT('%', valor_busqueda, '%') COLLATE utf8mb4_general_ci;
    
    -- Búsqueda por ID (numérica)
    ELSE
        SELECT 
            l.libroID, l.title, l.author, l.year, 
            l.pages_no, g.gender, l.estado
        FROM libro l
        INNER JOIN genero g ON l.genderID = g.generoID
        WHERE l.libroID = CAST(valor_busqueda AS UNSIGNED);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_buscar_multa`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_multa` (IN `cedula_input` VARCHAR(20))   BEGIN
    -- Información del lector
    SELECT 
        l.lectorID,
        l.nombre,
        l.apellido,
        l.cedula,
        l.email,
        l.telefono,
        l.direccion,
        CASE 
            WHEN l.estadoLectorID = 2 THEN 'Bloqueado' 
            ELSE 'Disponible' 
        END AS estadoLector
    FROM lector l
    WHERE l.cedula = cedula_input;

    -- Multas del lector
    SELECT 
        m.multaID,
        m.monto,
        m.motivo,
        DATE_FORMAT(m.fecha_emision, '%d/%m/%Y') AS fecha_emision,
        CASE 
            WHEN m.estadomultaID = 1 THEN 'Pagada' 
            ELSE 'No pagado' 
        END AS estadoMulta,
        m.lectorID,
        m.prestamoID
    FROM multa m
    INNER JOIN lector l ON m.lectorID = l.lectorID
    WHERE l.cedula = cedula_input
    ORDER BY m.fecha_emision DESC;
END$$

DROP PROCEDURE IF EXISTS `sp_buscar_prestamos_por_cedula`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_prestamos_por_cedula` (IN `p_cedula` VARCHAR(20))   BEGIN
    SELECT 
        p.prestamoID,
        p.fecha_prestamo,
        p.fecha_devolucion,
        p.fecha_finalizacion,
        p.estadoprestamoID,
        e.estado,
        l.nombre,
        l.apellido,
        l.cedula,
        lr.existenciaID
    FROM prestamo p
    INNER JOIN lector l ON p.lectorID = l.lectorID
    INNER JOIN estadoprestamo e ON p.estadoprestamoID = e.estadoprestamoID
    LEFT JOIN  -- Usamos LEFT JOIN para incluir préstamos aunque no tengan registro en listarreserva
        listarreservas lr ON p.prestamoID = lr.prestamoID
    WHERE l.cedula = p_cedula;
END$$

DROP PROCEDURE IF EXISTS `sp_buscar_prestamos_por_nombre_apellido`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_prestamos_por_nombre_apellido` (IN `p_nombre_completo` VARCHAR(100))   BEGIN
    SELECT 
        p.prestamoID,
        p.fecha_prestamo,
        p.fecha_devolucion,
        p.fecha_finalizacion,
        p.estadoprestamoID,
        e.estado,
        l.nombre,
        l.apellido,
        l.cedula,
        lr.existenciaID
    FROM prestamo p
    INNER JOIN lector l ON p.lectorID = l.lectorID
    INNER JOIN estadoprestamo e ON p.estadoprestamoID = e.estadoprestamoID
    LEFT JOIN  -- Usamos LEFT JOIN para incluir préstamos aunque no tengan registro en listarreserva
        listarreservas lr ON p.prestamoID = lr.prestamoID
    WHERE CONCAT(l.nombre, ' ', l.apellido) LIKE p_nombre_completo;
END$$

DROP PROCEDURE IF EXISTS `sp_cancelar_multa`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cancelar_multa` (IN `multaID_input` INT)   BEGIN
    UPDATE multa
    SET estadomultaID = 1 -- Pagada
    WHERE multaID = multaID_input;

    SELECT ROW_COUNT() AS filasAfectadas;
END$$

DROP PROCEDURE IF EXISTS `sp_desactivar_lector`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_desactivar_lector` (IN `p_lectorID` INT)   BEGIN
    UPDATE lector
    SET estadoLectorID = 2 -- 2 = Inactivo
    WHERE lectorID = p_lectorID;
END$$

DROP PROCEDURE IF EXISTS `sp_desbloquear_lector`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_desbloquear_lector` (IN `lectorID_input` INT)   BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM multa 
        WHERE lectorID = lectorID_input AND estadomultaID = 2
    ) THEN
        UPDATE lector
        SET estadoLectorID = 1 -- Disponible
        WHERE lectorID = lectorID_input;

        SELECT 1 AS exito, 'Lector desbloqueado' AS mensaje;
    ELSE
        SELECT 0 AS exito, 'El lector tiene multas pendientes' AS mensaje;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_eliminar_bibliotecario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminar_bibliotecario` (IN `p_bibliotecarioID` INT)   BEGIN
    UPDATE bibliotecario
    SET bloqueado = 1 -- 1 = Inactivo
    WHERE bibliotecarioID = p_bibliotecarioID AND rolID = 2;
END$$

DROP PROCEDURE IF EXISTS `sp_eliminar_libro`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminar_libro` (IN `p_libroID` INT)   BEGIN
    UPDATE libro SET estado = 0 WHERE libroID = p_libroID;
END$$

DROP PROCEDURE IF EXISTS `sp_habilitar_libro`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_habilitar_libro` (IN `p_libroID` INT, IN `p_estado` INT)   BEGIN
  UPDATE libro SET estado = p_estado WHERE libroID = p_libroID;
END$$

DROP PROCEDURE IF EXISTS `sp_insertarBibliotecario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertarBibliotecario` (IN `p_name` VARCHAR(50), IN `p_lastName` VARCHAR(50), IN `p_email` VARCHAR(100), IN `p_user` VARCHAR(50), IN `p_password` VARCHAR(100))   BEGIN
  INSERT INTO bibliotecario (name, lastName, email, user, password, rolID)
  VALUES (p_name, p_lastName, p_email, p_user, p_password, 2);
END$$

DROP PROCEDURE IF EXISTS `sp_insertar_bibliotecario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertar_bibliotecario` (IN `p_nombre` VARCHAR(30), IN `p_apellido` VARCHAR(30), IN `p_email` VARCHAR(30), IN `p_usuario` VARCHAR(15), IN `p_contrasenia` VARCHAR(15))   BEGIN
    INSERT INTO bibliotecario (nombre, apellido, email, usuario, contrasenia, rolID)
    VALUES (p_nombre, p_apellido, p_email, p_usuario, p_contrasenia, 2);
END$$

DROP PROCEDURE IF EXISTS `sp_insertar_lector`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertar_lector` (IN `p_nombre` VARCHAR(50), IN `p_apellido` VARCHAR(50), IN `p_cedula` VARCHAR(20), IN `p_email` VARCHAR(100), IN `p_telefono` VARCHAR(20), IN `p_direccion` TEXT)   BEGIN
    INSERT INTO lector (nombre, apellido, cedula, email, telefono, direccion, estadoLectorID)
    VALUES (p_nombre, p_apellido, p_cedula, p_email, p_telefono, p_direccion, 1); -- 1 = Activo
END$$

DROP PROCEDURE IF EXISTS `sp_insertar_libro`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertar_libro` (IN `p_title` VARCHAR(255), IN `p_author` VARCHAR(255), IN `p_year` INT, IN `p_pages_no` INT, IN `p_genderID` VARCHAR(100))   BEGIN
  INSERT INTO libro (title, author, year, pages_no, genderID)
  VALUES (p_title, p_author, p_year, p_pages_no, p_genderID);
END$$

DROP PROCEDURE IF EXISTS `sp_insertar_prestamo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertar_prestamo` (IN `p_fecha_prestamo` DATE, IN `p_fecha_devolucion` DATE, IN `p_lectorID` INT, OUT `p_prestamoID` INT)   BEGIN
    INSERT INTO prestamo (fecha_prestamo, fecha_devolucion, estadoprestamoID, lectorID)
    VALUES (p_fecha_prestamo, p_fecha_devolucion, 1, p_lectorID);
    
    -- Obtiene el último ID insertado
    SET p_prestamoID = LAST_INSERT_ID();
END$$

DROP PROCEDURE IF EXISTS `sp_insertar_prestamo_y_reserva`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertar_prestamo_y_reserva` (IN `p_fecha_prestamo` DATE, IN `p_fecha_devolucion` DATE, IN `p_lectorID` INT, IN `p_existenciaID` INT)   BEGIN
    -- Insertar el préstamo
    INSERT INTO prestamo (fecha_prestamo, fecha_devolucion, estadoprestamoID, lectorID)
    VALUES (p_fecha_prestamo, p_fecha_devolucion, 1, p_lectorID);

    -- Obtener el último ID generado
    SET @prestamoID = LAST_INSERT_ID();

    -- Insertar la reserva vinculada
    INSERT INTO listarreservas (prestamoID, existenciaID)
    VALUES (@prestamoID, p_existenciaID);
END$$

DROP PROCEDURE IF EXISTS `sp_insertar_reserva`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertar_reserva` (IN `p_prestamoID` INT, IN `p_existenciaID` INT)   BEGIN
    INSERT INTO listarreservas (prestamoID, existenciaID)
    VALUES (p_prestamoID, p_existenciaID);
END$$

DROP PROCEDURE IF EXISTS `sp_listar_generos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_listar_generos` ()   BEGIN
    SELECT genderID, name FROM genero;
END$$

DROP PROCEDURE IF EXISTS `sp_modificar_bibliotecario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_modificar_bibliotecario` (IN `p_bibliotecarioID` INT, IN `p_nombre` VARCHAR(30), IN `p_apellido` VARCHAR(30), IN `p_email` VARCHAR(30), IN `p_usuario` VARCHAR(15), IN `p_contrasenia` VARCHAR(15))   BEGIN
    UPDATE bibliotecario
    SET nombre = p_nombre,
        apellido = p_apellido,
        email = p_email,
        usuario = p_usuario,
        contrasenia = IF(p_contrasenia = '', contrasenia, p_contrasenia)
    WHERE bibliotecarioID = p_bibliotecarioID;
END$$

DROP PROCEDURE IF EXISTS `sp_modificar_lector`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_modificar_lector` (IN `p_lectorID` INT, IN `p_nombre` VARCHAR(50), IN `p_apellido` VARCHAR(50), IN `p_cedula` VARCHAR(20), IN `p_email` VARCHAR(100), IN `p_telefono` VARCHAR(20), IN `p_direccion` TEXT)   BEGIN
    UPDATE lector
    SET nombre = p_nombre,
        apellido = p_apellido,
        cedula = p_cedula,
        email = p_email,
        telefono = p_telefono,
        direccion = p_direccion
    WHERE lectorID = p_lectorID;
END$$

DROP PROCEDURE IF EXISTS `sp_modificar_libro`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_modificar_libro` (IN `p_libroID` INT, IN `p_title` VARCHAR(50), IN `p_author` VARCHAR(60), IN `p_year` INT, IN `p_pages` INT, IN `p_genderID` INT)   BEGIN
    UPDATE libro 
    SET title = p_title, author = p_author, year = p_year, pages_no = p_pages, genderID = p_genderID
    WHERE libroID = p_libroID;
END$$

DROP PROCEDURE IF EXISTS `sp_obtener_bibliotecarios`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_bibliotecarios` ()   BEGIN
    SELECT bibliotecarioID, nombre, apellido, email, usuario, rolID, bloqueado 
    FROM bibliotecario
    WHERE rolID = 2
    AND bloqueado = 0;
END$$

DROP PROCEDURE IF EXISTS `sp_obtener_lectores`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_lectores` ()   BEGIN
    SELECT lectorID, nombre, apellido, cedula, email, telefono, direccion, estadoLectorID
    FROM lector
    WHERE estadoLectorID = 1;
END$$

DROP PROCEDURE IF EXISTS `sp_obtener_lectores_inactivos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_lectores_inactivos` ()   BEGIN
    SELECT lectorID, nombre, apellido, cedula, email, telefono, direccion, estadoLectorID
    FROM lector
    WHERE estadoLectorID = 2; -- 2 = Inactivo
END$$

DROP PROCEDURE IF EXISTS `SP_obtener_libros`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_obtener_libros` ()   BEGIN
    SELECT 
        l.libroID,
        l.title,
        l.author,
        l.year,
        l.pages_no,
        g.gender,
        l.estado  -- Se añade esta columna
    FROM 
        libro l
    INNER JOIN 
        genero g ON l.genderID = g.generoID
    WHERE 
        l.estado = 1; -- Solo libros habilitados
END$$

DROP PROCEDURE IF EXISTS `sp_obtener_libros_deshabilitados`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_libros_deshabilitados` ()   BEGIN
    SELECT 
        l.libroID,
        l.title,
        l.author,
        l.year,
        l.pages_no,
        g.gender,
        l.estado
    FROM libro l
    INNER JOIN genero g ON l.genderID = g.generoID
    WHERE l.estado = 0;
END$$

DROP PROCEDURE IF EXISTS `sp_obtener_libros_habilitados`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_libros_habilitados` ()   BEGIN
    SELECT * FROM libro WHERE estado = 1;  -- Asumiendo que '1' es el estado habilitado
END$$

DROP PROCEDURE IF EXISTS `sp_obtener_prestamos`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_prestamos` ()   BEGIN
    SELECT 
        p.prestamoID,
        p.fecha_prestamo,
        p.fecha_finalizacion,
        p.fecha_devolucion,
        CASE 
            WHEN p.estadoprestamoID = 1 THEN 'Activo'
            WHEN p.estadoprestamoID = 2 THEN 'Completado'
            WHEN p.estadoprestamoID = 3 THEN 'Atrasado'
            ELSE 'Desconocido'
        END AS estado,
        l.nombre,
        l.apellido,
        l.cedula,
        lr.existenciaID  -- ¡Nuevo campo agregado!
    FROM 
        prestamo p
    JOIN 
        lector l ON p.lectorID = l.lectorID
    LEFT JOIN  -- Usamos LEFT JOIN para incluir préstamos aunque no tengan registro en listarreserva
        listarreservas lr ON p.prestamoID = lr.prestamoID
    ORDER BY 
        p.fecha_prestamo DESC;
END$$

DROP PROCEDURE IF EXISTS `sp_reactivar_lector`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reactivar_lector` (IN `p_lectorID` INT)   BEGIN
    UPDATE lector
    SET estadoLectorID = 1 -- 1 = Activo
    WHERE lectorID = p_lectorID;
END$$

DROP PROCEDURE IF EXISTS `sp_registrar_devolucion`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_registrar_devolucion` (IN `p_prestamoID` INT, IN `p_estadoExistenciaID` INT, IN `p_disponibilidadID` INT, IN `p_motivo_multa` VARCHAR(255))   BEGIN
    DECLARE v_lectorID INT;
    DECLARE v_libroID INT;
    DECLARE v_existenciaID INT;
    DECLARE v_monto_multa DECIMAL(10,2) DEFAULT 10.00;
    DECLARE v_error_msg VARCHAR(255);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1 v_error_msg = MESSAGE_TEXT;
        ROLLBACK;
        SET v_error_msg = CONCAT('Error en transacción: ', v_error_msg);
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_error_msg;
    END;
    
    -- Validación de parámetros
    IF p_prestamoID IS NULL OR p_estadoExistenciaID IS NULL OR p_disponibilidadID IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Parámetros requeridos no pueden ser nulos';
    END IF;
    
    START TRANSACTION;
    
    -- 1. Obtener existenciaID de listarreservas
    SELECT existenciaID INTO v_existenciaID 
    FROM listarreservas 
    WHERE prestamoID = p_prestamoID
    LIMIT 1
    FOR UPDATE;
    
    IF v_existenciaID IS NULL THEN
        SET v_error_msg = 'No se encontró reserva para el préstamo ID';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_error_msg;
    END IF;
    
    
    
    -- 2. Verificar y obtener información del préstamo
    SELECT lectorID INTO v_lectorID 
    FROM prestamo 
    WHERE prestamoID = p_prestamoID
    FOR UPDATE;
    
    IF v_lectorID IS NULL THEN
        SET v_error_msg = 'Préstamo no encontrado con ID';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_error_msg;
    END IF;
    
    -- 3. Verificar y obtener información del libro
    SELECT libroID INTO v_libroID 
    FROM existencialibro 
    WHERE existenciaID = v_existenciaID
    FOR UPDATE;
    
    IF v_libroID IS NULL THEN
        SET v_error_msg = 'Existencia de libro no encontrada con ID';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_error_msg;
    END IF;
    
    -- 4. Registrar multa si aplica
    IF p_estadoExistenciaID = 1 THEN
        IF p_motivo_multa IS NULL OR p_motivo_multa = '' THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Se requiere motivo para libros dañados';
        END IF;
        
        INSERT INTO multa (
            monto, 
            motivo, 
            fecha_emision, 
            estadomultaID, 
            lectorID, 
            prestamoID
        ) VALUES (
            v_monto_multa,
            p_motivo_multa,
            NOW(),
            2, -- ID para estado "Pendiente"
            v_lectorID,
            p_prestamoID
        );
        
        -- Verificar inserción
        IF ROW_COUNT() = 0 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al registrar multa';
        END IF;
    END IF;
    
    -- 5. Actualizar estado del libro
    UPDATE existencialibro 
    SET 
        estadoExistenciaID = p_estadoExistenciaID,
        disponibilidadExistenciaID = p_disponibilidadID
    WHERE existenciaID = v_existenciaID;
    
    IF ROW_COUNT() = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al actualizar estado del libro';
    END IF;
    
    -- 6. Registrar devolución
    UPDATE prestamo 
    SET 
        fecha_devolucion = NOW(),
        estadoprestamoID = 2, -- ID para estado "Completado"
        fecha_finalizacion = NOW()
    WHERE prestamoID = p_prestamoID;
    
    IF ROW_COUNT() = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al registrar devolución';
    END IF;
    
    COMMIT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alquilercubiculo`
--

DROP TABLE IF EXISTS `alquilercubiculo`;
CREATE TABLE IF NOT EXISTS `alquilercubiculo` (
  `alquilercubiculoID` int(11) NOT NULL AUTO_INCREMENT,
  `tiempo` varchar(50) NOT NULL,
  `lectorID` int(11) NOT NULL,
  `cubiculoID` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  PRIMARY KEY (`alquilercubiculoID`),
  KEY `cubiculoID` (`cubiculoID`),
  KEY `lectorID` (`lectorID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alquilercubiculo`
--

INSERT INTO `alquilercubiculo` (`alquilercubiculoID`, `tiempo`, `lectorID`, `cubiculoID`, `fecha_inicio`, `fecha_fin`) VALUES
(1, '36h', 1, 1, '2025-06-10', '2025-06-11'),
(2, '36h', 2, 2, '2025-06-10', '2025-06-11'),
(3, '13h', 1, 1, '2025-06-11', '2025-06-11'),
(4, '21h', 1, 1, '2025-06-11', '2025-06-11'),
(5, '12h', 2, 9, '2025-06-13', '2025-06-13'),
(6, '14h', 2, 1, '2025-06-13', '2025-06-13'),
(7, '87h', 2, 1, '2025-06-13', '2025-06-16'),
(8, '15h', 1, 2, '2025-06-16', '2025-06-16'),
(9, '15h', 4, 9, '2025-06-19', '2025-06-19'),
(10, '14h', 2, 1, '2025-06-27', '2025-06-27'),
(11, '23h', 5, 1, '2025-06-29', '2025-06-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bibliotecario`
--

DROP TABLE IF EXISTS `bibliotecario`;
CREATE TABLE IF NOT EXISTS `bibliotecario` (
  `bibliotecarioID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `usuario` varchar(15) NOT NULL,
  `contrasenia` varchar(15) NOT NULL,
  `rolID` int(11) NOT NULL,
  `bloqueado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`bibliotecarioID`),
  KEY `rolID` (`rolID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bibliotecario`
--

INSERT INTO `bibliotecario` (`bibliotecarioID`, `nombre`, `apellido`, `email`, `usuario`, `contrasenia`, `rolID`, `bloqueado`) VALUES
(2, 'juan ernesto', 'riquelme mera', 'juanerne@mail.com', 'juaneree', '123456', 2, 0),
(3, 'Jeremy David', 'Soto Monar', 'jeremymon@mail.com', 'jedoso', '422463', 2, 0),
(4, 'Maria Elisa', 'Estrella Dueñas', 'melisa@mail.com', 'melissa1', '$2y$10$kwxK.4s0', 2, 0),
(5, 'Didier Joel', 'Frias Mayor', 'didi123@mmail.com', 'jolo45', '$2y$10$LQ7dGN5e', 2, 0),
(6, 'Diego Alejandro', 'Farias Mite', 'Fmite@mail.com', 'diego12', '$2y$10$f9cyUEMR', 2, 1),
(11, 'asd', 'asd', 'jeremymonar@mail.com', 'jedoso|', '123', 2, 1),
(12, 'Ignacio Fernando', 'Jara Reyes', 'ignafer12@mail.com', 'ferjara12', '123', 2, 0),
(13, 'fermin', 'lopez', 'flopez@mail.com', 'ferrcho', '1234', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante`
--

DROP TABLE IF EXISTS `comprobante`;
CREATE TABLE IF NOT EXISTS `comprobante` (
  `comprobanteID` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_emision` date NOT NULL,
  `prestamoID` int(11) NOT NULL,
  `bibliotecarioID` int(11) NOT NULL,
  PRIMARY KEY (`comprobanteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cubiculo`
--

DROP TABLE IF EXISTS `cubiculo`;
CREATE TABLE IF NOT EXISTS `cubiculo` (
  `cubiculoID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(10) NOT NULL,
  `equipamento` varchar(100) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `disponibilidadID` int(11) NOT NULL,
  PRIMARY KEY (`cubiculoID`),
  KEY `disponibilidadID` (`disponibilidadID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cubiculo`
--

INSERT INTO `cubiculo` (`cubiculoID`, `nombre`, `equipamento`, `capacidad`, `disponibilidadID`) VALUES
(1, 'cubiculo 1', 'Computadora', 1, 1),
(2, 'Cubiculo 2', 'Copiadora, Computadora', 2, 1),
(3, 'Cubiculo 3', 'Computadora', 1, 1),
(4, 'Cubiculo 4', 'Copiadora', 2, 1),
(5, 'Cubiculo 5', 'Scanner', 2, 1),
(6, 'Cubiculo 6', 'Lampara', 2, 1),
(7, 'Cubiculo 7', 'Laptop', 2, 1),
(8, 'Cubiculo 8', 'Computadora, Scanner', 3, 1),
(9, 'Cubiculo 9', 'Laptop', 1, 1),
(10, 'cubiculo 1', 'Copiadora', 4, 1),
(11, 'Cubiculo 9', 'Laptop', 1, 1),
(12, 'Cubiculo 1', 'Computadora, Scanner', 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad`
--

DROP TABLE IF EXISTS `disponibilidad`;
CREATE TABLE IF NOT EXISTS `disponibilidad` (
  `disponibilidadID` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(15) NOT NULL,
  PRIMARY KEY (`disponibilidadID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disponibilidad`
--

INSERT INTO `disponibilidad` (`disponibilidadID`, `estado`) VALUES
(1, 'Disponible'),
(2, 'No Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidadexistencia`
--

DROP TABLE IF EXISTS `disponibilidadexistencia`;
CREATE TABLE IF NOT EXISTS `disponibilidadexistencia` (
  `disponibilidadID` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`disponibilidadID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disponibilidadexistencia`
--

INSERT INTO `disponibilidadexistencia` (`disponibilidadID`, `status`) VALUES
(1, 'Disponible'),
(2, 'No disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadoexistencia`
--

DROP TABLE IF EXISTS `estadoexistencia`;
CREATE TABLE IF NOT EXISTS `estadoexistencia` (
  `statusExistenceID` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`statusExistenceID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estadoexistencia`
--

INSERT INTO `estadoexistencia` (`statusExistenceID`, `status`) VALUES
(1, 'Dañado'),
(2, 'Buena'),
(3, 'Excelente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadolector`
--

DROP TABLE IF EXISTS `estadolector`;
CREATE TABLE IF NOT EXISTS `estadolector` (
  `estadoLectorID` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(30) NOT NULL,
  PRIMARY KEY (`estadoLectorID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estadolector`
--

INSERT INTO `estadolector` (`estadoLectorID`, `estado`) VALUES
(1, 'Disponible'),
(2, 'Bloqueado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadomulta`
--

DROP TABLE IF EXISTS `estadomulta`;
CREATE TABLE IF NOT EXISTS `estadomulta` (
  `estadomultaID` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) NOT NULL,
  PRIMARY KEY (`estadomultaID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estadomulta`
--

INSERT INTO `estadomulta` (`estadomultaID`, `estado`) VALUES
(1, 'Pagado'),
(2, 'No pagado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadoprestamo`
--

DROP TABLE IF EXISTS `estadoprestamo`;
CREATE TABLE IF NOT EXISTS `estadoprestamo` (
  `estadoprestamoID` int(11) NOT NULL AUTO_INCREMENT,
  `estado` text NOT NULL,
  PRIMARY KEY (`estadoprestamoID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estadoprestamo`
--

INSERT INTO `estadoprestamo` (`estadoprestamoID`, `estado`) VALUES
(1, 'Activo'),
(2, 'Completado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `existencialibro`
--

DROP TABLE IF EXISTS `existencialibro`;
CREATE TABLE IF NOT EXISTS `existencialibro` (
  `existenciaID` int(11) NOT NULL AUTO_INCREMENT,
  `libroID` int(11) DEFAULT NULL,
  `ubicacionID` int(11) DEFAULT NULL,
  `estadoExistenciaID` int(11) DEFAULT NULL,
  `disponibilidadExistenciaID` int(11) DEFAULT NULL,
  PRIMARY KEY (`existenciaID`),
  KEY `libroID` (`libroID`),
  KEY `ubicacionID` (`ubicacionID`),
  KEY `estadoExistenciaID` (`estadoExistenciaID`),
  KEY `disponibilidadExistenciaID` (`disponibilidadExistenciaID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `existencialibro`
--

INSERT INTO `existencialibro` (`existenciaID`, `libroID`, `ubicacionID`, `estadoExistenciaID`, `disponibilidadExistenciaID`) VALUES
(1, 8, 2, 3, 2),
(2, 8, 2, 2, 2),
(3, 1, 4, 2, 1),
(4, 6, 4, 3, 1),
(5, 8, 1, 2, 1),
(6, 4, 2, 2, 1),
(7, 6, 4, 2, 2),
(8, 5, 5, 3, 2),
(9, 12, 2, 2, 2),
(10, 1, 4, 3, 1),
(12, 12, 2, 2, 1),
(13, 3, 3, 2, 1),
(14, 1, 8, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

DROP TABLE IF EXISTS `genero`;
CREATE TABLE IF NOT EXISTS `genero` (
  `generoID` int(11) NOT NULL AUTO_INCREMENT,
  `gender` varchar(50) NOT NULL,
  PRIMARY KEY (`generoID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`generoID`, `gender`) VALUES
(1, 'Ciencia Ficcion'),
(2, 'Romance'),
(3, 'Historia'),
(4, 'Drama'),
(5, 'Terror'),
(6, 'Científico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lector`
--

DROP TABLE IF EXISTS `lector`;
CREATE TABLE IF NOT EXISTS `lector` (
  `lectorID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `cedula` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(500) DEFAULT NULL,
  `estadoLectorID` int(11) NOT NULL,
  PRIMARY KEY (`lectorID`),
  KEY `estadoLectorID` (`estadoLectorID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lector`
--

INSERT INTO `lector` (`lectorID`, `nombre`, `apellido`, `cedula`, `email`, `telefono`, `direccion`, `estadoLectorID`) VALUES
(1, 'Juana Maria', 'Arcos Vera', '0985745124', 'juanaa@mail.com', '785456', 'Calle 15', 1),
(2, 'Maria Paula', 'Sores Lescano', '0563254578', 'mapa@mail.com', '0965874785', '15va y Febres Cordero', 1),
(3, 'Pablo Ernesto', 'Romero Tapia', '0985647894', 'pabloe@gmail.com', '0987654657', '15 y la P', 2),
(4, 'Diego Miguel', 'Quiroga Renaldo', '0987676567', 'eliseoq@mail.com', '0982341679', 'Francisco de Marcos y la 8ctava', 2),
(5, 'Genesis Leonor', 'Miranda Ordoñez', '0989756123', 'gelemior@mail.com', '0809878674', 'Ciudadela Celeste', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

DROP TABLE IF EXISTS `libro`;
CREATE TABLE IF NOT EXISTS `libro` (
  `libroID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `author` varchar(60) NOT NULL,
  `year` int(4) NOT NULL,
  `pages_no` int(4) NOT NULL,
  `genderID` int(1) NOT NULL,
  `estado` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`libroID`),
  KEY `genderID` (`genderID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`libroID`, `title`, `author`, `year`, `pages_no`, `genderID`, `estado`) VALUES
(1, 'Así como tú', 'Angela Pesantes', 2015, 550, 4, 0),
(2, 'El arbol con la Ventana', 'Kevin Torres Arguello', 2003, 300, 2, 1),
(3, 'EL teorema de Katherins', 'Jonn green', 2003, 450, 2, 0),
(4, 'El quijote', 'Miguel Cervantes', 1990, 450, 2, 0),
(5, 'Las aventuras de los Enanos', 'Micaela Romero', 2000, 366, 1, 1),
(6, 'Lo que el Agua se llevó', 'Miriam Arteaga', 2005, 665, 3, 0),
(7, 'Cazadores de Sombras', 'Alexandra Jimenez', 2005, 980, 1, 0),
(8, '100 años de soledad', 'Gabriel Garcia Marquez', 1965, 450, 2, 0),
(9, 'Despues de la Tormenta', 'Miguel Angel Cuadra', 1998, 420, 4, 0),
(10, 'Yo antes de tí', 'Maria guadalupe', 2003, 550, 2, 0),
(11, 'Ventanas del mas alla', 'Pedro Montes', 1998, 455, 4, 0),
(12, 'Las 1000 y una noches', 'ahmend khed', 1998, 778, 2, 1),
(13, 'Mi vida atra vez de ti', 'Juan Diego', 2014, 350, 2, 0),
(14, 'Ella es Laura', 'José de la Mancha', 2004, 350, 5, 0),
(15, 'Aquí en mi Mundo', 'Allan Martinez', 2003, 275, 1, 1),
(16, 'Un dia Bajo la Lluvia', 'Kelvin Armando', 1998, 350, 4, 0),
(17, 'La Anatomía de las hojas', 'Julian Villegas', 2024, 325, 6, 0),
(18, 'El hombre y la Cigueña', 'Allan Paiz', 2017, 280, 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listarreservas`
--

DROP TABLE IF EXISTS `listarreservas`;
CREATE TABLE IF NOT EXISTS `listarreservas` (
  `listarreservaID` int(11) NOT NULL,
  `prestamoID` int(11) NOT NULL,
  `existenciaID` int(11) NOT NULL,
  KEY `existenciaID` (`existenciaID`),
  KEY `prestamoID` (`prestamoID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `listarreservas`
--

INSERT INTO `listarreservas` (`listarreservaID`, `prestamoID`, `existenciaID`) VALUES
(0, 1, 2),
(1, 1, 3),
(0, 4, 6),
(0, 5, 1),
(0, 6, 5),
(0, 7, 5),
(0, 8, 5),
(0, 9, 1),
(0, 10, 5),
(0, 11, 4),
(0, 12, 10),
(0, 13, 9),
(0, 14, 10),
(0, 15, 10),
(0, 16, 10),
(0, 17, 10),
(0, 18, 10),
(0, 19, 6),
(0, 20, 5),
(0, 21, 5),
(0, 22, 3),
(0, 23, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `multa`
--

DROP TABLE IF EXISTS `multa`;
CREATE TABLE IF NOT EXISTS `multa` (
  `multaID` int(11) NOT NULL AUTO_INCREMENT,
  `monto` double NOT NULL,
  `motivo` varchar(200) DEFAULT NULL,
  `fecha_emision` date NOT NULL,
  `estadomultaID` int(11) NOT NULL,
  `lectorID` int(11) NOT NULL,
  `prestamoID` int(11) NOT NULL,
  PRIMARY KEY (`multaID`),
  KEY `lectorID` (`lectorID`),
  KEY `estadomultaID` (`estadomultaID`),
  KEY `prestamoID` (`prestamoID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `multa`
--

INSERT INTO `multa` (`multaID`, `monto`, `motivo`, `fecha_emision`, `estadomultaID`, `lectorID`, `prestamoID`) VALUES
(1, 2.53, 'Daño de libro', '2025-05-20', 1, 1, 1),
(2, 10, 'Daño', '2025-05-30', 1, 2, 4),
(4, 10, 'Dañado libro', '2025-05-30', 1, 1, 5),
(5, 10, 'Hojas Dañadas y rayadas', '2025-05-30', 1, 1, 8),
(6, 10, 'Rayada en empaste', '2025-05-31', 1, 2, 9),
(7, 10, 'Rayo en Hojas', '2025-06-12', 1, 1, 10),
(8, 10, 'asd', '2025-06-15', 1, 1, 13),
(9, 10, 'Rayado de páginas', '2025-06-26', 1, 4, 19),
(10, 10, 'Hojas Dañadas y rayadas', '2025-06-27', 1, 1, 21),
(11, 10, 'Rayadura de Portada', '2025-06-29', 2, 1, 23);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

DROP TABLE IF EXISTS `prestamo`;
CREATE TABLE IF NOT EXISTS `prestamo` (
  `prestamoID` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_prestamo` date NOT NULL,
  `fecha_devolucion` date NOT NULL,
  `estadoprestamoID` int(11) NOT NULL,
  `lectorID` int(11) NOT NULL,
  `fecha_finalizacion` date NOT NULL,
  PRIMARY KEY (`prestamoID`),
  KEY `estadoprestamoID` (`estadoprestamoID`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`prestamoID`, `fecha_prestamo`, `fecha_devolucion`, `estadoprestamoID`, `lectorID`, `fecha_finalizacion`) VALUES
(1, '2025-05-03', '2025-05-07', 2, 1, '2025-05-06'),
(2, '2025-05-28', '2025-05-30', 2, 1, '2025-05-31'),
(3, '2025-05-28', '2025-05-30', 2, 1, '2025-05-01'),
(4, '2025-05-28', '2025-05-30', 2, 2, '2025-05-30'),
(5, '2025-05-29', '2025-05-31', 2, 1, '2025-05-31'),
(6, '2025-05-29', '2025-05-30', 2, 1, '2025-05-30'),
(7, '2025-05-30', '2025-05-31', 2, 1, '2025-05-31'),
(8, '2025-05-30', '2025-05-30', 2, 1, '2025-05-30'),
(9, '2025-05-31', '2025-05-31', 2, 2, '2025-05-31'),
(10, '2025-06-12', '2025-06-12', 2, 1, '2025-06-12'),
(11, '2025-06-12', '2025-06-12', 2, 1, '2025-06-12'),
(12, '2025-06-15', '2025-06-15', 2, 1, '2025-06-15'),
(13, '2025-06-07', '2025-06-15', 2, 1, '2025-06-15'),
(14, '2025-06-15', '2025-06-15', 2, 1, '2025-06-15'),
(15, '2025-06-22', '2025-06-15', 2, 1, '2025-06-15'),
(16, '2025-06-15', '2025-06-15', 2, 1, '2025-06-15'),
(17, '2025-06-15', '2025-06-15', 2, 1, '2025-06-15'),
(18, '2025-06-18', '2025-06-18', 2, 1, '2025-06-18'),
(19, '2025-06-27', '2025-06-26', 2, 4, '2025-06-26'),
(20, '2025-06-26', '2025-06-26', 2, 4, '2025-06-26'),
(21, '2025-06-27', '2025-06-27', 2, 1, '2025-06-27'),
(22, '2025-06-29', '2025-06-29', 2, 4, '2025-06-29'),
(23, '2025-06-29', '2025-06-29', 2, 1, '2025-06-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE IF NOT EXISTS `rol` (
  `rolID` int(11) NOT NULL AUTO_INCREMENT,
  `nombreRol` varchar(255) NOT NULL,
  PRIMARY KEY (`rolID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rolID`, `nombreRol`) VALUES
(1, 'administrador'),
(2, 'bibliotecario'),
(3, 'lector');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

DROP TABLE IF EXISTS `ubicacion`;
CREATE TABLE IF NOT EXISTS `ubicacion` (
  `ubicacionID` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(20) NOT NULL,
  `aisle` varchar(20) NOT NULL,
  `shelving` varchar(20) NOT NULL,
  `level` varchar(20) NOT NULL,
  PRIMARY KEY (`ubicacionID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ubicacion`
--

INSERT INTO `ubicacion` (`ubicacionID`, `section`, `aisle`, `shelving`, `level`) VALUES
(1, 'Informatica', '3', 'INF', '2'),
(2, 'Literatura', '1', 'LIT', '1'),
(3, 'Matematicas', '4', 'MAT', '3'),
(4, 'Ciencias Sociales', '2', 'SOC', '4'),
(5, 'Filosofia', '5', 'FIL', '1'),
(6, 'Historia', '3', 'HIS', '2'),
(7, 'Arte', '1', 'ART', '5'),
(8, 'Ciencias Naturales', '6', 'CIN', '1');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alquilercubiculo`
--
ALTER TABLE `alquilercubiculo`
  ADD CONSTRAINT `alquilercubiculo_ibfk_1` FOREIGN KEY (`cubiculoID`) REFERENCES `cubiculo` (`cubiculoID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `alquilercubiculo_ibfk_2` FOREIGN KEY (`lectorID`) REFERENCES `lector` (`lectorID`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `bibliotecario`
--
ALTER TABLE `bibliotecario`
  ADD CONSTRAINT `bibliotecario_ibfk_1` FOREIGN KEY (`rolID`) REFERENCES `rol` (`rolID`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `cubiculo`
--
ALTER TABLE `cubiculo`
  ADD CONSTRAINT `cubiculo_ibfk_1` FOREIGN KEY (`disponibilidadID`) REFERENCES `disponibilidad` (`disponibilidadID`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `existencialibro`
--
ALTER TABLE `existencialibro`
  ADD CONSTRAINT `existencialibro_ibfk_1` FOREIGN KEY (`libroID`) REFERENCES `libro` (`libroID`),
  ADD CONSTRAINT `existencialibro_ibfk_2` FOREIGN KEY (`ubicacionID`) REFERENCES `ubicacion` (`ubicacionID`),
  ADD CONSTRAINT `existencialibro_ibfk_3` FOREIGN KEY (`estadoExistenciaID`) REFERENCES `estadoexistencia` (`statusExistenceID`),
  ADD CONSTRAINT `existencialibro_ibfk_4` FOREIGN KEY (`disponibilidadExistenciaID`) REFERENCES `disponibilidadexistencia` (`disponibilidadID`);

--
-- Filtros para la tabla `lector`
--
ALTER TABLE `lector`
  ADD CONSTRAINT `lector_ibfk_1` FOREIGN KEY (`estadoLectorID`) REFERENCES `estadolector` (`estadoLectorID`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `libro`
--
ALTER TABLE `libro`
  ADD CONSTRAINT `libro_ibfk_1` FOREIGN KEY (`genderID`) REFERENCES `genero` (`generoID`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `listarreservas`
--
ALTER TABLE `listarreservas`
  ADD CONSTRAINT `listarreservas_ibfk_1` FOREIGN KEY (`existenciaID`) REFERENCES `existencialibro` (`existenciaID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `listarreservas_ibfk_2` FOREIGN KEY (`prestamoID`) REFERENCES `prestamo` (`prestamoID`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `multa`
--
ALTER TABLE `multa`
  ADD CONSTRAINT `multa_ibfk_1` FOREIGN KEY (`lectorID`) REFERENCES `lector` (`lectorID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `multa_ibfk_2` FOREIGN KEY (`estadomultaID`) REFERENCES `estadomulta` (`estadomultaID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `multa_ibfk_3` FOREIGN KEY (`prestamoID`) REFERENCES `prestamo` (`prestamoID`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `prestamo_ibfk_1` FOREIGN KEY (`estadoprestamoID`) REFERENCES `estadoprestamo` (`estadoprestamoID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
