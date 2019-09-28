-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 21 sep. 2019 à 23:44
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
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `user_id`, `friend_id`) VALUES
(31, 19, 22),
(30, 22, 19),
(29, 19, 20),
(28, 20, 19),
(27, 19, 21),
(26, 21, 19);

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

DROP TABLE IF EXISTS `demande`;
CREATE TABLE IF NOT EXISTS `demande` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `send_id` int(10) NOT NULL,
  `receive_id` int(10) NOT NULL,
  `is_accepted` tinyint(1) DEFAULT NULL,
  `date_envoi` datetime NOT NULL,
  `date_acceptation` datetime DEFAULT NULL,
  `date_refus` datetime DEFAULT NULL,
  `date_annulation` datetime DEFAULT NULL,
  `date_suppression_contact` datetime DEFAULT NULL,
  `is_seen` tinyint(10) DEFAULT '0',
  `actif` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`id`, `send_id`, `receive_id`, `is_accepted`, `date_envoi`, `date_acceptation`, `date_refus`, `date_annulation`, `date_suppression_contact`, `is_seen`, `actif`) VALUES
(16, 19, 22, 1, '2019-09-21 23:49:08', '2019-09-21 23:53:05', NULL, NULL, NULL, 0, 1),
(15, 19, 20, 1, '2019-09-21 23:48:52', '2019-09-21 23:52:43', NULL, NULL, NULL, 0, 1),
(13, 19, 21, 1, '2019-09-21 23:07:22', '2019-09-21 23:52:55', NULL, NULL, NULL, 0, 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `discussion`
--

INSERT INTO `discussion` (`id`, `type`, `name`, `date_creation`, `photo_profil`, `last_message`) VALUES
(1, 'individual', '', '2019-09-12 06:47:52.000000', NULL, 38),
(2, 'individual', '', '2019-09-12 06:48:08.000000', NULL, 34),
(3, 'individual', '', '2019-09-12 06:48:14.000000', NULL, 36);

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

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
  `msg_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin  NOT NULL,
  `type` varchar(50) NOT NULL,
  `date_envoi` datetime(6) NOT NULL,
  `user_id` int(10) NOT NULL,
  `discussion_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `msg_text`, `type`, `date_envoi`, `user_id`, `discussion_id`) VALUES
