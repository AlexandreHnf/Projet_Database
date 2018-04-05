SET NAMES utf8;

DROP TABLE IF EXISTS PropositionAchat;

CREATE TABLE PropositionAchat (
    
    TitreObjet CHAR(30) NOT NULL,
    AcheteurPotentiel VARCHAR(30),
    PrixPropose DECIMAL(6,2) NOT NULL,
    Etat TINYINT(1) NOT NULL
    
    PRIMARY KEY (TitreObjet)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;