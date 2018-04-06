DROP DATABASE IF EXISTS Ebay;

CREATE DATABASE Ebay; -- création de la base de données

USE Ebay;

SET NAMES utf8;

-- ============================ TABLE ADMIN ============================
DROP TABLE IF EXISTS `Administrateur`;

CREATE TABLE `Administrateur` (
    
    `Pseudo` VARCHAR(30) NOT NULL,
    PRIMARY KEY (`Pseudo`)

) ENGINE=InnoDB;



-- ============================ TABLE UTILISATEUR ============================
DROP TABLE IF EXISTS Utilisateur;

CREATE TABLE Utilisateur (
    
    Pseudo VARCHAR(30) NOT NULL PRIMARY KEY, -- clé primaire
    AdresseMail VARCHAR(320) NOT NULL, -- 320 = 64 (avant @) + 1 (@) + 255 (apres @)
    MotDePasse VARCHAR(30) NOT NULL,
    Description_user TEXT,
    -- pas de foreign key vers Admin car on stocke pas de "supprime"
    

) ENGINE=InnoDB;



-- ============================ TABLE VENDEUR ============================
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




-- ============================ TABLE OBJET ============================
DROP TABLE IF EXISTS Objet;

CREATE TABLE Objet (
    
    Titre CHAR(30) NOT NULL PRIMARY KEY, -- clé primaire
    Description_obj TEXT,
    DateMiseEnVente DATE,
    PrixMin DECIMAL(6,2) NOT NULL,
    Vendeur VARCHAR(30),
    DateVente DATE,
    Acheteur VARCHAR(30),
    PseudoVendeur VARCHAR(30) NOT NULL, -- foreign key
    -- On stocke plus AdminPseudo du coup vu qu'on sauve pas les supprime

    -- CONTRAINTES D'INTEGRITE

    CONSTRAINT fk_vendeur                 -- On donne un nom à notre clé
        FOREIGN KEY (PseudoVendeur)       -- Colonne sur laquelle on crée la clé
        REFERENCES Vendeur(Pseudo)        -- Colonne de référence


) ENGINE=InnoDB;



-- ============================ TABLE EVALUATION ============================
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



-- ============================ TABLE CATEGORIE ============================
DROP TABLE IF EXISTS Categorie;

CREATE TABLE Categorie (
    
    Titre VARCHAR(30) NOT NULL PRIMARY KEY, -- clé primaire
    Description_cat TEXT
    PseudoAdmin VARCHAR(30) NOT NULL, -- foreign key

    -- CONTRAINTES D'INTEGRITE

    CONSTRAINT fk_admin             
        FOREIGN KEY (PseudoAdmin)         
        REFERENCES Administrateur(Pseudo)
    

) ENGINE=InnoDB;



-- ============================ TABLE PROP ACHAT ============================
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



