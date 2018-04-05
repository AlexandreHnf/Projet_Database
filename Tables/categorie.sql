SET NAMES utf8;

DROP TABLE IF EXISTS Categorie;

CREATE TABLE Categorie (
    
    Titre VARCHAR(30) NOT NULL,
    Description_cat TEXT

    PRIMARY KEY (Titre)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;