SET NAMES utf8;

DROP TABLE IF EXISTS Vendeur;

CREATE TABLE Vendeur (
    
    Pseudo VARCHAR(30) NOT NULL,
    Nom CHAR(30) NOT NULL,
    Prenom CHAR(30) NOT NULL,
    DateNaissance DATE NOT NULL,
    Adresse TEXT NOT NULL

    PRIMARY KEY (Pseudo)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;