-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 12 juin 2023 à 10:20
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `reseau_social`
--

-- --------------------------------------------------------

--
-- Structure de la table `ficheuser`
--

DROP TABLE IF EXISTS `ficheuser`;
CREATE TABLE IF NOT EXISTS `ficheuser` (
  `id_fiche_user` int NOT NULL AUTO_INCREMENT,
  `name_fiche_user` varchar(100) NOT NULL,
  `image_fiche_user` varchar(100) NOT NULL,
  `likes_fiche_user` int NOT NULL,
  `dislikes_fiche_user` int NOT NULL,
  `usersIDLiked_fiche_user` varchar(250) NOT NULL,
  `usersIDDisliked_fiche_user` varchar(250) NOT NULL,
  `createdAt_fiche_user` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID_fiche_user` int NOT NULL,
  PRIMARY KEY (`id_fiche_user`),
  KEY `userID_fiche_user` (`userID_fiche_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`login`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `created_at`) VALUES
(2, 'rnehl1@bbc.co.uk', 'rhNInF5TOmj', '2023-06-07 15:48:21'),
(3, 'okiezler2@wp.com', 'F2kNSl3RaEG', '2023-06-07 15:48:21'),
(5, 'adon4@vimeo.com', 'jhzP4J3Z', '2023-06-07 15:48:21'),
(30, 'tesfd44@efre44.fe', 'testtest44', '2023-06-12 10:54:14');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ficheuser`
--
ALTER TABLE `ficheuser`
  ADD CONSTRAINT `ficheuser_ibfk_1` FOREIGN KEY (`userID_fiche_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
