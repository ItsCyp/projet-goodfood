# Projet GoodFood

> Cyprian Chailan

## Description du Projet

Ce projet a pour objectif d'informatiser le système d'information de la société de restauration **GOODFOOD** afin
d'améliorer la prestation de services auprès des clients. Il comprend la modélisation des données, la création de tables
et de triggers SQL, ainsi que le développement d'une application web en PHP pour manipuler la base de données.

## Dépendance Fonctionnelles

Les dépendances fonctionnelles identifiées dans le projet sont les suivantes :

1. `numTable` -> `nbPlace`

2. `numCom` -> `numTable, dateCom, nbPers, datePaie, modePaie, montantCom, dateAffect`

3. `numPlat` -> `libelle, type, prixUnit`

4. `numServ` -> `nomServ, grade`

5. `numCom, numPlat` -> `quantite`

6. `numTable, dateAffect` -> `numServ`

## Clé Minimale

La clé minimale pour notre modèle est :

- `numCom, numPlat`

## Schéma de Relations

- G1 : `TABLE (numTable, nbPlace)` PRIMARY KEY `numTable`

- G2 : `COMMANDE (numCom, numTable, dateCom, nbPers, datePaie, modePaie, montantCom)` PRIMARY KEY `numCom`

    - Remarque : L'attribut `dateAffect` peut être supprimé parce que... (ajouter une explication ici)

- G3 : `PLAT (numPlat, libelle, type, prixUnit)` PRIMARY KEY `numPlat`

- G4 : `SERVEUR (numServ, nomServ, grade)` PRIMARY KEY `numServ`

- G5 : `CONTIENT (numCom, numPlat, quantite)` PRIMARY KEY `numCom, numPlat`

- G6 : `AFFECTER (numTable, dateAffect, numServ)` PRIMARY KEY `numTable, dateAffect`

## Script des Triggers

1. **Trigger auditer :**

- TABLE : `COMMANDE`

- TIME : `AFTER`

- EVENT : `INSERT`

```sql
BEGIN
DECLARE
grade VARCHAR(20);

SELECT s.grade
INTO grade
FROM SERVEUR s
         JOIN AFFECTER a ON s.numserv = a.numserv
WHERE a.numtab = NEW.numtab
  AND a.dataff = NEW.datcom;

IF
grade = 'maitre hotel' AND (NEW.montcom / NEW.nbpers) < 15 THEN
        INSERT INTO AUDITER (numcom, numtab, datcom, nbpers, datpaie, montcom)
        VALUES (NEW.numcom, NEW.numtab, NEW.datcom, NEW.nbpers, NEW.datpaie, NEW.montcom);
END IF;
END
```

2. **Trigger quantitee :**

- TABLE : `CONTIENT`

- TIME : `BEFORE`

- EVENT : `INSERT`

```sql
BEGIN
DECLARE
nb_personnes INT;

SELECT nbpers
INTO nb_personnes
FROM COMMANDE
WHERE numcom = NEW.numcom;

IF
NEW.quantite > nb_personnes THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'La quantité du plat ne doit pas dépasser le nombre de personnes dans la commande';
END IF;
END
```

## Programmes PHP

Les programmes PHP sont organisés pour fournir les fonctionnalités suivantes via l'API PDO :

1. **Liste des Plats Servis**

    - Permet de déterminer la liste des plats (numéro et nom du plat) servis à une période donnée (date début, date
      fin).

2. **Plats Non Commandés**

    - Affiche la liste des plats (numéro et nom du plat) qui n’ont jamais été commandés pendant une période donnée (date
      début, date fin).

3. **Serveurs par Table**

    - Établit la liste des serveurs (nom et date) ayant servi une table donnée à une période donnée (date début, date
      fin).

4. **Chiffre d'Affaires par Serveur**

    - Affiche le chiffre d’affaires et le nombre de commandes réalisés par chaque serveur (nom, chiffre d’affaire,
      nombre de commandes) en une période donnée (date début, date fin).

5. **Serveurs sans Chiffre d'Affaires**

    - Affiche la liste des serveurs (nom) n’ayant pas réalisé de chiffre d’affaires durant une période donnée (date
      début, date fin).

6. **Montant Total d'une Commande**

    - Calcule le montant total d’une commande donnée (numéro de commande) et met à jour la table `COMMANDE`.

## Structure de la Programmation PHP

Pour la programmation PHP, j'ai utilisé un dispatcher pour gérer les différentes actions et un repository pour établir
la connexion avec la base de données et exécuter les requêtes SQL.

- **Dispatcher** : Le dispatcher est utilisé pour centraliser la gestion des différentes actions de l'application. Il permet
de rediriger les requêtes vers les fonctions appropriées basées sur les actions demandées par l'utilisateur.

- **Repository** : Le repository est responsable de l'interaction avec la base de données. Il contient les méthodes
nécessaires pour effectuer les opérations SQL, telles que la sélection, l'insertion, la mise à jour et la suppression
des données.

Cette approche permet de structurer le code de manière modulaire et maintenable, facilitant ainsi l'ajout de nouvelles
fonctionnalités et le maintien du code existant.

## Utilisation de l'Application

Il y a plusieurs actions que vous pouvez effectuer avec l'application web pour manipuler la base de données en utilisant
les fonctionnalités demandées. Dans chaque fonctionnalité, il est nécessaire de saisir une date de début et une date de
fin pour afficher les résultats.

J'ai mis en place un formulaire pour saisir ces dates. Les dates saisies sont ensuite enregistrées en cookies pour
pouvoir être réutilisées dans les autres fonctionnalités. Cela permet de gagner du temps et d'assurer une cohérence des
périodes utilisées dans l'application.
