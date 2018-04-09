DROP DATABASE IF EXISTS Ebay;

CREATE DATABASE Ebay; -- création de la base de données

USE Ebay;

SET NAMES utf8;

-- Pour importer données des fichiers XML: 
-- https://stackoverflow.com/questions/5491056/how-to-import-xml-file-into-mysql-database-table-using-xml-load-function

-- ============================ TABLE UTILISATEUR ============================
DROP TABLE IF EXISTS Utilisateur;

CREATE TABLE Utilisateur (
    
    Pseudo VARCHAR(30) NOT NULL, -- clé primaire
    AdresseMail VARCHAR(320) NOT NULL, -- 320 = 64 (avant @) + 1 (@) + 255 (apres @)
    MotDePasse VARCHAR(30) NOT NULL,
    Description_user TEXT, -- pas de foreign key vers Admin car on stocke pas de "supprime"

    PRIMARY KEY (Pseudo)
    

);




-- ============================ TABLE ADMIN ============================
DROP TABLE IF EXISTS Administrateur;

CREATE TABLE Administrateur (
    
    Pseudo VARCHAR(30) NOT NULL,
    PseudoUser VARCHAR(30) NOT NULL, -- foreign key pour l'héritage
    PRIMARY KEY (Pseudo),
    
    CONSTRAINT fk_admin_user
        FOREIGN KEY (PseudoUser)         
        REFERENCES Utilisateur(Pseudo)

);



-- ============================ TABLE VENDEUR ============================
DROP TABLE IF EXISTS Vendeur;

CREATE TABLE Vendeur (
    
    Pseudo VARCHAR(30) NOT NULL, -- clé primaire
    Nom CHAR(30) NOT NULL,
    Prenom CHAR(30) NOT NULL,
    DateNaissance DATE NOT NULL,
    Adresse TEXT NOT NULL,
    PseudoUser VARCHAR(30) NOT NULL, -- foreign key pour l'héritage

    -- CONTRAINTES D'INTEGRITE

    PRIMARY KEY (Pseudo),

    CONSTRAINT fk_vendeur_user               -- On donne un nom à notre clé
        FOREIGN KEY (PseudoUser)             -- Colonne sur laquelle on crée la clé
        REFERENCES Utilisateur(Pseudo)       -- Colonne de référence

);




-- ============================ TABLE OBJET ============================
DROP TABLE IF EXISTS Objet;

CREATE TABLE Objet (
    
    Titre CHAR(30) NOT NULL, -- clé primaire
    Description_obj TEXT,
    DateMiseEnVente DATE,
    PrixMin DECIMAL(6,2) NOT NULL,
    Vendeur VARCHAR(30),
    DateVente DATE,
    Acheteur VARCHAR(30),
    PseudoVendeur VARCHAR(30) NOT NULL, -- foreign key
    -- On stocke plus AdminPseudo du coup vu qu'on sauve pas les supprime

    -- CONTRAINTES D'INTEGRITE
    PRIMARY KEY (Titre),
    
    CONSTRAINT fk_obj_vendeur             -- On donne un nom à notre clé
        FOREIGN KEY (PseudoVendeur)       -- Colonne sur laquelle on crée la clé
        REFERENCES Vendeur(Pseudo)        -- Colonne de référence

);



-- ============================ TABLE EVALUATION ============================
DROP TABLE IF EXISTS Evaluation;

CREATE TABLE Evaluation (
    
    Numero INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, -- clé primaire
    Note SMALLINT NOT NULL,
    Date_eval DATETIME NOT NULL,
    Commentaire TEXT,
    TitreObj CHAR(30) NOT NULL, -- foreign key
    PseudoVendeur VARCHAR(30) NOT NULL, -- foreign key
    PseudoUser VARCHAR(30) NOT NULL, -- foreign key

    -- CONTRAINTES D'INTEGRITE

    CONSTRAINT fk_obj_eval                -- On donne un nom à notre clé
        FOREIGN KEY (TitreObj)            -- Colonne sur laquelle on crée la clé
        REFERENCES Objet(Titre),          -- Colonne de référence

    CONSTRAINT fk_eval_vendeur                
        FOREIGN KEY (PseudoVendeur)      
        REFERENCES Vendeur(Pseudo),       

    CONSTRAINT fk_eval_user            
        FOREIGN KEY (PseudoUser)         
        REFERENCES Utilisateur(Pseudo)   


);



-- ============================ TABLE CATEGORIE ============================
DROP TABLE IF EXISTS Categorie;

CREATE TABLE Categorie (
    
    Titre VARCHAR(30) NOT NULL, -- clé primaire
    Description_cat TEXT,
    PseudoAdmin VARCHAR(30) NOT NULL, -- foreign key

    -- CONTRAINTES D'INTEGRITE

    PRIMARY KEY (Titre),

    CONSTRAINT fk_admin_cat           
        FOREIGN KEY (PseudoAdmin)         
        REFERENCES Administrateur(Pseudo)
    

);



-- ============================ TABLE PROP ACHAT ============================
DROP TABLE IF EXISTS PropositionAchat;

CREATE TABLE PropositionAchat (
    
    TitreObjet CHAR(30) NOT NULL, -- clé primaire
    AcheteurPotentiel VARCHAR(30),
    PrixPropose DECIMAL(6,2) NOT NULL,
    Etat TINYINT(1) NOT NULL,
    TitreObj CHAR(30) NOT NULL, -- foreign key
    PseudoUser VARCHAR(30) NOT NULL, -- foreign key
    
    PRIMARY KEY (TitreObjet),

    CONSTRAINT fk_obj_prop          
        FOREIGN KEY (TitreObj)         
        REFERENCES Objet(Titre),

    CONSTRAINT fk_user_prop          
        FOREIGN KEY (PseudoUser)         
        REFERENCES Utilisateur(Pseudo)

);


DROP TABLE IF EXISTS Modification; -- Un admin modifie (0,n) catégorie(s)

CREATE TABLE Modification (

    TitreCategorie VARCHAR(30) NOT NULL,
    PseudoAdmin VARCHAR(30) NOT NULL, -- 2 foreign keys qui forment la clé primaire
    DateModif DATETIME NOT NULL,

    PRIMARY KEY(TitreCategorie, PseudoAdmin),

    CONSTRAINT fk_titre_cat
        FOREIGN KEY (TitreCategorie)
        REFERENCES Categorie(Titre),

    CONSTRAINT fk_admin_modif
        FOREIGN KEY (PseudoAdmin)
        REFERENCES Administrateur(Pseudo)

    
);




DROP TABLE IF EXISTS Appartenance; -- Un objet appartient a (1,n) catégorie(s)

CREATE TABLE Appartenance (

    TitreObj CHAR(30) NOT NULL,
    TitreCategorie VARCHAR(30) NOT NULL, -- 2 foreign keys qui forment la clé primaire

    PRIMARY KEY(TitreObj, TitreCategorie),

    CONSTRAINT fk_titre_app
        FOREIGN KEY (TitreObj)
        REFERENCES Objet(Titre),

    CONSTRAINT fk_cat_app
        FOREIGN KEY (TitreCategorie)
        REFERENCES Categorie(Titre)

    
);


