-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2024 a las 12:34:54
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
-- Base de datos: `bd_rutalibre3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id_actividad` int(11) NOT NULL,
  `nombre_actividad` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `viajes_id_viajes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id_actividad`, `nombre_actividad`, `descripcion`, `fecha_inicio`, `fecha_fin`, `viajes_id_viajes`) VALUES
(1, 'Visita al Museo del Prado', 'Tour guiado por el Museo del Prado', '2024-11-17 10:00:00', '2024-11-17 13:00:00', 1),
(2, 'Paseo en Barco por el Guadalquivir', 'Recorrido en barco por el río Guadalquivir', '2024-11-22 14:00:00', '2024-11-22 16:00:00', 2),
(3, 'Excursión a la Alhambra', 'Visita guiada a la Alhambra en Granada', '2024-12-21 09:00:00', '2024-12-21 12:00:00', 3),
(4, 'Ruta del Vino en La Rioja', 'Tour de bodegas y cata de vinos en La Rioja', '2024-12-02 11:00:00', '2024-12-02 17:00:00', 4),
(5, 'Exploración del Parque Güell', 'Visita y recorrido por el Parque Güell', '2024-11-14 15:00:00', '2024-11-14 18:00:00', 5),
(6, 'Tour por el Casco Antiguo de Sevilla', 'Recorrido a pie por el casco antiguo de Sevilla', '2025-01-06 10:00:00', '2025-01-06 13:00:00', 6),
(7, 'Tour Gastronómico en San Sebastián', 'Recorrido por los mejores restaurantes de pintxos', '2025-01-10 12:00:00', '2025-01-10 15:00:00', 1),
(8, 'Camino de Santiago', 'Caminata desde Sarria a Santiago de Compostela', '2025-02-01 08:00:00', '2025-02-07 18:00:00', 2),
(9, 'Esquí en los Pirineos', 'Día de esquí en la estación de Baqueira-Beret', '2025-01-20 09:00:00', '2025-01-20 17:00:00', 3),
(10, 'Festival de Cine de San Sebastián', 'Asistencia a proyecciones y eventos del festival', '2025-09-20 11:00:00', '2025-09-27 22:00:00', 4),
(11, 'Fiestas de San Fermín en Pamplona', 'Participación en los encierros y festividades', '2025-07-06 08:00:00', '2025-07-14 23:00:00', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id_grupo` int(11) NOT NULL,
  `nombre_grupo` varchar(255) NOT NULL,
  `integrantes` int(11) NOT NULL DEFAULT 1,
  `estado` enum('activo','eliminado') NOT NULL DEFAULT 'activo',
  `descripcion` text NOT NULL,
  `viajes_id_viajes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `nombre_grupo`, `integrantes`, `estado`, `descripcion`, `viajes_id_viajes`) VALUES
