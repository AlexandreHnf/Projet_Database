DROP DATABASE IF EXISTS Ebay;

CREATE DATABASE Ebay; -- création de la base de données

USE Ebay;

SET NAMES utf8;

-- ============================ TABLE UTILISATEUR ============================
DROP TABLE IF EXISTS Utilisateur;

CREATE TABLE Utilisateur (
    
    UserID INT UNSIGNED NOT NULL AUTO_INCREMENT, -- clé primaire
    MotDePasse VARCHAR(30) NOT NULL,
    Pseudo VARCHAR(30) NOT NULL, 
    AdresseMail VARCHAR(320) NOT NULL, -- 320 = 64 (avant @) + 1 (@) + 255 (apres @)
    Description_user TEXT default NULL, -- pas de foreign key vers Admin car on stocke pas de "supprime"

    PRIMARY KEY (UserID)
    

);


LOAD DATA LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/users.txt'
INTO TABLE Utilisateur
FIELDS TERMINATED BY ', '
LINES TERMINATED BY '\n' 
(@ignore, MotDePasse, Pseudo, AdresseMail);

LOAD DATA LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/sellers.txt'
INTO TABLE Utilisateur
FIELDS TERMINATED BY ', '
LINES TERMINATED BY '\n' 
(@ignore, @ignore, MotDePasse, Pseudo, @ignore, @ignore, AdresseMail);


-- ============================ TABLE ADMIN ============================
DROP TABLE IF EXISTS Administrateur;

CREATE TABLE Administrateur (
    
    AdminID INT UNSIGNED NOT NULL AUTO_INCREMENT, -- clé primaire
    Pseudo VARCHAR(30) NOT NULL default '',
    -- PseudoUser VARCHAR(30) NOT NULL default '', -- foreign key pour l'héritage
    UserID INT UNSIGNED NOT NULL, -- foreign key
    
    -- CONTRAINTES D'INTEGRITE

    PRIMARY KEY (AdminID),
    
    CONSTRAINT fk_admin_user
        FOREIGN KEY (UserID)         
        REFERENCES Utilisateur(UserID)

);



-- ============================ TABLE VENDEUR ============================
DROP TABLE IF EXISTS Vendeur;

CREATE TABLE Vendeur (
    
    SellerID INT UNSIGNED NOT NULL AUTO_INCREMENT, -- clé primaire
    Pseudo VARCHAR(30) NOT NULL,
    Nom CHAR(30) NOT NULL,
    Prenom CHAR(30) NOT NULL,
    DateNaissance DATE NOT NULL,
    Adresse TEXT NOT NULL,
    -- PseudoUser VARCHAR(30) NOT NULL, -- foreign key pour l'héritage
    UserID INT UNSIGNED NOT NULL, -- foreign key

    -- CONTRAINTES D'INTEGRITE

    PRIMARY KEY (SellerID),

    CONSTRAINT fk_vendeur_user               -- On donne un nom à notre clé
        FOREIGN KEY (UserID)             -- Colonne sur laquelle on crée la clé
        REFERENCES Utilisateur(UserID)       -- Colonne de référence

);


LOAD DATA LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/sellers.txt'
INTO TABLE Vendeur
FIELDS TERMINATED BY ', '
LINES TERMINATED BY '\n' 
(@ignore, Prenom, Nom, Pseudo, MotDePasse, DateNaissance, Adresse, AdresseMail);


-- ============================ TABLE OBJET ============================
DROP TABLE IF EXISTS Objet;

CREATE TABLE Objet (
    
    ItemID INT UNSIGNED NOT NULL AUTO_INCREMENT, -- clé primaire
    Titre CHAR(30) NOT NULL,
    Description_obj TEXT,
    DateMiseEnVente DATE,
    PrixMin DECIMAL(6,2) NOT NULL,
    Vendeur VARCHAR(30),
    DateVente DATE,
    Acheteur VARCHAR(30),
    --PseudoVendeur VARCHAR(30) NOT NULL, -- foreign key
    SellerID INT UNSIGNED NOT NULL, -- foreign key
    -- On stocke plus AdminPseudo du coup vu qu'on sauve pas les supprime

    -- CONTRAINTES D'INTEGRITE
    PRIMARY KEY (ItemID),
    
    CONSTRAINT fk_obj_vendeur             -- On donne un nom à notre clé
        FOREIGN KEY (SellerID)       -- Colonne sur laquelle on crée la clé
        REFERENCES Vendeur(SellerID)        -- Colonne de référence

);


LOAD DATA LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/items.txt'
INTO TABLE Objet
FIELDS TERMINATED BY ', '
LINES TERMINATED BY '\n'
(@ignore, Vendeur, Titre, Description_obj, @ignore, PrixMin, DateMiseEnVente);


-- ============================ TABLE EVALUATION ============================
DROP TABLE IF EXISTS Evaluation;

