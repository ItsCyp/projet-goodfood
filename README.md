# Projet GoodFood
> Cyprian Chailan

### Dépendance fonctionnelles :

1. numTable -> nbPlace
2. numCom -> numTable, dateCom, nbPers, datePaie, modePaie, montantCom, dateAffect
3. numPlat -> libelle, type, prixUnit
4. numServ -> nomServ, grade
5. numCom, numPlat -> quantite
6. numTable, dateAffect -> numServ

Clé minimale : numCom, numPlat

numCom, numPlat -> numTable =  
numCom, numPlat -> numTable, numPlat  
= numTable, numPlat -> numTable

G1 : TABLE (numTable, nbPlace) PRIMARY KEY numTable  
G2 : COMMANDE (numCom, numTable, dateCom, nbPers, datePaie, modePaie, montantCom) PRIMARY KEY numCom (On peut supprimer l'attribut dateAffect parce que..... ????? )  
G3 : PLAT (numPlat, libelle, type, prixUnit) PRIMARY KEY numPlat  
G4 : SERVEUR (numServ, nomServ, grade) PRIMARY KEY numServ  
G5 : CONTIENT (numCom, numPlat, quantite) PRIMARY KEY numCom, numPlat  
G6 : AFFECTER (numTable, dateAffect, numServ) PRIMARY KEY numTable, dateAffect  

### Script des triggers : 

**Trigger auditer :**
- TABLE : COMMANDE
- TIME : AFTER
- EVENT : INSERT

```sql
BEGIN
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
```

**Trigger quantitee :**
- TABLE : CONTIENT
- TIME : BEFORE
- EVENT : INSERT

```sql
BEGIN
    DECLARE nb_personnes INT;

    SELECT nbpers INTO nb_personnes
    FROM COMMANDE
    WHERE numcom = NEW.numcom;
    
    IF NEW.quantite > nb_personnes THEN
    	SIGNAL SQLSTATE '45000' 
		SET MESSAGE_TEXT = 'La quantité du plat ne doit pas dépasser le nombre de personnes dans la commande';
    END IF;
END
```

