-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-06-2025 a las 07:44:52
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `colegio`
--xd

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `AlumnoID` int(11) NOT NULL,
  `DNI` varchar(12) NOT NULL,
  `Nombres` varchar(100) NOT NULL,
  `Apellidos` varchar(100) NOT NULL,
  `Fec_Nacimiento` date DEFAULT NULL,
  `Fec_Registro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`AlumnoID`, `DNI`, `Nombres`, `Apellidos`, `Fec_Nacimiento`, `Fec_Registro`) VALUES
(1, '92251762', 'Mia Cataleya', 'Fenco Huamani', '2021-02-28', '2024-03-02'),
(2, '92031777', 'Zeus Kalel Eduardo', 'Villar Infante', '2020-09-24', '2024-02-28'),
(3, '91449083', 'Diego Alonso', 'Estada Almonacid', '2019-07-19', '2023-03-03'),
(4, '91294943', 'Saul Ezequiel', 'Ortiz Zevallos', '2019-04-19', '2023-03-03'),
(5, '91281568', 'Alessandro Benyamin', 'Quispe Gavilan', '2019-04-09', '2025-03-05'),
(6, '91699456', 'Thiago Guillermo', 'Valenzuela Apaza', '2020-01-26', '2025-03-05'),
(7, '91431444', 'Danna Danuska', 'Zapata Luna', '2019-07-23', '2023-03-01'),
(8, '92646723', 'Danae Brianna Zendaya', 'Rivera Iquiapaza', '2021-11-29', '2025-02-26'),
(9, '92448265', 'Luna Morita', 'Allpacca Ccorahua', '2021-07-14', '2025-02-19'),
(10, '92432241', 'Kendall Rafael', 'Loyo Meza', '2021-07-03', '2025-02-20'),
(11, '92676328', 'Carlos Patricio', 'Semblantes Martinez', '2021-12-20', '2025-02-22'),
(12, '91884015', 'Gael Emiliano', 'Aguilar Pacheco', '2020-06-08', '2023-03-01'),
(13, '92294641', 'Raphael Nehemias', 'Calluchi Naveros', '2021-03-30', '2023-03-04'),
(14, '92012621', 'Jesus Efraim', 'Castro Carrera', '2020-09-11', '2023-02-15'),
(15, '91924775', 'Valentin Rey', 'Infante Taboada', '2020-07-10', '2025-02-16'),
(16, '92110236', 'Joaquin Abraham', 'Ramos Pinto', '2020-11-14', '2025-02-25'),
(17, '91839551', 'Kylie Rousse', 'Semblantes Martinez', '2020-05-04', '2025-02-16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apoderados`
--

CREATE TABLE `apoderados` (
  `ApoderadoID` int(11) NOT NULL,
  `DNI` varchar(100) NOT NULL,
  `Nombres` varchar(100) NOT NULL,
  `Apellidos` varchar(100) NOT NULL,
  `Telefono` varchar(12) DEFAULT NULL,
  `Correo` varchar(100) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `apoderados`
--

INSERT INTO `apoderados` (`ApoderadoID`, `DNI`, `Nombres`, `Apellidos`, `Telefono`, `Correo`, `Direccion`) VALUES
(1, '71334561', 'Carlos Andres', 'Aguilar Rojas', '973901820', 'carlos.gutierrez@email.com', 'Jiron Crespo y Castillo 2099'),
(2, '70014581', 'Mariana Elena', 'Zevallos Torres', '922911332', 'mariana.torres@email.com', 'Jr. Armendariz 2010'),
(3, '71234541', 'Jose Manuel', 'Calluchi Olivares', '999922820', 'jose.paredes@email.com', 'Avenida Universitaria 3914'),
(4, '74456561', 'Patricia Rosa', 'Gavilan Aguilar', '955965821', 'patricia.mendoza@email.com', 'AV. Grau 3030'),
(5, '78809551', 'Ricardo Ivan', 'Castro Leon', '945646821', 'ricardo.castaneda@email.com', 'Calle Esperanza 2005'),
(6, '72834521', 'Ana Lucia', 'Apaza Ramirez', '911256785', 'ana.chavez@email.com', 'Calle Pescadores 2124'),
(7, '74232521', 'Miguel Angel', 'Ramos Ponce', '903911824', 'miguel.romero@email.com', 'Jiron Grau, La perla'),
(8, '75939531', 'Claudia Teresa', 'Infante Nunez', '918252334', 'claudia.delgado@email.com', 'Los eucaliptos 2512'),
(9, '70330591', 'Luis Enrique', 'Semblantes Quispe', '943856204', 'luis.herrera@email.com', 'Av. La marina cruce con Av. Universitaria'),
(10, '71104561', 'Sonia Milagros', 'Meza Zambrano', '959924214', 'sonia.vargas@email.com', 'Calle Chacarilla 2055');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `AsistenciaID` int(11) NOT NULL,
  `AlumnoID` int(11) DEFAULT NULL,
  `Fecha` date NOT NULL,
  `Estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `CategoriaID` int(11) NOT NULL,
  `Nombre_Categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`CategoriaID`, `Nombre_Categoria`) VALUES
(1, 'Inicial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `DocenteID` int(11) NOT NULL,
  `DNI` varchar(12) NOT NULL,
  `Nombres` varchar(100) NOT NULL,
  `Apellidos` varchar(100) NOT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Fec_Registro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`DocenteID`, `DNI`, `Nombres`, `Apellidos`, `Direccion`, `Fec_Registro`) VALUES
