SET NAMES utf8;

DROP TABLE IF EXISTS Objet;

CREATE TABLE Objet (
    
    Titre CHAR(30) NOT NULL PRIMARY KEY, -- clé primaire
    Description_obj TEXT,
    DateMiseEnVente DATE,
    PrixMin DECIMAL(6,2) NOT NULL,
    Vendeur VARCHAR(30),
    DateVente DATE,
    Acheteur VARCHAR(30),
    PseudoVendeur VARCHAR(30) NOT NULL -- foreign key
    --On stocke plus AdminPseudo du coup vu qu'on sauve pas les "supprime"

    -- CONTRAINTES D'INTEGRITE
    
    CONSTRAINT fk_vendeur                 -- On donne un nom à notre clé
        FOREIGN KEY (PseudoVendeur)       -- Colonne sur laquelle on crée la clé
        REFERENCES Vendeur(Pseudo)        -- Colonne de référence


) ENGINE=InnoDB;