-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 13 sep. 2019 à 01:50
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `user_id`, `friend_id`) VALUES
(1, 19, 20),
(2, 19, 21),
(3, 19, 22),
(4, 20, 19),
(6, 21, 19),
(7, 22, 19);

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
  `name` varchar(50) DEFAULT NULL,
  `date_creation` datetime(6) NOT NULL,
  `photo_profil` varchar(50) DEFAULT NULL,
  `last_message` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `discussion`
--

INSERT INTO `discussion` (`id`, `type`, `name`, `date_creation`, `photo_profil`, `last_message`) VALUES
(1, 'individual', '', '2019-09-12 06:47:52.000000', NULL, 3),
(2, 'individual', NULL, '2019-09-12 06:48:08.000000', NULL, 4),
(3, 'individual', NULL, '2019-09-12 06:48:14.000000', NULL, 5);

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `discussion_user`
--

INSERT INTO `discussion_user` (`id`, `discussion_id`, `user_id`) VALUES
(1, 1, 19),
(2, 1, 20),
(3, 2, 19),
(4, 2, 21),
(5, 3, 19),
(6, 3, 22);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `msg_text` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `date_envoi` datetime(6) NOT NULL,
  `user_id` int(10) NOT NULL,
  `discussion_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `msg_text`, `type`, `date_envoi`, `user_id`, `discussion_id`) VALUES
(1, 'Salut !', 'text', '2019-09-12 12:15:45.000000', 19, 1),
(2, 'Coucou David', 'text', '2019-09-12 12:16:56.000000', 20, 1),
(3, 'ça va David ?', 'text', '2019-09-12 12:17:42.000000', 20, 1),
(4, 'Bonjour Mr Carlo', 'text', '2019-09-13 01:56:06.000000', 19, 2),
(5, 'Hey, tu t\'appelles David ?', 'text', '2019-09-13 02:01:16.000000', 22, 3);

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
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `message_vu`
--

INSERT INTO `message_vu` (`id`, `is_seen`, `date_seen`, `user_id`, `message_id`) VALUES
(1, 1, '2019-09-12 12:19:34', 19, 1),
(2, 1, '2019-09-12 12:19:57', 20, 1),
(3, 0, NULL, 19, 2),
(4, 1, '2019-09-12 12:21:03', 20, 2),
(5, 0, NULL, 19, 3),
(6, 1, '2019-09-12 12:21:34', 20, 3),
(7, 1, '2019-09-13 02:06:17', 19, 4),
(8, 0, NULL, 21, 4),
(9, 0, NULL, 19, 5),
(10, 1, '2019-09-13 02:07:24', 22, 5);

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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `pseudo`, `password`, `email`, `date_creation`, `bio`, `photo_profil`, `actif`, `date_last_modification`) VALUES
(22, 'patrick', '6c84cbd30cf9350a990bad2bcc1bec5f', 'patrick', '2019-09-12 06:32:52.000000', NULL, NULL, 1, NULL),
(21, 'carlo', '7d6543d7862a07edf7902086f39b4b9a', 'carlo', '2019-09-12 06:32:34.000000', NULL, NULL, 1, NULL),
(20, 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', 'bob', '2019-09-12 06:32:18.000000', NULL, NULL, 1, NULL),
(19, 'david', '172522ec1028ab781d9dfd17eaca4427', 'david', '2019-09-12 06:31:43.000000', NULL, NULL, 1, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
