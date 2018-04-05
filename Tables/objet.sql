SET NAMES utf8;

DROP TABLE IF EXISTS Objet;

CREATE TABLE Objet (
    
    Titre CHAR(30) NOT NULL,
    Description_obj TEXT,
    DateMiseEnVente DATE,
    PrixMin DECIMAL(6,2) NOT NULL,
    Vendeur VARCHAR(30),
    DateVente DATE,
    Acheteur VARCHAR(30),
    
    PRIMARY KEY (Titre)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;