SET NAMES utf8;

DROP TABLE IF EXISTS Categorie;

CREATE TABLE Categorie (
    
    Titre VARCHAR(30) NOT NULL PRIMARY KEY, -- clé primaire
    Description_cat TEXT
    PseudoAdmin VARCHAR(30) NOT NULL -- foreign key

    -- CONTRAINTES D'INTEGRITE

    CONSTRAINT fk_admin             
        FOREIGN KEY (PseudoAdmin)         
        REFERENCES Administrateur(Pseudo)
    

) ENGINE=InnoDB;