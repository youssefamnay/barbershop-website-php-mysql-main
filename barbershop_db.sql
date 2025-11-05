-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 19 juin 2024 à 21:42
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
-- Base de données : `barbershop`
--

-- --------------------------------------------------------

--
-- Structure de la table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `appointment_id` int NOT NULL AUTO_INCREMENT,
  `date_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `client_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_expected` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `canceled` tinyint(1) NOT NULL DEFAULT '0',
  `cancellation_reason` text,
  `status` enum('ongoing','completed','canceled') DEFAULT 'ongoing',
  PRIMARY KEY (`appointment_id`),
  KEY `FK_client_appointment` (`client_id`),
  KEY `FK_employee_appointment` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `barber_admin`
--

DROP TABLE IF EXISTS `barber_admin`;
CREATE TABLE IF NOT EXISTS `barber_admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `barber_admin`
--

INSERT INTO `barber_admin` (`admin_id`, `username`, `email`, `full_name`, `password`) VALUES
(1, 'admin', 'admin.admin@gmail.com', 'Admin Admin', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `client_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `client_email` varchar(50) NOT NULL,
  `password` varchar(300) NOT NULL,
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `client_email` (`client_email`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `employee_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `phone_number`, `email`) VALUES
(1, 'Hamid', 'Mernissi', '0626278839', 'hamid@gmail.com'),
(2, 'Ahmed', 'Belkadi', '0621936672', 'ahmed@gmail.com'),
(3, 'Abdellah', 'Omari', '0626273882', 'abdellah@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `employees_schedule`
--

DROP TABLE IF EXISTS `employees_schedule`;
CREATE TABLE IF NOT EXISTS `employees_schedule` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `day_id` tinyint(1) NOT NULL,
  `from_hour` time NOT NULL,
  `to_hour` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_emp` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `employees_schedule`
--

INSERT INTO `employees_schedule` (`id`, `employee_id`, `day_id`, `from_hour`, `to_hour`) VALUES
(29, 3, 1, '09:00:00', '18:00:00'),
(30, 3, 7, '09:00:00', '17:00:00'),
(38, 2, 1, '09:00:00', '17:00:00'),
(39, 2, 6, '09:00:00', '18:00:00'),
(40, 2, 7, '09:00:00', '18:00:00'),
(47, 1, 1, '09:00:00', '18:00:00'),
(48, 1, 2, '15:00:00', '22:00:00'),
(49, 1, 3, '09:00:00', '18:00:00'),
(50, 1, 4, '00:00:00', '20:00:00'),
(51, 1, 5, '09:00:00', '14:00:00'),
(52, 1, 6, '09:00:00', '18:00:00'),
(53, 1, 7, '09:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `service_id` int NOT NULL AUTO_INCREMENT,
  `service_name` varchar(50) NOT NULL,
  `service_description` varchar(255) NOT NULL,
  `service_price` decimal(6,2) NOT NULL,
  `service_duration` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`service_id`),
  KEY `FK_service_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `service_description`, `service_price`, `service_duration`, `category_id`) VALUES
(1, 'Coupe Homme', ' Une coupe de cheveux professionnelle réalisée par nos barbiers expérimentés pour hommes. Nous vous offrons une coupe précise et personnalisée selon vos préférences, mettant en valeur votre style unique.', '50.00', 20, 4),
(2, 'Coupe junior\n', 'Une coupe adaptée aux jeunes hommes, réalisée avec soin et précision par nos experts. Nous comprenons l\'importance de trouver le bon style, même à un jeune âge, et nous sommes là pour vous aider à obtenir le look que vous désirez.', '30.00', 15, 4),
(3, 'Brushing\n', 'Profitez d\'un brushing professionnel pour hommes qui donnera à vos cheveux une apparence soignée et élégante. Nos stylistes utilisent des techniques avancées pour créer des styles qui durent toute la journée.', '100.00', 40, 4),
(4, 'Barbe', 'Un soin de barbe complet comprenant la taille, le façonnage et le rasage si nécessaire. Nos barbiers maîtrisent l\'art du toilettage de la barbe pour vous offrir un look net et bien entretenu.', '40.00', 10, 2),
(5, 'Gommage visage', 'Offrez à votre visage un traitement revitalisant avec notre gommage visage pour hommes. Ce service élimine les impuretés, exfolie en douceur et laisse votre peau fraîche et éclatante.', '90.00', 30, 2),
(6, 'Soins de visage\n', 'Profitez d\'un soin complet du visage conçu spécialement pour les hommes. Nos produits de qualité et nos techniques de massage relaxantes revitalisent la peau, la laissant hydratée, rafraîchie et éclatante.', '80.00', 30, 2),
(7, 'Kératine\n', 'Le traitement à la kératine pour hommes est conçu pour lisser et adoucir les cheveux tout en réduisant les frisottis et en ajoutant de la brillance. Obtenez des cheveux plus faciles à coiffer et plus sains avec ce traitement ', '200.00', 60, 3),
(8, 'Face Cleaning', 'Un nettoyage en profondeur du visage pour hommes, idéal pour éliminer les impuretés, l\'excès de sébum et les cellules mortes de la peau. Ce traitement laisse votre peau propre, rafraîchie et prête à affronter la journée.', '150.00', 15, 3),
(9, 'Soins de cheveux\n', 'Offrez à vos cheveux un traitement nourrissant et réparateur avec nos soins capillaires pour hommes. Nos produits de qualité professionnelle revitalisent les cheveux, les laissant doux, soyeux et pleins de vitalité.', '180.00', 20, 3);

-- --------------------------------------------------------

--
-- Structure de la table `services_booked`
--

DROP TABLE IF EXISTS `services_booked`;
CREATE TABLE IF NOT EXISTS `services_booked` (
  `appointment_id` int NOT NULL,
  `service_id` int NOT NULL,
  PRIMARY KEY (`appointment_id`,`service_id`),
  KEY `FK_SB_service` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `service_categories`
--

DROP TABLE IF EXISTS `service_categories`;
CREATE TABLE IF NOT EXISTS `service_categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `service_categories`
--

INSERT INTO `service_categories` (`category_id`, `category_name`) VALUES
(2, 'Shaving'),
(3, 'Face Masking'),
(4, 'Uncategorized');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `FK_client_appointment` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_employee_appointment` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `employees_schedule`
--
ALTER TABLE `employees_schedule`
  ADD CONSTRAINT `FK_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `FK_service_category` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`category_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `services_booked`
--
ALTER TABLE `services_booked`
  ADD CONSTRAINT `FK_appointment` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`),
  ADD CONSTRAINT `FK_SB_appointment` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_SB_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
