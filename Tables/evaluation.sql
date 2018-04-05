SET NAMES utf8;

DROP TABLE IF EXISTS Evaluation;

CREATE TABLE Evaluation (
    
    Numero INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, -- clé primaire
    Note SMALLINT NOT NULL,
    Date_eval DATETIME NOT NULL,
    Commentaire TEXT 
    TitreObj CHAR(30) NOT NULL, -- foreign key
    PseudoVendeur VARCHAR(30) NOT NULL, -- foreign key
    PseudoUser VARCHAR(30) NOT NULL, -- foreign key

    -- CONTRAINTES D'INTEGRITE

    CONSTRAINT fk_objet                  -- On donne un nom à notre clé
        FOREIGN KEY (TitreObj)           -- Colonne sur laquelle on crée la clé
        REFERENCES Objet(Titre)          -- Colonne de référence

    CONSTRAINT fk_vendeur                
        FOREIGN KEY (PseudoVendeur)      
        REFERENCES Vendeur(Pseudo)       

    CONSTRAINT fk_utilisateur            
        FOREIGN KEY (PseudoUser)         
        REFERENCES Utilisateur(Pseudo)   


) ENGINE=InnoDB;