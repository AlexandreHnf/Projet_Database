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
set MotDePasse = MotDePasse;

-- =========================== SELLERS.TXT > VENDEUR ===================

LOAD DATA LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/sellers.txt'
INTO TABLE Vendeur
FIELDS TERMINATED BY ', '
LINES TERMINATED BY '\n' 
(SellerID, Prenom, Nom, @ignore, @ignore, @date, Adresse, @ignore)
SET DateNaissance = STR_TO_DATE(@date, '%d-%m-%Y ');

-- =========================== ITEMS.TXT > OBJET ===================

LOAD DATA LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/items.txt'
INTO TABLE Objet
FIELDS TERMINATED BY ', '
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(ItemID, SellerID, Titre, Description_obj, Categorie, PrixMin, DateMiseEnVente);

-- ============================== REVIEWS.XML > EVALUATION ==================

LOAD XML LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/reviews.xml' 
INTO TABLE Evaluation
ROWS IDENTIFIED BY '<Review>';

-- ================ PROPOSITION.XML > PROPOSITIONACHAT=====================

LOAD XML LOCAL INFILE '/opt/lampp/phpmyadmin/data/dataset_ebay_v2/propositions.xml' 
INTO TABLE PropositionAchat
ROWS IDENTIFIED BY '<Proposition>';