(1, 'viaje 1', 1, 'activo', 'viaje prueba', 1),
(2, 'viaje 2', 2, 'activo', 'grupo de viaje a Valencia', 2),
(3, 'viaje 3', 3, 'activo', 'grupo de viaje a Mallorca', 3),
(4, 'viaje 4', 4, 'activo', 'grupo de viaje a Barcelona', 4),
(5, 'viaje 5', 5, 'activo', 'grupo de viaje a Madrid', 5),
(6, 'viaje 6', 6, 'activo', 'grupo de viaje a Granada', 6),
(7, 'viaje 7', 7, 'activo', 'grupo de viaje a Sevilla', 7),
(8, 'viaje 8', 8, 'activo', 'grupo de viaje a Bilbao', 8),
(9, 'viaje 9', 9, 'activo', 'grupo de viaje a San Sebastián', 1),
(10, 'viaje 10', 10, 'activo', 'grupo de viaje de Camino de Santiago', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `tipo_usuario` enum('empresa','particular') NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `email`, `password`, `fecha_creacion`, `tipo_usuario`, `password_reset_token`, `token_expiry`) VALUES
(1, 'Andres', 'Flores', 'adresflores@gmail.com', '123ABC', '2024-11-14 09:44:06', 'particular', NULL, NULL),
(2, 'Pedro', 'Cereza', 'pedroCereza@gmail.com', 'ABC123', '2024-11-14 11:32:53', 'particular', NULL, NULL),
(3, 'Laura', 'Gomez', 'lauraGomez@gmail.com', 'DEF456', '2024-11-14 12:00:00', 'particular', NULL, NULL),
(4, 'Carlos', 'Sanchez', 'carlosSanchez@gmail.com', 'GHI789', '2024-11-14 12:05:00', 'empresa', NULL, NULL),
(5, 'Ana', 'Martinez', 'anaMartinez@gmail.com', 'JKL012', '2024-11-14 12:10:00', 'particular', NULL, NULL),
(6, 'Miguel', 'Ramirez', 'miguelRamirez@gmail.com', 'MNO345', '2024-11-14 12:15:00', 'empresa', NULL, NULL),
(7, 'Sofia', 'Perez', 'sofiaPerez@gmail.com', 'PQR678', '2024-11-14 12:20:00', 'particular', NULL, NULL),
(8, 'Andres', 'Vega', 'andresVega@gmail.com', 'STU901', '2024-11-14 12:25:00', 'empresa', NULL, NULL),
(9, 'Maria', 'Lopez', 'mariaLopez@gmail.com', 'VWX234', '2024-11-14 12:30:00', 'particular', NULL, NULL),
(10, 'Javier', 'Garcia', 'javierGarcia@gmail.com', 'YZA567', '2024-11-14 12:35:00', 'empresa', NULL, NULL),
(11, 'Elena', 'Ruiz', 'elenaRuiz@gmail.com', 'BCD890', '2024-11-14 12:40:00', 'particular', NULL, NULL),
(12, 'Roberto', 'Hernandez', 'robertoHernandez@gmail.com', 'EFG123', '2024-11-14 12:45:00', 'empresa', NULL, NULL),
(13, 'usuario prueba', 'prueba', 'usuarioprueba@gmail.com', '$2y$10$KjcRPomkKKT0EjLqza3sYu.NsdWOGqwzzSCSMyYT24qSDBdG7Cse.', '2024-11-21 16:15:48', 'empresa', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_grupos`
--

CREATE TABLE `usuarios_grupos` (
  `usuarios_id` int(11) NOT NULL,
  `grupos_id` int(11) NOT NULL,
  `fecha_incorporacion` date NOT NULL,
  `rol` enum('administrador','miembro') NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_grupos`
--

INSERT INTO `usuarios_grupos` (`usuarios_id`, `grupos_id`, `fecha_incorporacion`, `rol`, `estado`) VALUES
(1, 1, '2024-11-14', 'administrador', 'activo'),
(2, 2, '2024-11-15', 'miembro', 'activo'),
(3, 3, '2024-11-16', 'miembro', 'activo'),
(4, 4, '2024-11-17', 'miembro', 'activo'),
(5, 5, '2024-11-18', 'miembro', 'activo'),
(6, 6, '2024-11-19', 'miembro', 'activo'),
(7, 7, '2024-11-20', 'miembro', 'activo'),
(8, 8, '2024-11-21', 'miembro', 'activo'),
(9, 9, '2024-11-22', 'miembro', 'activo'),
(10, 10, '2024-11-23', 'miembro', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viajes`
--

CREATE TABLE `viajes` (
  `id_viaje` int(11) NOT NULL,
  `nombre_viaje` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `presupuesto_base` decimal(10,2) NOT NULL,
  `estado` enum('planificado','en curso','finalizado') NOT NULL DEFAULT 'planificado',
  `id_usuario` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `viajes`
--

INSERT INTO `viajes` (`id_viaje`, `nombre_viaje`, `fecha_inicio`, `fecha_final`, `presupuesto_base`, `estado`, `id_usuario`, `fecha_creacion`) VALUES
(1, 'Viaje a Madrid España', '2024-11-16', '2024-11-20', 500.00, 'planificado', 13, '2024-11-14 09:44:06'),
(2, 'Viaje a Valencia', '2024-11-21', '2024-11-30', 800.00, 'en curso', 13, '2024-11-14 11:32:53'),
(3, 'Viaje a Mallorca', '2024-10-15', '2024-10-24', 1000.00, 'finalizado', 13, '2024-09-09 12:00:00'),
(4, 'Viaje a Barcelona', '2024-10-01', '2024-10-16', 1200.00, 'en curso', 13, '2024-09-25 12:05:00'),
(5, 'Viaje a Madrid', '2024-12-10', '2024-12-15', 1500.00, 'planificado', 5, '2024-11-14 12:10:00'),
(6, 'Viaje a Granada', '2024-12-20', '2024-12-25', 700.00, 'planificado', 6, '2024-11-14 12:15:00'),
(7, 'Viaje a Sevilla', '2025-01-05', '2025-01-10', 850.00, 'en curso', 13, '2024-11-14 12:20:00'),
(8, 'Viaje a Bilbao', '2025-01-15', '2025-01-20', 1300.00, 'planificado', 13, '2024-11-14 12:25:00'),
(9, 'viaje 2 de usuario 1', '2024-10-01', '2024-10-10', 400.00, 'planificado', 1, '2024-09-18 13:19:31'),
(17, 'viaje 3', '2024-11-24', '2024-11-30', 344.00, 'planificado', 5, '2024-11-20 00:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id_actividad`),
  ADD KEY `fk_actividades_viajes` (`viajes_id_viajes`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_grupo`),
  ADD KEY `fk_grupos_viajes` (`viajes_id_viajes`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `usuarios_grupos`
--
ALTER TABLE `usuarios_grupos`
  ADD PRIMARY KEY (`usuarios_id`,`grupos_id`),
  ADD KEY `fk_usuarios_grupos_grupos` (`grupos_id`);

--
-- Indices de la tabla `viajes`
--
ALTER TABLE `viajes`
  ADD PRIMARY KEY (`id_viaje`),
  ADD KEY `fk_viajes_usuarios` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id_actividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `viajes`
--
ALTER TABLE `viajes`
  MODIFY `id_viaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `fk_actividades_viajes` FOREIGN KEY (`viajes_id_viajes`) REFERENCES `viajes` (`id_viaje`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD CONSTRAINT `fk_grupos_viajes` FOREIGN KEY (`viajes_id_viajes`) REFERENCES `viajes` (`id_viaje`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios_grupos`
--
ALTER TABLE `usuarios_grupos`
  ADD CONSTRAINT `fk_usuarios_grupos_grupos` FOREIGN KEY (`grupos_id`) REFERENCES `grupos` (`id_grupo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuarios_grupos_usuarios` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `viajes`
--
ALTER TABLE `viajes`
  ADD CONSTRAINT `fk_viajes_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
