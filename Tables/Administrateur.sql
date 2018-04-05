SET NAMES utf8;

DROP TABLE IF EXISTS Administrateur;

CREATE TABLE Administrateur (
    
    Pseudo VARCHAR(30) NOT NULL
    PRIMARY KEY (Pseudo)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;