(1, 'Salut !', 'text', '2019-09-12 12:15:45.000000', 19, 1),
(2, 'Coucou David', 'text', '2019-09-12 12:16:56.000000', 20, 1),
(3, 'ça va David ?', 'text', '2019-09-12 12:17:42.000000', 20, 1),
(4, 'Bonjour Mr Carlo', 'text', '2019-09-13 01:56:06.000000', 19, 2),
(5, 'Hey, tu t\'appelles David ?', 'text', '2019-09-13 02:01:16.000000', 22, 3),
(20, 'c', 'text', '2019-09-14 23:42:44.000000', 19, 2),
(19, 'r', 'text', '2019-09-14 23:42:39.000000', 19, 2),
(18, 'm', 'text', '2019-09-14 23:42:37.000000', 19, 2),
(17, 'Eh Oh !!', 'text', '2019-09-14 23:24:45.000000', 22, 3),
(16, 'Oui et toi ?', 'text', '2019-09-14 22:48:42.000000', 19, 1),
(21, 'a', 'text', '2019-09-14 23:42:45.000000', 19, 2),
(22, 'r', 'text', '2019-09-14 23:42:46.000000', 19, 2),
(23, 'l', 'text', '2019-09-14 23:42:47.000000', 19, 2),
(24, 'o', 'text', '2019-09-14 23:42:48.000000', 19, 2),
(25, 'o', 'text', '2019-09-14 23:42:50.000000', 19, 2),
(26, '', 'text', '2019-09-14 23:42:50.000000', 19, 2),
(27, '', 'text', '2019-09-14 23:42:51.000000', 19, 2),
(28, '', 'text', '2019-09-14 23:42:51.000000', 19, 2),
(29, '', 'text', '2019-09-14 23:42:51.000000', 19, 2),
(30, 'o', 'text', '2019-09-14 23:42:53.000000', 19, 2),
(31, '', 'text', '2019-09-14 23:42:55.000000', 19, 2),
(32, 'ha', 'text', '2019-09-14 23:42:58.000000', 19, 2),
(33, 'ha', 'text', '2019-09-14 23:42:59.000000', 19, 2),
(34, 'ha', 'text', '2019-09-14 23:43:00.000000', 19, 2),
(35, 'hey', 'text', '2019-09-15 08:24:22.000000', 19, 1),
(36, 'So... What\'s up', 'text', '2019-09-15 08:29:27.000000', 19, 3),
(37, 'test', 'text', '2019-09-22 01:12:24.000000', 19, 1),
(38, 'test2', 'text', '2019-09-22 01:27:56.000000', 20, 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `message_vu`
--

INSERT INTO `message_vu` (`id`, `is_seen`, `date_seen`, `user_id`, `message_id`) VALUES
(1, 1, '2019-09-22 00:51:34', 19, 1),
(2, 1, '2019-09-12 12:19:57', 20, 1),
(3, 1, '2019-09-22 01:00:07', 19, 2),
(4, 1, '2019-09-12 12:21:03', 20, 2),
(5, 1, '2019-09-22 01:00:07', 19, 3),
(6, 1, '2019-09-12 12:21:34', 20, 3),
(7, 1, '2019-09-13 02:06:17', 19, 4),
(8, 0, NULL, 21, 4),
(9, 1, '2019-09-22 00:46:35', 19, 5),
(10, 1, '2019-09-13 02:07:24', 22, 5),
(18, 1, '2019-09-22 00:46:35', 19, 17),
(17, 0, NULL, 20, 16),
(16, 1, '2019-09-22 00:51:34', 19, 16),
(19, 1, '2019-09-14 23:24:45', 22, 17),
(20, 1, '2019-09-14 23:42:37', 19, 18),
(21, 0, NULL, 21, 18),
(22, 1, '2019-09-14 23:42:39', 19, 19),
(23, 0, NULL, 21, 19),
(24, 1, '2019-09-14 23:42:44', 19, 20),
(25, 0, NULL, 21, 20),
(26, 1, '2019-09-14 23:42:45', 19, 21),
(27, 0, NULL, 21, 21),
(28, 1, '2019-09-14 23:42:46', 19, 22),
(29, 0, NULL, 21, 22),
(30, 1, '2019-09-14 23:42:47', 19, 23),
(31, 0, NULL, 21, 23),
(32, 1, '2019-09-14 23:42:48', 19, 24),
(33, 0, NULL, 21, 24),
(34, 1, '2019-09-14 23:42:50', 19, 25),
(35, 0, NULL, 21, 25),
(36, 1, '2019-09-14 23:42:50', 19, 25),
(37, 0, NULL, 21, 25),
(38, 1, '2019-09-14 23:42:51', 19, 27),
(39, 0, NULL, 21, 27),
(40, 1, '2019-09-14 23:42:51', 19, 27),
(41, 0, NULL, 21, 27),
(42, 1, '2019-09-14 23:42:51', 19, 27),
(43, 0, NULL, 21, 27),
(44, 1, '2019-09-14 23:42:53', 19, 30),
(45, 0, NULL, 21, 30),
(46, 1, '2019-09-14 23:42:55', 19, 31),
(47, 0, NULL, 21, 31),
(48, 1, '2019-09-14 23:42:58', 19, 32),
(49, 0, NULL, 21, 32),
(50, 1, '2019-09-14 23:42:59', 19, 33),
(51, 0, NULL, 21, 33),
(52, 1, '2019-09-14 23:43:00', 19, 34),
(53, 0, NULL, 21, 34),
(54, 1, '2019-09-22 00:51:34', 19, 35),
(55, 0, NULL, 20, 35),
(56, 1, '2019-09-22 00:46:35', 19, 36),
(57, 0, NULL, 22, 36),
(58, 1, '2019-09-22 01:12:24', 19, 37),
(59, 0, NULL, 20, 37),
(60, 1, '2019-09-22 01:29:51', 19, 38),
(61, 1, '2019-09-22 01:27:56', 20, 38);

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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `pseudo`, `password`, `email`, `date_creation`, `bio`, `photo_profil`, `actif`, `date_last_modification`) VALUES
(25, 'sacha', '954ef2a9e618b60487c20d52d6086f1c', 'sacha@gmail.com', '2019-09-22 02:22:13.000000', NULL, NULL, 1, NULL),
(22, 'patrick', '6c84cbd30cf9350a990bad2bcc1bec5f', 'patrick@gmail.com', '2019-09-12 06:32:52.000000', NULL, NULL, 1, NULL),
(21, 'carlo', '7d6543d7862a07edf7902086f39b4b9a', 'carlo', '2019-09-12 06:32:34.000000', NULL, NULL, 1, NULL),
(20, 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', 'cartable', '2019-09-12 06:32:18.000000', NULL, NULL, 1, NULL),
(19, 'david', '172522ec1028ab781d9dfd17eaca4427', 'david@gmail.com', '2019-09-12 06:31:43.000000', NULL, NULL, 1, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