CREATE TABLE Evaluation (
    
    Numero INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, -- clé primaire
    Note SMALLINT NOT NULL,
    Date_eval DATETIME NOT NULL,
    Commentaire TEXT,
    -- TitreObj CHAR(30) NOT NULL, -- foreign key
    -- PseudoVendeur VARCHAR(30) NOT NULL, -- foreign key
    -- PseudoUser VARCHAR(30) NOT NULL, -- foreign key
    ItemID INT UNSIGNED NOT NULL, -- foreign key
    SellerID INT UNSIGNED NOT NULL, -- foreign key
    UserID INT UNSIGNED NOT NULL, -- foreign key

    -- CONTRAINTES D'INTEGRITE

    CONSTRAINT fk_obj_eval                -- On donne un nom à notre clé
        FOREIGN KEY (ItemID)            -- Colonne sur laquelle on crée la clé
        REFERENCES Objet(ItemID),          -- Colonne de référence

    CONSTRAINT fk_eval_vendeur                
        FOREIGN KEY (SellerID)      
        REFERENCES Vendeur(SellerID),       

    CONSTRAINT fk_eval_user            
        FOREIGN KEY (UserID)         
        REFERENCES Utilisateur(UserID)   


);



-- ============================ TABLE CATEGORIE ============================
DROP TABLE IF EXISTS Categorie;

CREATE TABLE Categorie (
    
    Titre VARCHAR(30) NOT NULL, -- clé primaire
    Description_cat TEXT,
    -- PseudoAdmin VARCHAR(30) NOT NULL, -- foreign key
    AdminID INT UNSIGNED NOT NULL, -- foreign key

    -- CONTRAINTES D'INTEGRITE

    PRIMARY KEY (Titre),

    CONSTRAINT fk_admin_cat           
        FOREIGN KEY (AdminID)         
        REFERENCES Administrateur(AdminID)
    

);



-- ============================ TABLE PROP ACHAT ============================
DROP TABLE IF EXISTS PropositionAchat;

CREATE TABLE PropositionAchat (
    
    -- TitreObjet CHAR(30) NOT NULL, -- clé primaire
    ItemID INT UNSIGNED NOT NULL, -- clé primaire
    AcheteurPotentiel VARCHAR(30),
    PrixPropose DECIMAL(6,2) NOT NULL,
    Etat TINYINT(1) NOT NULL,
    -- TitreObj CHAR(30) NOT NULL, -- foreign key
    -- PseudoUser VARCHAR(30) NOT NULL, -- foreign key
    UserID INT UNSIGNED NOT NULL, -- foreign key
    
    PRIMARY KEY (ItemID),

    -- CONSTRAINT fk_obj_prop          
    --     FOREIGN KEY (TitreObj)         
    --     REFERENCES Objet(Titre),

    CONSTRAINT fk_user_prop          
        FOREIGN KEY (UserID)         
        REFERENCES Utilisateur(UserID)

);


DROP TABLE IF EXISTS Modification; -- Un admin modifie (0,n) catégorie(s)

CREATE TABLE Modification (

    TitreCategorie VARCHAR(30) NOT NULL default '',
    -- PseudoAdmin VARCHAR(30) NOT NULL default '', 
    AdminID INT UNSIGNED NOT NULL,
    -- 2 foreign keys qui forment la clé primaire
    DateModif DATETIME NOT NULL,

    PRIMARY KEY(TitreCategorie, AdminID),

    CONSTRAINT fk_titre_cat
        FOREIGN KEY (TitreCategorie)
        REFERENCES Categorie(Titre),

    CONSTRAINT fk_admin_modif
        FOREIGN KEY (AdminID)
        REFERENCES Administrateur(AdminID)

    
);




DROP TABLE IF EXISTS Appartenance; -- Un objet appartient a (1,n) catégorie(s)

CREATE TABLE Appartenance (

    -- TitreObj CHAR(30) NOT NULL default '',
    ItemID INT UNSIGNED NOT NULL, 
    TitreCategorie VARCHAR(30) NOT NULL default '', 
    -- 2 foreign keys qui forment la clé primaire

    PRIMARY KEY(ItemID, TitreCategorie),

    CONSTRAINT fk_titre_app
        FOREIGN KEY (ItemID)
        REFERENCES Objet(ItemID),

    CONSTRAINT fk_cat_app
        FOREIGN KEY (TitreCategorie)
        REFERENCES Categorie(Titre)

    
);




DROP TABLE IF EXISTS Suppression; -- Un objet appartient a (1,n) catégorie(s)

CREATE TABLE Suppression (

    -- PseudoAdmin VARCHAR(30) NOT NULL default '',
    -- PseudoUser VARCHAR(30) NOT NULL default '', -- 2 foreign keys qui forment la clé primaire
    AdminID INT UNSIGNED NOT NULL,
    UserID INT UNSIGNED NOT NULL,
    DateSup DATETIME NOT NULL,

    PRIMARY KEY(PseudoAdmin, PseudoUser),

    CONSTRAINT fk_admin_suppr
        FOREIGN KEY (AdminID)
        REFERENCES Administrateur(AdminID),

    CONSTRAINT fk_suppr_user
        FOREIGN KEY (UserID)
        REFERENCES Utilisateur(UserID)

    
);

LOAD DATA LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/items.txt'
INTO TABLE Objet
FIELDS TERMINATED BY ', '
LINES TERMINATED BY '\n'
(@ignore, Vendeur, Titre, Description_obj, @ignore, PrixMin, DateMiseEnVente);