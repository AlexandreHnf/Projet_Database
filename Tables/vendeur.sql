SET NAMES utf8;

DROP TABLE IF EXISTS Vendeur;

CREATE TABLE Vendeur (
    
    Pseudo VARCHAR(30) NOT NULL PRIMARY KEY, -- clé primaire
    Nom CHAR(30) NOT NULL,
    Prenom CHAR(30) NOT NULL,
    DateNaissance DATE NOT NULL,
    Adresse TEXT NOT NULL
    PseudoUser VARCHAR(30) NOT NULL, -- foreign key

    -- CONTRAINTES D'INTEGRITE

    CONSTRAINT fk_user                       -- On donne un nom à notre clé
        FOREIGN KEY (PseudoUser)             -- Colonne sur laquelle on crée la clé
        REFERENCES Utilisateur(Pseudo)       -- Colonne de référence

) ENGINE=InnoDB;