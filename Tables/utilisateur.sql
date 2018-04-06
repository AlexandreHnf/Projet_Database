SET NAMES utf8;

DROP TABLE IF EXISTS Utilisateur;

CREATE TABLE Utilisateur (
    
    Pseudo VARCHAR(30) NOT NULL PRIMARY KEY, -- clé primaire
    AdresseMail VARCHAR(320) NOT NULL, --320 = 64 (avant @) + 1 (@) + 255 (apres @)
    MotDePasse VARCHAR(30) NOT NULL,
    Description_user TEXT,
    -- pas de foreign key vers Admin car on stocke pas de "supprime"
    

) ENGINE=InnoDB;