(1, '71451634', 'Martha', 'Zurita Meza', 'Jiron Joaquin capello 3054', '2022-03-10'),
(2, '73251509', 'Nataly Maria', 'Ulloa Zurita', 'Jiron Joaquin capello 3050', '2018-03-02'),
(3, '71957702', 'Isandra', 'Carranza Choque', 'Av. La marina 4050', '2024-08-10'),
(4, '67113562', 'Veronica', 'Alvarez Quispe', 'Jiron Crespo y Castilo 3049', '2023-03-01'),
(5, '75548164', 'Ana Rosa', 'Ulloa Zurita', 'Jiron Miguel Grau 6084 - La Lerla', '2014-01-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE `login` (
  `UsuarioID` int(11) NOT NULL,
  `Nombre_Usuario` varchar(100) DEFAULT NULL,
  `Contrasena` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `login`
--

INSERT INTO `login` (`UsuarioID`, `Nombre_Usuario`, `Contrasena`) VALUES
(1, 'admin', '1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matriculas`
--

CREATE TABLE `matriculas` (
  `MatriculaID` int(11) NOT NULL,
  `AlumnoID` int(11) DEFAULT NULL,
  `SeccionID` int(11) DEFAULT NULL,
  `Periodo_Inicio` date NOT NULL,
  `Periodo_Fin` date NOT NULL,
  `Estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `matriculas`
--

INSERT INTO `matriculas` (`MatriculaID`, `AlumnoID`, `SeccionID`, `Periodo_Inicio`, `Periodo_Fin`, `Estado`) VALUES
(1, 1, 1, '2025-03-05', '2025-12-19', 'Activo'),
(2, 2, 1, '2025-03-05', '2025-12-19', 'Activo'),
(3, 12, 1, '2025-03-05', '2025-12-19', 'Activo'),
(4, 13, 1, '2025-03-05', '2025-12-19', 'Activo'),
(5, 14, 1, '2025-03-05', '2025-12-19', 'Activo'),
(6, 15, 1, '2025-03-05', '2025-12-19', 'Activo'),
(7, 16, 1, '2025-03-05', '2025-12-19', 'Activo'),
(8, 17, 1, '2025-03-05', '2025-12-19', 'Activo'),
(9, 3, 2, '2025-03-05', '2025-12-19', 'Activo'),
(10, 4, 2, '2025-03-05', '2025-12-19', 'Activo'),
(11, 5, 2, '2025-03-05', '2025-12-19', 'Activo'),
(12, 6, 2, '2025-03-05', '2025-12-19', 'Activo'),
(13, 7, 2, '2025-03-05', '2025-12-19', 'Activo'),
(14, 8, 3, '2025-03-05', '2025-12-19', 'Activo'),
(15, 9, 3, '2025-03-05', '2025-12-19', 'Activo'),
(16, 10, 3, '2025-03-05', '2025-12-19', 'Activo'),
(17, 11, 3, '2025-03-05', '2025-12-19', 'Activo'),
(18, 1, 2, '2025-06-01', '2025-06-26', 'Suspendido'),
(19, 1, 2, '2025-06-12', '2025-06-25', 'Suspendido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `observaciones`
--

CREATE TABLE `observaciones` (
  `ObservacionID` int(11) NOT NULL,
  `AlumnoID` int(11) DEFAULT NULL,
  `DocenteID` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `observaciones`
--

INSERT INTO `observaciones` (`ObservacionID`, `AlumnoID`, `DocenteID`, `Fecha`, `Comentario`) VALUES
(1, 1, 2, '2025-03-12', 'Inquietud'),
(2, 4, 3, '2025-03-12', 'Agresivo'),
(3, 5, 1, '2025-03-12', 'Felicitaciones');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `SeccionID` int(11) NOT NULL,
  `CategoriaID` int(11) DEFAULT NULL,
  `Nombre_Seccion` varchar(10) NOT NULL,
  `Cupo_Maximo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`SeccionID`, `CategoriaID`, `Nombre_Seccion`, `Cupo_Maximo`) VALUES
(1, 1, 'Aula 1', 14),
(2, 1, 'Aula 2', 10),
(3, 1, 'Aula 3', 6),
(4, 1, 'Aula 4', 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`AlumnoID`);

--
-- Indices de la tabla `apoderados`
--
ALTER TABLE `apoderados`
  ADD PRIMARY KEY (`ApoderadoID`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`AsistenciaID`),
  ADD KEY `AlumnoID` (`AlumnoID`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`CategoriaID`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`DocenteID`);

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`UsuarioID`);

--
-- Indices de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`MatriculaID`),
  ADD KEY `AlumnoID` (`AlumnoID`),
  ADD KEY `SeccionID` (`SeccionID`);

--
-- Indices de la tabla `observaciones`
--
ALTER TABLE `observaciones`
  ADD PRIMARY KEY (`ObservacionID`),
  ADD KEY `AlumnoID` (`AlumnoID`),
  ADD KEY `DocenteID` (`DocenteID`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`SeccionID`),
  ADD KEY `CategoriaID` (`CategoriaID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `MatriculaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`AlumnoID`) REFERENCES `alumnos` (`AlumnoID`);

--
-- Filtros para la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`AlumnoID`) REFERENCES `alumnos` (`AlumnoID`),
  ADD CONSTRAINT `matriculas_ibfk_2` FOREIGN KEY (`SeccionID`) REFERENCES `secciones` (`SeccionID`);

--
-- Filtros para la tabla `observaciones`
--
ALTER TABLE `observaciones`
  ADD CONSTRAINT `observaciones_ibfk_1` FOREIGN KEY (`AlumnoID`) REFERENCES `alumnos` (`AlumnoID`),
  ADD CONSTRAINT `observaciones_ibfk_2` FOREIGN KEY (`DocenteID`) REFERENCES `docentes` (`DocenteID`);

--
-- Filtros para la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD CONSTRAINT `secciones_ibfk_1` FOREIGN KEY (`CategoriaID`) REFERENCES `categoria` (`CategoriaID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;