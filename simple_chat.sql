-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 11 sep. 2019 à 10:55
-- Version du serveur :  5.7.21
-- Version de PHP :  7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `simple_chat`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin_message`
--

DROP TABLE IF EXISTS `admin_message`;
CREATE TABLE IF NOT EXISTS `admin_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `type` varchar(50) NOT NULL,
  `text` text NOT NULL,
  `date_envoi` datetime NOT NULL,
  `is_admin_send` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `friend_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

DROP TABLE IF EXISTS `demande`;
CREATE TABLE IF NOT EXISTS `demande` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `send_id` int(10) NOT NULL,
  `receive_id` int(10) NOT NULL,
  `is_accepted` tinyint(1) NOT NULL,
  `date_envoi` datetime NOT NULL,
  `date_acceptation` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `discussion`
--

DROP TABLE IF EXISTS `discussion`;
CREATE TABLE IF NOT EXISTS `discussion` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_creation` datetime(6) NOT NULL,
  `photo_profil` varchar(50) NOT NULL,
  `date_last_message` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `discussion_user`
--

DROP TABLE IF EXISTS `discussion_user`;
CREATE TABLE IF NOT EXISTS `discussion_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `discussion_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `date_envoi` datetime(6) NOT NULL,
  `user_id` int(10) NOT NULL,
  `discussion_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `message_vu`
--

DROP TABLE IF EXISTS `message_vu`;
CREATE TABLE IF NOT EXISTS `message_vu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `is_seen` tinyint(1) NOT NULL DEFAULT '0',
  `date_seen` datetime DEFAULT NULL,
  `user_id` int(10) NOT NULL,
  `message_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `photo_profil`
--

DROP TABLE IF EXISTS `photo_profil`;
CREATE TABLE IF NOT EXISTS `photo_profil` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_photo` varchar(50) NOT NULL,
  `date_creation` datetime NOT NULL,
  `type` varchar(50) NOT NULL,
  `user_discussion_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `photo_profil`
--

INSERT INTO `photo_profil` (`id`, `name_photo`, `date_creation`, `type`, `user_discussion_id`) VALUES
(8, 'user8_20190831152127.jpg', '2019-08-31 18:21:27', 'user', 8),
(7, 'user8_20190831152026.jpg', '2019-08-31 18:20:26', 'user', 8),
(6, 'user8_20190831143022.jpg', '2019-08-31 17:30:22', 'user', 8);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `date_creation` datetime(6) NOT NULL,
  `bio` text,
  `photo_profil` int(10) DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `date_last_modification` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `pseudo`, `password`, `email`, `date_creation`, `bio`, `photo_profil`, `actif`, `date_last_modification`) VALUES
(6, 'David', 'hohoho', '', '2019-08-30 22:22:28.000000', NULL, NULL, 1, NULL),
(8, 'Jeantre', 'hum', '', '2019-08-31 09:48:58.000000', 'Hello body', 8, 1, '2019-08-31 18:21:27'),
(9, ':pseudo', ':password', ':email', '2019-09-11 07:14:43.000000', NULL, NULL, 1, NULL),
(10, 'django_boy', '046531ffc2408bca4ad24ac92c9644c3', 'dj@gmail.com', '2019-09-11 07:15:06.000000', NULL, NULL, 1, NULL),
(11, 'django_boy', '046531ffc2408bca4ad24ac92c9644c3', 'djd@gmail.com', '2019-09-11 07:21:14.000000', NULL, NULL, 1, NULL),
(12, ':pseudo', ':password', ':email', '2019-09-11 07:34:28.000000', NULL, NULL, 1, '2019-09-11 07:39:48'),
(13, 'django_boy', '046531ffc2408bca4ad24ac92c9644c3', 'dja@gmail.com', '2019-09-11 07:40:44.000000', NULL, NULL, 1, '2019-09-11 07:40:44'),
(14, 'gmail', 'de01c1d48db6c321c637457113ed80d5', 'gmail', '2019-09-11 13:01:19.000000', NULL, NULL, 1, '2019-09-11 13:01:19'),
(15, 'jj', 'de01c1d48db6c321c637457113ed80d5', 'gmails', '2019-09-11 13:26:32.000000', NULL, NULL, 1, '2019-09-11 13:26:32');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
