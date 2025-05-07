-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-05-2025 a las 04:01:27
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

DELIMITER $$
--
-- Procedimientos
--
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

DROP PROCEDURE IF EXISTS `insertarExistencia`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertarExistencia` (IN `p_libroID` INT, IN `p_ubicacionID` INT, IN `p_estadoExistenciaID` INT, IN `p_disponibilidadExistenciaID` INT)   BEGIN
    INSERT INTO existenciaLibro (libroID, ubicacionID, estadoExistenciaID, disponibilidadExistenciaID)
    VALUES (p_libroID, p_ubicacionID, p_estadoExistenciaID, p_disponibilidadExistenciaID);
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

DROP PROCEDURE IF EXISTS `sp_buscar_bibliotecarios`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_bibliotecarios` (IN `p_campo` VARCHAR(20), IN `p_valor` VARCHAR(30))   BEGIN
    SET @sql = CONCAT('SELECT bibliotecarioID, nombre, apellido, email, usuario, rolID 
                      FROM bibliotecario 
                      WHERE rolID = 2 AND ', p_campo, ' LIKE CONCAT("%", ?, "%")');
    
    PREPARE stmt FROM @sql;
    SET @valor = p_valor;
    EXECUTE stmt USING @valor;
    DEALLOCATE PREPARE stmt;
END$$

DROP PROCEDURE IF EXISTS `sp_buscar_lector`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_lector` (IN `p_cedula` VARCHAR(20))   BEGIN
    SELECT 
        nombre,
        email
    FROM 
        lector
    WHERE 
        cedula = p_cedula;
END$$

DROP PROCEDURE IF EXISTS `sp_buscar_lectores`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_lectores` (IN `p_campo` VARCHAR(20), IN `p_valor` VARCHAR(50))   BEGIN
    SET @sql = CONCAT('SELECT lectorID, nombre, apellido, cedula, email, telefono, direccion, estadoLectorID 
                      FROM lector 
                      WHERE ', p_campo, ' LIKE CONCAT("%", ?, "%")');
    
    PREPARE stmt FROM @sql;
    SET @valor = p_valor;
    EXECUTE stmt USING @valor;
    DEALLOCATE PREPARE stmt;
END$$

DROP PROCEDURE IF EXISTS `sp_buscar_libros`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_buscar_libros` (IN `campo_busqueda` VARCHAR(20), IN `valor_busqueda` VARCHAR(255))   BEGIN
    IF campo_busqueda = 'titulo' THEN
        SELECT 
            l.libroID, l.title, l.author, l.year, 
            l.pages_no, g.gender, l.estado
        FROM libro l
        INNER JOIN genero g ON l.genderID = g.generoID
        WHERE l.title LIKE CONCAT('%', valor_busqueda, '%');

    ELSEIF campo_busqueda = 'autor' THEN
        SELECT 
            l.libroID, l.title, l.author, l.year, 
            l.pages_no, g.gender, l.estado
        FROM libro l
        INNER JOIN genero g ON l.genderID = g.generoID
        WHERE l.author LIKE CONCAT('%', valor_busqueda, '%');

    ELSEIF campo_busqueda = 'id' THEN
        SELECT 
            l.libroID, l.title, l.author, l.year, 
            l.pages_no, g.gender, l.estado
        FROM libro l
        INNER JOIN genero g ON l.genderID = g.generoID
        WHERE l.libroID = valor_busqueda;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `sp_desactivar_lector`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_desactivar_lector` (IN `p_lectorID` INT)   BEGIN
    UPDATE lector
    SET estadoLectorID = 2 -- 2 = Inactivo
    WHERE lectorID = p_lectorID;
END$$

DROP PROCEDURE IF EXISTS `sp_eliminar_bibliotecario`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminar_bibliotecario` (IN `p_bibliotecarioID` INT)   BEGIN
    DELETE FROM bibliotecario WHERE bibliotecarioID = p_bibliotecarioID AND rolID = 2;
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
    SELECT bibliotecarioID, nombre, apellido, email, usuario, rolID 
    FROM bibliotecario
    WHERE rolID = 2;
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
        prestamoID, 
        fecha_prestamo, 
        fecha_devolucion, 
        estadoprestamoID, 
        lectorID, 
        fecha_finalizacion
    FROM 
        prestamo;
END$$

DROP PROCEDURE IF EXISTS `sp_reactivar_lector`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_reactivar_lector` (IN `p_lectorID` INT)   BEGIN
    UPDATE lector
    SET estadoLectorID = 1 -- 1 = Activo
    WHERE lectorID = p_lectorID;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alquilercubiculo`
--

DROP TABLE IF EXISTS `alquilercubiculo`;
CREATE TABLE `alquilercubiculo` (
  `alquilercubiculoID` int(11) NOT NULL,
  `tiempo` varchar(50) NOT NULL,
  `lectorID` int(11) NOT NULL,
  `cubiculoID` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bibliotecario`
--

DROP TABLE IF EXISTS `bibliotecario`;
CREATE TABLE `bibliotecario` (
  `bibliotecarioID` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `usuario` varchar(15) NOT NULL,
  `contrasenia` varchar(15) NOT NULL,
  `rolID` int(11) NOT NULL,
  `bloqueado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bibliotecario`
--

INSERT INTO `bibliotecario` (`bibliotecarioID`, `nombre`, `apellido`, `email`, `usuario`, `contrasenia`, `rolID`, `bloqueado`) VALUES
(2, 'juan ernesto', 'riquelme mera', 'juanerne@mail.com', 'juanere', '123456', 2, 0),
(3, 'Jeremy David', 'Soto Monar', 'jeremymon@mail.com', 'jedoso', '422463', 2, 0),
(4, 'Maria Elisa', 'Estrella Dueñas', 'melisa@mail.com', 'melissa1', '$2y$10$kwxK.4s0', 2, 0),
(5, 'Didier Joel', 'Frias Mayor', 'didi123@mmail.com', 'jolo45', '$2y$10$LQ7dGN5e', 2, 0),
(6, 'Diego Alejandro', 'Farias Mite', 'Fmite@mail.com', 'diego12', '$2y$10$f9cyUEMR', 2, 0),
(7, 'Marlon', 'Quijije', 'quijijema@mail.com', 'quijijex', '456', 2, 0),
(8, 'Josefina', 'Garcia', 'josega@mail.com', 'gajose1', '1234', 2, 0),
(9, 'lucas', 'bajaña', 'luca@mail.com', 'lucas1', '45556', 2, 0),
(10, 'Kevin ', 'Arguello', 'kevin@mail.com', 'kevin12', '1236', 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobante`
--

DROP TABLE IF EXISTS `comprobante`;
CREATE TABLE `comprobante` (
  `comprobanteID` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `prestamoID` int(11) NOT NULL,
  `bibliotecarioID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cubiculo`
--

DROP TABLE IF EXISTS `cubiculo`;
CREATE TABLE `cubiculo` (
  `cubiculoID` int(11) NOT NULL,
  `nombre` varchar(10) NOT NULL,
  `equipamento` varchar(100) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `disponibilidadID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad`
--

DROP TABLE IF EXISTS `disponibilidad`;
CREATE TABLE `disponibilidad` (
  `disponibilidadID` int(11) NOT NULL,
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
CREATE TABLE `disponibilidadexistencia` (
  `disponibilidadID` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
CREATE TABLE `estadoexistencia` (
  `statusExistenceID` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
CREATE TABLE `estadolector` (
  `estadoLectorID` int(11) NOT NULL,
  `estado` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estadolector`
--

INSERT INTO `estadolector` (`estadoLectorID`, `estado`) VALUES
(1, 'bloqueado'),
(2, 'disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadomulta`
--

DROP TABLE IF EXISTS `estadomulta`;
CREATE TABLE `estadomulta` (
  `estadomultaID` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
CREATE TABLE `estadoprestamo` (
  `estadoprestamoID` int(11) NOT NULL,
  `estado` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estadoprestamo`
--

INSERT INTO `estadoprestamo` (`estadoprestamoID`, `estado`) VALUES
(1, 'Prestado'),
(2, 'Devuelto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `existencialibro`
--

DROP TABLE IF EXISTS `existencialibro`;
CREATE TABLE `existencialibro` (
  `existenciaID` int(11) NOT NULL,
  `libroID` int(11) DEFAULT NULL,
  `ubicacionID` int(11) DEFAULT NULL,
  `estadoExistenciaID` int(11) DEFAULT NULL,
  `disponibilidadExistenciaID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `existencialibro`
--

INSERT INTO `existencialibro` (`existenciaID`, `libroID`, `ubicacionID`, `estadoExistenciaID`, `disponibilidadExistenciaID`) VALUES
(1, 8, 2, 3, 1),
(2, 8, 8, 1, 2),
(3, 1, 6, 2, 2),
(4, 6, 4, 2, 1),
(5, 8, 1, 3, 1),
(6, 4, 7, 3, 1),
(7, 6, 4, 2, 2),
(8, 5, 5, 3, 2),
(9, 12, 3, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

DROP TABLE IF EXISTS `genero`;
CREATE TABLE `genero` (
  `generoID` int(11) NOT NULL,
  `gender` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
CREATE TABLE `lector` (
  `lectorID` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `direccion` varchar(60) NOT NULL,
  `estadoLectorID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lector`
--

INSERT INTO `lector` (`lectorID`, `nombre`, `apellido`, `cedula`, `email`, `telefono`, `direccion`, `estadoLectorID`) VALUES
(1, 'Juana Maria', 'Arcos Vera', '0985745124', 'juanaa@mail.com', '785457', 'Calle 15', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

DROP TABLE IF EXISTS `libro`;
CREATE TABLE `libro` (
  `libroID` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `author` varchar(60) NOT NULL,
  `year` int(4) NOT NULL,
  `pages_no` int(4) NOT NULL,
  `genderID` int(1) NOT NULL,
  `estado` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`libroID`, `title`, `author`, `year`, `pages_no`, `genderID`, `estado`) VALUES
(1, 'Así como tú', 'Angela Pesantes', 2015, 550, 4, 1),
(2, 'El arbol con la Ventana', 'Kevin Torres Arguello', 2003, 300, 2, 1),
(3, 'EL teorema de Katherins', 'Jonn green', 2003, 450, 2, 1),
(4, 'El quijote', 'Miguel Cervantes', 1990, 450, 2, 1),
(5, 'Las aventuras de los Enanos', 'Micaela Romero', 2000, 366, 1, 1),
(6, 'Lo que el Agua se llevó', 'Miriam Arteaga', 2005, 665, 3, 1),
(7, 'Cazadores de Sombras', 'Alexandra Jimenez', 2005, 980, 1, 1),
(8, '100 años de soledad', 'Gabriel Garcia Marquez', 1965, 450, 2, 1),
(9, 'Despues de la Tormenta', 'Miguel Angel Cuadra', 1998, 420, 4, 1),
(10, 'Yo antes de tí', 'Maria guadalupe', 2003, 550, 2, 1),
(11, 'Ventanas del mas alla', 'Pedro Montes', 1998, 455, 4, 1),
(12, 'Las 1000 y una noches', 'ahmend khed', 1998, 778, 5, 1),
(13, 'Mi vida atra vez de ti', 'Juan Diego', 2014, 350, 2, 1),
(14, 'Ella es Laura', 'José de la Mancha', 2004, 350, 4, 1),
(15, 'Aquí en mi Mundo', 'Allan Martinez', 2003, 275, 1, 1),
(16, 'Un dia Bajo la Lluvia', 'Kelvin Armando', 1998, 350, 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listarreservas`
--

DROP TABLE IF EXISTS `listarreservas`;
CREATE TABLE `listarreservas` (
  `listarreservaID` int(11) NOT NULL,
  `prestamoID` int(11) NOT NULL,
  `existenciaID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `listarreservas`
--

INSERT INTO `listarreservas` (`listarreservaID`, `prestamoID`, `existenciaID`) VALUES
(0, 1, 2),
(1, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `multa`
--

DROP TABLE IF EXISTS `multa`;
CREATE TABLE `multa` (
  `multaID` int(11) NOT NULL,
  `monto` double NOT NULL,
  `motivo` varchar(200) NOT NULL,
  `fecha_emision` date NOT NULL,
  `estadomultaID` int(11) NOT NULL,
  `lectorID` int(11) NOT NULL,
  `prestamoID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

DROP TABLE IF EXISTS `prestamo`;
CREATE TABLE `prestamo` (
  `prestamoID` int(11) NOT NULL,
  `fecha_prestamo` date NOT NULL,
  `fecha_devolucion` date NOT NULL,
  `estadoprestamoID` int(11) NOT NULL,
  `lectorID` int(11) NOT NULL,
  `fecha_finalizacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`prestamoID`, `fecha_prestamo`, `fecha_devolucion`, `estadoprestamoID`, `lectorID`, `fecha_finalizacion`) VALUES
(1, '2025-05-03', '2025-05-07', 1, 1, '2025-05-06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol` (
  `rolID` int(11) NOT NULL,
  `nombreRol` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
CREATE TABLE `ubicacion` (
  `ubicacionID` int(11) NOT NULL,
  `section` varchar(20) NOT NULL,
  `aisle` varchar(20) NOT NULL,
  `shelving` varchar(20) NOT NULL,
  `level` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alquilercubiculo`
--
ALTER TABLE `alquilercubiculo`
  ADD PRIMARY KEY (`alquilercubiculoID`),
  ADD KEY `cubiculoID` (`cubiculoID`),
  ADD KEY `lectorID` (`lectorID`);

--
-- Indices de la tabla `bibliotecario`
--
ALTER TABLE `bibliotecario`
  ADD PRIMARY KEY (`bibliotecarioID`),
  ADD KEY `rolID` (`rolID`);

--
-- Indices de la tabla `comprobante`
--
ALTER TABLE `comprobante`
  ADD PRIMARY KEY (`comprobanteID`);

--
-- Indices de la tabla `cubiculo`
--
ALTER TABLE `cubiculo`
  ADD PRIMARY KEY (`cubiculoID`),
  ADD KEY `disponibilidadID` (`disponibilidadID`);

--
-- Indices de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  ADD PRIMARY KEY (`disponibilidadID`);

--
-- Indices de la tabla `disponibilidadexistencia`
--
ALTER TABLE `disponibilidadexistencia`
  ADD PRIMARY KEY (`disponibilidadID`);

--
-- Indices de la tabla `estadoexistencia`
--
ALTER TABLE `estadoexistencia`
  ADD PRIMARY KEY (`statusExistenceID`);

--
-- Indices de la tabla `estadolector`
--
ALTER TABLE `estadolector`
  ADD PRIMARY KEY (`estadoLectorID`);

--
-- Indices de la tabla `estadomulta`
--
ALTER TABLE `estadomulta`
  ADD PRIMARY KEY (`estadomultaID`);

--
-- Indices de la tabla `estadoprestamo`
--
ALTER TABLE `estadoprestamo`
  ADD PRIMARY KEY (`estadoprestamoID`);

--
-- Indices de la tabla `existencialibro`
--
ALTER TABLE `existencialibro`
  ADD PRIMARY KEY (`existenciaID`),
  ADD KEY `libroID` (`libroID`),
  ADD KEY `ubicacionID` (`ubicacionID`),
  ADD KEY `estadoExistenciaID` (`estadoExistenciaID`),
  ADD KEY `disponibilidadExistenciaID` (`disponibilidadExistenciaID`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`generoID`);

--
-- Indices de la tabla `lector`
--
ALTER TABLE `lector`
  ADD PRIMARY KEY (`lectorID`),
  ADD KEY `estadoLectorID` (`estadoLectorID`);

--
-- Indices de la tabla `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`libroID`),
  ADD KEY `genderID` (`genderID`);

--
-- Indices de la tabla `listarreservas`
--
ALTER TABLE `listarreservas`
  ADD KEY `existenciaID` (`existenciaID`),
  ADD KEY `prestamoID` (`prestamoID`);

--
-- Indices de la tabla `multa`
--
ALTER TABLE `multa`
  ADD PRIMARY KEY (`multaID`),
  ADD KEY `lectorID` (`lectorID`),
  ADD KEY `estadomultaID` (`estadomultaID`),
  ADD KEY `prestamoID` (`prestamoID`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`prestamoID`),
  ADD KEY `estadoprestamoID` (`estadoprestamoID`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`rolID`);

--
-- Indices de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD PRIMARY KEY (`ubicacionID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alquilercubiculo`
--
ALTER TABLE `alquilercubiculo`
  MODIFY `alquilercubiculoID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bibliotecario`
--
ALTER TABLE `bibliotecario`
  MODIFY `bibliotecarioID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `comprobante`
--
ALTER TABLE `comprobante`
  MODIFY `comprobanteID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cubiculo`
--
ALTER TABLE `cubiculo`
  MODIFY `cubiculoID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  MODIFY `disponibilidadID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `disponibilidadexistencia`
--
ALTER TABLE `disponibilidadexistencia`
  MODIFY `disponibilidadID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estadoexistencia`
--
ALTER TABLE `estadoexistencia`
  MODIFY `statusExistenceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estadolector`
--
ALTER TABLE `estadolector`
  MODIFY `estadoLectorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estadomulta`
--
ALTER TABLE `estadomulta`
  MODIFY `estadomultaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estadoprestamo`
--
ALTER TABLE `estadoprestamo`
  MODIFY `estadoprestamoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `existencialibro`
--
ALTER TABLE `existencialibro`
  MODIFY `existenciaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `generoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `lector`
--
ALTER TABLE `lector`
  MODIFY `lectorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `libroID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `multa`
--
ALTER TABLE `multa`
  MODIFY `multaID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `prestamoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `rolID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  MODIFY `ubicacionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
