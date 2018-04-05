SET NAMES utf8;

DROP TABLE IF EXISTS PropositionAchat;

CREATE TABLE PropositionAchat (
    
    TitreObjet CHAR(30) NOT NULL PRIMARY KEY, -- clé primaire
    AcheteurPotentiel VARCHAR(30),
    PrixPropose DECIMAL(6,2) NOT NULL,
    Etat TINYINT(1) NOT NULL
    TitreObj CHAR(30) NOT NULL, -- foreign key
    PseudoUser VARCHAR(30) NOT NULL, -- foreign key
    
    CONSTRAINT fk_objet            
        FOREIGN KEY (TitreObj)         
        REFERENCES Objet(Titre)

    CONSTRAINT fk_user            
        FOREIGN KEY (PseudoUser)         
        REFERENCES Utilisateur(Pseudo)

) ENGINE=InnoDB;