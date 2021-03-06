DROP DATABASE IF EXISTS Ebay;

CREATE DATABASE Ebay CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- création de la base de données

USE Ebay;

SET NAMES utf8;

-- ============================ TABLE UTILISATEUR ============================
DROP TABLE IF EXISTS Utilisateur;

CREATE TABLE Utilisateur (
    
    UserID INT UNSIGNED NOT NULL AUTO_INCREMENT, -- clé primaire
    MotDePasse VARCHAR(255) NOT NULL,
    Pseudo VARCHAR(30) NOT NULL, 
    AdresseMail VARCHAR(255) NOT NULL, 
    Description_user TEXT default '',

    PRIMARY KEY (UserID)
    
);

INSERT INTO Utilisateur
VALUES (@ignore, SHA1('master_admin'), 'Master', 'master0@hotmail.com',
        'Administrateur suprême');

-- =========================== USERS.TXT > UTILISATEUR ===================

LOAD DATA LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/users.txt'
INTO TABLE Utilisateur
FIELDS TERMINATED BY ', '
LINES TERMINATED BY '\n' 
(UserID, MotDePasse, Pseudo, AdresseMail)
set MotDePasse = SHA1(MotDePasse);
-- =========================== SELLERS.TXT > UTILISATEUR ===================

LOAD DATA LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/sellers.txt'
INTO TABLE Utilisateur
FIELDS TERMINATED BY ', '
LINES TERMINATED BY '\n' 
(UserID, @ignore, @ignore, Pseudo, MotDePasse, @ignore, @ignore, AdresseMail)
set MotDePasse = SHA1(MotDePasse);

-- ============================ TABLE ADMIN ============================
DROP TABLE IF EXISTS Administrateur;

CREATE TABLE Administrateur (
    
    AdminID INT UNSIGNED NOT NULL AUTO_INCREMENT, -- clé primaire et foreign key

    -- CONTRAINTES D'INTEGRITE

    PRIMARY KEY (AdminID),
    
    CONSTRAINT fk_admin_user
        FOREIGN KEY (AdminID)         
        REFERENCES Utilisateur(UserID)

);

INSERT INTO Administrateur
VALUES (1);

-- ============================ TABLE VENDEUR ============================
DROP TABLE IF EXISTS Vendeur;

CREATE TABLE Vendeur (
    
    SellerID INT UNSIGNED NOT NULL, -- clé primaire et foreign key
    Nom CHAR(30) NOT NULL,
    Prenom CHAR(30) NOT NULL,
    DateNaissance DATE NOT NULL,
    Adresse TEXT NOT NULL,

    -- CONTRAINTES D'INTEGRITE

    PRIMARY KEY (SellerID),

    CONSTRAINT fk_vendeur_user               -- On donne un nom à notre clé
        FOREIGN KEY (SellerID)             -- Colonne sur laquelle on crée la clé
        REFERENCES Utilisateur(UserID)       -- Colonne de référence

);


-- =========================== SELLERS.TXT > VENDEUR ===================

LOAD DATA LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/sellers.txt'
INTO TABLE Vendeur
FIELDS TERMINATED BY ', '
LINES TERMINATED BY '\n' 
(SellerID, Prenom, Nom, @ignore, @ignore, @date, Adresse, @ignore)
SET DateNaissance = STR_TO_DATE(@date, '%d-%m-%Y ');

-- ============================ TABLE CATEGORIE ============================
DROP TABLE IF EXISTS Categorie;

CREATE TABLE Categorie (
    Titre VARCHAR(100) NOT NULL, -- clé primaire
    Description_cat TEXT NOT NULL,
    AdminID INT UNSIGNED, -- foreign key

    -- CONTRAINTES D'INTEGRITE


    PRIMARY KEY (Titre),

    CONSTRAINT fk_admin_cat           
        FOREIGN KEY (AdminID)         
        REFERENCES Administrateur(AdminID)

);

-- Ajout de la Categorie par défaut Default
INSERT INTO Categorie
VALUES ('Default', 'Catégorie par défaut', NULL);


-- ============================ TABLE OBJET ============================
DROP TABLE IF EXISTS Objet;

CREATE TABLE Objet (
    
    ItemID INT UNSIGNED NOT NULL AUTO_INCREMENT, -- clé primaire
    Titre CHAR(255) NOT NULL,
    Description_obj TEXT,
    DateMiseEnVente DATE,
    PrixMin DECIMAL(6,2) NOT NULL,
    DateVente DATE,
    Acheteur INT UNSIGNED, -- foreign key
    SellerID INT UNSIGNED NOT NULL, -- foreign key
    Categorie VARCHAR(100) NOT NULL, -- foreign key

    -- CONTRAINTES D'INTEGRITE
    PRIMARY KEY (ItemID),

    CONSTRAINT fk_obj_acheteur
        FOREIGN KEY (Acheteur)
        REFERENCES Utilisateur(UserID),
    
    CONSTRAINT fk_obj_vendeur             -- On donne un nom à notre clé
        FOREIGN KEY (SellerID)       -- Colonne sur laquelle on crée la clé
        REFERENCES Vendeur(SellerID),        -- Colonne de référence

    CONSTRAINT fk_obj_categorie
        FOREIGN KEY (Categorie)
        REFERENCES Categorie(Titre)

);


-- =========================== ITEMS.TXT > OBJET ===================

LOAD DATA LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/items.txt'
INTO TABLE Objet
FIELDS TERMINATED BY ', '
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(ItemID, SellerID, Titre, Description_obj, Categorie, PrixMin, DateMiseEnVente);


-- ============================ TABLE EVALUATION ============================
DROP TABLE IF EXISTS Evaluation;

CREATE TABLE Evaluation (

    Numero INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, /*clé primaire*/  
    Buyer INT UNSIGNED NOT NULL, /*foreign key*/
    Seller INT UNSIGNED NOT NULL,   /*foreign key*/
    Time DATE,
    Rate SMALLINT NOT NULL, 
    Commentaire TEXT default '',

    -- CONTRAINTES D'INTEGRITE
    
    CONSTRAINT fk_buyer          
        FOREIGN KEY (Buyer)         
        REFERENCES Utilisateur(UserID),

    CONSTRAINT fk_seller          
        FOREIGN KEY (Seller)         
        REFERENCES Vendeur(SellerID)

);


-- ============================== REVIEWS.XML > EVALUATION ==================

LOAD XML LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/reviews.xml' 
INTO TABLE Evaluation
ROWS IDENTIFIED BY '<Review>';

-- ============================ TABLE PROP ACHAT ============================
DROP TABLE IF EXISTS PropositionAchat;

CREATE TABLE PropositionAchat (
    
    ItemID INT UNSIGNED NOT NULL,
    Time DATE,
    Buyer INT UNSIGNED NOT NULL, -- foreign key
    price DECIMAL(6,2) NOT NULL,
    accepted VARCHAR(10), -- True ou False

    CONSTRAINT fk_obj_prop          
        FOREIGN KEY (ItemID)         
        REFERENCES Objet(ItemID),

    
    CONSTRAINT fk_user_prop          
        FOREIGN KEY (Buyer)         
        REFERENCES Utilisateur(UserID)
    

);


-- ================ PROPOSITION.XML > PROPOSITIONACHAT=====================

LOAD XML LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/propositions.xml' 
INTO TABLE PropositionAchat
ROWS IDENTIFIED BY '<Proposition>';


UPDATE Objet inner join PropositionAchat 
on Objet.ItemID = PropositionAchat.ItemID
SET Objet.Acheteur = PropositionAchat.Buyer
where PropositionAchat.accepted = "True";
    
);

