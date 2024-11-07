-- Table Commande
DROP TABLE IF EXISTS `COMMANDE`;
CREATE TABLE `COMMANDE`(
	`numcom` int(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`numtab` int(4),
	`datcom` date,
	`nbpers` int(2),
	`datpaie` datetime,
	`modpaie` varchar(15),
	`montcom` decimal(8,2)
	);


-- Tuples de Commande
INSERT INTO `COMMANDE` (`numcom`,`numtab`,`datcom`,`nbpers`,`datpaie`,`modpaie`,`montcom`) VALUES
(100,10,STR_TO_DATE('10/09/2016','%d/%m/%Y'),2,STR_TO_DATE('10/09/2016 20:50','%d/%m/%Y %H:%i'),'Carte',null),
(101,11,STR_TO_DATE('10/09/2016','%d/%m/%Y'),4,STR_TO_DATE('10/09/2016 21:20','%d/%m/%Y %H:%i'),'Chèque',null),
(102,17,STR_TO_DATE('10/09/2016','%d/%m/%Y'),2,STR_TO_DATE('10/09/2016 20:55','%d/%m/%Y %H:%i'),'Carte',null),
(103,12,STR_TO_DATE('10/09/2016','%d/%m/%Y'),2,STR_TO_DATE('10/09/2016 21:10','%d/%m/%Y %H:%i'),'Espèces',null),
(104,18,STR_TO_DATE('10/09/2016','%d/%m/%Y'),1,STR_TO_DATE('10/09/2016 21:00','%d/%m/%Y %H:%i'),'Chèque',null),
(105,10,STR_TO_DATE('10/09/2016','%d/%m/%Y'),2,STR_TO_DATE('10/09/2016 20:45','%d/%m/%Y %H:%i'),'Carte',null),
(106,14,STR_TO_DATE('11/10/2016','%d/%m/%Y'),2,STR_TO_DATE('11/10/2016 22:45','%d/%m/%Y %H:%i'),'Carte',null),
(107,10,STR_TO_DATE('11/10/2016','%d/%m/%Y'),2,STR_TO_DATE('11/10/2016 16:45','%d/%m/%Y %H:%i'),'Carte',10);



-- Table Plat
DROP TABLE IF EXISTS `PLAT`;
CREATE TABLE `PLAT`(
	`numplat` int(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`libelle` varchar(40),
	`type` varchar(15),
	`prixunit` decimal(6,2)
	);

-- Tuples de Plat

INSERT INTO `PLAT` (`numplat`,`libelle`,`type`,`prixunit`) VALUES
(1,'assiette de crudités','Entrée',25),
(2,'tarte de saison','Dessert',25),
(3,'sorbet mirabelle','Dessert',35),
(4,'filet de boeuf','Viande',62),
(5,'salade verte','Entrée',15),
(6,'chevre chaud','Entrée',21),
(7,'pate lorrain','Entrée',25),
(8,'saumon fumé','Entrée',30),
(9,'entrecote printaniere','Viande',58),
(10,'gratin dauphinois','Plat',42),
(11,'brochet à l''oseille','Poisson',68),
(12,'gigot d''agneau','Viande',56),
(13,'crème caramel','Dessert',15),
(14,'munster au cumin','Fromage',18),
(15,'filet de sole au beurre','Poisson',70),
(16,'fois gras de lorraine','Entrée',61);



-- Table Serveur
DROP TABLE IF EXISTS `SERVEUR`;
CREATE TABLE `SERVEUR`(
	`numserv` int(2) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`nomserv` varchar(25),
	`grade` varchar(20)
	);

-- Tuples de Serveur
INSERT INTO `SERVEUR` (`numserv`,`nomserv`,`grade`) VALUES 
(1,'Tutus Peter','maitre hotel'),
(2,'Lilo Vito','serveur g1'),
(3,'Don Carl','serveur g2'),
(4,'Leo Jon','serveur g1'),
(5,'Dean Geak','chef serveur');


-- Table Tabl
DROP TABLE IF EXISTS `TABL`;
CREATE TABLE `TABL` (
	`numtab` int(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`nbplace` int(2)
	);


-- Tuples de Tabl
INSERT INTO `TABL` (`numtab`,`nbplace`) VALUES
(10,4),
(11,6),
(12,8),
(13,4),
(14,6),
(15,4),
(16,4),
(17,6),
(18,2),
(19,4);

-- Table CONTIENT
DROP TABLE IF EXISTS `CONTIENT`;
CREATE TABLE `CONTIENT`(
	`numcom` int(4) NOT NULL,
	`numplat` int(4) NOT NULL,
	`quantite` int(2),
	PRIMARY KEY (`numcom`,`numplat`)
	);

-- Tuples de Contient

INSERT INTO `CONTIENT` (`numcom`,`numplat`,`quantite`) VALUES
(100,4,2),
(100,5,2),
(100,13,1),
(100,3,1),
(101,7,2),
(101,16,2),
(101,12,2),
(101,15,2),
(101,2,2),
(101,3,2),
(102,1,2),
(102,10,2),
(102,14,2),
(102,2,1),
(102,3,1),
(103,9,2),
(103,14,2),
(103,2,1),
(103,3,1),
(104,7,1),
(104,11,1),
(104,14,1),
(104,3,1),
(105,3,2),
(106,3,2),
(107,3,10);

-- Table Affecter
DROP TABLE IF EXISTS `AFFECTER`;
CREATE TABLE `AFFECTER`(
	`numtab` int(4),
	`dataff` date,
	`numserv` int(2),
	PRIMARY KEY (`numtab`,`dataff`)
	);

-- Tuples de Affecter

INSERT INTO `AFFECTER` (`numtab`,`dataff`,`numserv`) VALUES
(10,STR_TO_DATE('10/09/2016','%d/%m/%Y'),1),
(11,STR_TO_DATE('10/09/2016','%d/%m/%Y'),1),
(12,STR_TO_DATE('10/09/2016','%d/%m/%Y'),1),
(17,STR_TO_DATE('10/09/2016','%d/%m/%Y'),2),
(18,STR_TO_DATE('10/09/2016','%d/%m/%Y'),2),
(15,STR_TO_DATE('10/09/2016','%d/%m/%Y'),3),
(16,STR_TO_DATE('10/09/2016','%d/%m/%Y'),3),
(10,STR_TO_DATE('11/09/2016','%d/%m/%Y'),1),
(10,STR_TO_DATE('11/10/2016','%d/%m/%Y'),1),
(14,STR_TO_DATE('11/10/2016','%d/%m/%Y'),1);


-- Table Auditer
DROP TABLE IF EXISTS `AUDITER`;
CREATE TABLE `AUDITER`(
	`numcom` int(4),
	`numtab` int(4),
	`datcom` date,
	`nbpers` int(2),
	`datpaie` date,
	`montcom` decimal(8,2),
	PRIMARY KEY (`numcom`)
	);


-- Clé étrengère pour la table COMMANDE (relation avec TABL)
ALTER TABLE `COMMANDE` 
ADD CONSTRAINT `fk_commande_table` FOREIGN KEY (`numtab`) REFERENCES `TABL` (`numtab`);

-- Clé étrengère pour la table CONTIENT (relation avec COMMANDE et PLAT)
ALTER TABLE `CONTIENT` 
ADD CONSTRAINT `fk_contient_commande` FOREIGN KEY (`numcom`) REFERENCES `COMMANDE` (`numcom`),
ADD CONSTRAINT `fk_contient_plat` FOREIGN KEY (`numplat`) REFERENCES `PLAT` (`numplat`);

-- Clé étrengère pour la table AFFECTER (relation avec TABL and SERVEUR)
ALTER TABLE `AFFECTER` 
ADD CONSTRAINT `fk_affecter_table` FOREIGN KEY (`numtab`) REFERENCES `TABL` (`numtab`),
ADD CONSTRAINT `fk_affecter_serveur` FOREIGN KEY (`numserv`) REFERENCES `SERVEUR` (`numserv`);
