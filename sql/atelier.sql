-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 19 mars 2020 à 13:43
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `atelier`
--

-- --------------------------------------------------------

--
-- Structure de la table `partie`
--

DROP TABLE IF EXISTS `partie`;
CREATE TABLE IF NOT EXISTS `partie` (
  `id` varchar(200) NOT NULL,
  `token` varchar(200) NOT NULL,
  `nb_photos` int(11) NOT NULL,
  `status` int(3) NOT NULL,
  `score` int(200) DEFAULT 0,
  `pseudo` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE IF NOT EXISTS `photo` (
  `id` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `latitude` decimal(65,0) DEFAULT NULL,
  `longitude` decimal(65,0) DEFAULT NULL,
  `zoom` int(200) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `pivot_photo_serie`
--

DROP TABLE IF EXISTS `pivot_photo_serie`;
CREATE TABLE IF NOT EXISTS `pivot_photo_serie` (
  `id_photo` varchar(200) DEFAULT NULL,
  `id_serie` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `serie`
--

DROP TABLE IF EXISTS `serie`;
CREATE TABLE IF NOT EXISTS `serie` (
  `id` varchar(200) NOT NULL,
  `ville` text DEFAULT NULL,
  `map_refs` varchar(200) DEFAULT NULL,
  `dist` decimal(10,0) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `serie`
--

INSERT INTO `serie` (`id`, `ville`, `map_refs`, `dist`) VALUES
('1', 'Nancy', NULL, '3');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` varchar(200) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `motdepasse` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `motdepasse`) VALUES
('1', 'hugo.pallara@gmail.com', 'salutatous'),
('2', 'romain.day@gmail.com', 'salutatous'),
('3', 'ludo.meligner@gmail.com', 'salutatous'),
('4', 'arthur.zinni@gmail.com', 'salutatous');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
