-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2024 at 12:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `goodfood`
--

-- --------------------------------------------------------

--
-- Table structure for table `affecter`
--

CREATE TABLE `affecter` (
  `numtab` int(4) NOT NULL,
  `dataff` date NOT NULL,
  `numserv` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `affecter`
--

INSERT INTO `affecter` (`numtab`, `dataff`, `numserv`) VALUES
(10, '2016-09-10', 1),
(10, '2016-09-11', 1),
(10, '2016-10-11', 1),
(11, '2016-09-10', 1),
(12, '2016-09-10', 1),
(14, '2016-10-11', 1),
(17, '2016-09-10', 2),
(18, '2016-09-10', 2),
(15, '2016-09-10', 3),
(16, '2016-09-10', 3);

-- --------------------------------------------------------

--
-- Table structure for table `auditer`
--

CREATE TABLE `auditer` (
  `numcom` int(4) NOT NULL,
  `numtab` int(4) DEFAULT NULL,
  `datcom` date DEFAULT NULL,
  `nbpers` int(2) DEFAULT NULL,
  `datpaie` date DEFAULT NULL,
  `montcom` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auditer`
--

INSERT INTO `auditer` (`numcom`, `numtab`, `datcom`, `nbpers`, `datpaie`, `montcom`) VALUES
(107, 10, '2016-10-11', 2, '2016-10-11', 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

CREATE TABLE `commande` (
  `numcom` int(4) NOT NULL,
  `numtab` int(4) DEFAULT NULL,
  `datcom` date DEFAULT NULL,
  `nbpers` int(2) DEFAULT NULL,
  `datpaie` datetime DEFAULT NULL,
  `modpaie` varchar(15) DEFAULT NULL,
  `montcom` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commande`
--

INSERT INTO `commande` (`numcom`, `numtab`, `datcom`, `nbpers`, `datpaie`, `modpaie`, `montcom`) VALUES
(100, 10, '2016-09-10', 2, '2016-09-10 20:50:00', 'Carte', 204.00),
(101, 11, '2016-09-10', 4, '2016-09-10 21:20:00', 'Chèque', 544.00),
(102, 17, '2016-09-10', 2, '2016-09-10 20:55:00', 'Carte', 230.00),
(103, 12, '2016-09-10', 2, '2016-09-10 21:10:00', 'Espèces', 212.00),
(104, 18, '2016-09-10', 1, '2016-09-10 21:00:00', 'Chèque', 146.00),
(105, 10, '2016-09-10', 2, '2016-09-10 20:45:00', 'Carte', 70.00),
(106, 14, '2016-10-11', 2, '2016-10-11 22:45:00', 'Carte', 70.00),
(107, 10, '2016-10-11', 2, '2016-10-11 16:45:00', 'Carte', NULL);

--
-- Triggers `commande`
--
DELIMITER $$
CREATE TRIGGER `auditer` AFTER INSERT ON `commande` FOR EACH ROW BEGIN
    DECLARE grade VARCHAR(20);
    
    SELECT s.grade INTO grade
    FROM SERVEUR s
    JOIN AFFECTER a ON s.numserv = a.numserv
    WHERE a.numtab = NEW.numtab AND a.dataff = NEW.datcom;

    IF grade = 'maitre hotel' AND (NEW.montcom / NEW.nbpers) < 15 THEN
        INSERT INTO AUDITER (numcom, numtab, datcom, nbpers, datpaie, montcom)
        VALUES (NEW.numcom, NEW.numtab, NEW.datcom, NEW.nbpers, NEW.datpaie, NEW.montcom);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `contient`
--

CREATE TABLE `contient` (
  `numcom` int(4) NOT NULL,
  `numplat` int(4) NOT NULL,
  `quantite` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contient`
--

INSERT INTO `contient` (`numcom`, `numplat`, `quantite`) VALUES
(100, 3, 1),
(100, 4, 2),
(100, 5, 2),
(100, 13, 1),
(101, 2, 2),
(101, 3, 2),
(101, 7, 2),
(101, 12, 2),
(101, 15, 2),
(101, 16, 2),
(102, 1, 2),
(102, 2, 1),
(102, 3, 1),
(102, 10, 2),
(102, 14, 2),
(103, 2, 1),
(103, 3, 1),
(103, 9, 2),
(103, 14, 2),
(104, 3, 1),
(104, 7, 1),
(104, 11, 1),
(104, 14, 1),
(105, 3, 2),
(106, 3, 2);

--
-- Triggers `contient`
--
DELIMITER $$
CREATE TRIGGER `quantitee` BEFORE INSERT ON `contient` FOR EACH ROW BEGIN
    DECLARE nb_personnes INT;
    
    SELECT nbpers INTO nb_personnes
    FROM COMMANDE
    WHERE numcom = NEW.numcom;
    
    IF NEW.quantite > nb_personnes THEN
    	SIGNAL SQLSTATE '45000' 
		SET MESSAGE_TEXT = 'La quantité du plat ne doit pas dépasser le nombre de personnes dans la commande';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `plat`
--

CREATE TABLE `plat` (
  `numplat` int(4) NOT NULL,
  `libelle` varchar(40) DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `prixunit` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plat`
--

INSERT INTO `plat` (`numplat`, `libelle`, `type`, `prixunit`) VALUES
(1, 'assiette de crudités', 'Entrée', 25.00),
(2, 'tarte de saison', 'Dessert', 25.00),
(3, 'sorbet mirabelle', 'Dessert', 35.00),
(4, 'filet de boeuf', 'Viande', 62.00),
(5, 'salade verte', 'Entrée', 15.00),
(6, 'chevre chaud', 'Entrée', 21.00),
(7, 'pate lorrain', 'Entrée', 25.00),
(8, 'saumon fumé', 'Entrée', 30.00),
(9, 'entrecote printaniere', 'Viande', 58.00),
(10, 'gratin dauphinois', 'Plat', 42.00),
(11, 'brochet à l\'oseille', 'Poisson', 68.00),
(12, 'gigot d\'agneau', 'Viande', 56.00),
(13, 'crème caramel', 'Dessert', 15.00),
(14, 'munster au cumin', 'Fromage', 18.00),
(15, 'filet de sole au beurre', 'Poisson', 70.00),
(16, 'fois gras de lorraine', 'Entrée', 61.00);

-- --------------------------------------------------------

--
-- Table structure for table `serveur`
--

CREATE TABLE `serveur` (
  `numserv` int(2) NOT NULL,
  `nomserv` varchar(25) DEFAULT NULL,
  `grade` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `serveur`
--

INSERT INTO `serveur` (`numserv`, `nomserv`, `grade`) VALUES
(1, 'Tutus Peter', 'maitre hotel'),
(2, 'Lilo Vito', 'serveur g1'),
(3, 'Don Carl', 'serveur g2'),
(4, 'Leo Jon', 'serveur g1'),
(5, 'Dean Geak', 'chef serveur');

-- --------------------------------------------------------

--
-- Table structure for table `tabl`
--

CREATE TABLE `tabl` (
  `numtab` int(4) NOT NULL,
  `nbplace` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabl`
--

INSERT INTO `tabl` (`numtab`, `nbplace`) VALUES
(10, 4),
(11, 6),
(12, 8),
(13, 4),
(14, 6),
(15, 4),
(16, 4),
(17, 6),
(18, 2),
(19, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `affecter`
--
ALTER TABLE `affecter`
  ADD PRIMARY KEY (`numtab`,`dataff`),
  ADD KEY `fk_affecter_serveur` (`numserv`);

--
-- Indexes for table `auditer`
--
ALTER TABLE `auditer`
  ADD PRIMARY KEY (`numcom`);

--
-- Indexes for table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`numcom`),
  ADD KEY `fk_commande_table` (`numtab`);

--
-- Indexes for table `contient`
--
ALTER TABLE `contient`
  ADD PRIMARY KEY (`numcom`,`numplat`),
  ADD KEY `fk_contient_plat` (`numplat`);

--
-- Indexes for table `plat`
--
ALTER TABLE `plat`
  ADD PRIMARY KEY (`numplat`);

--
-- Indexes for table `serveur`
--
ALTER TABLE `serveur`
  ADD PRIMARY KEY (`numserv`);

--
-- Indexes for table `tabl`
--
ALTER TABLE `tabl`
  ADD PRIMARY KEY (`numtab`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commande`
--
ALTER TABLE `commande`
  MODIFY `numcom` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `plat`
--
ALTER TABLE `plat`
  MODIFY `numplat` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `serveur`
--
ALTER TABLE `serveur`
  MODIFY `numserv` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tabl`
--
ALTER TABLE `tabl`
  MODIFY `numtab` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `affecter`
--
ALTER TABLE `affecter`
  ADD CONSTRAINT `fk_affecter_serveur` FOREIGN KEY (`numserv`) REFERENCES `serveur` (`numserv`),
  ADD CONSTRAINT `fk_affecter_table` FOREIGN KEY (`numtab`) REFERENCES `tabl` (`numtab`);

--
-- Constraints for table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_table` FOREIGN KEY (`numtab`) REFERENCES `tabl` (`numtab`);

--
-- Constraints for table `contient`
--
ALTER TABLE `contient`
  ADD CONSTRAINT `fk_contient_commande` FOREIGN KEY (`numcom`) REFERENCES `commande` (`numcom`),
  ADD CONSTRAINT `fk_contient_plat` FOREIGN KEY (`numplat`) REFERENCES `plat` (`numplat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
