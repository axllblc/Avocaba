<?php

/* 🧑‍🌾 Traitements liés aux fournisseurs */

error_reporting(0);

require_once 'db.inc.php';



// **************
// * Constantes *
// **************

// Requêtes à préparer

const RECHERCHE_FOURNISSEUR = '
  SELECT * 
  FROM `fournisseurs` 
  WHERE `Siret` = ?;
';

const NOMBRE_FOURNISSEUR = '
  SELECT COUNT(*)
  FROM `fournisseurs`;
';

const PRODUITS_PHARES = '
  SELECT `IdArticle`
  FROM `articles`
  WHERE `SiretProducteur` = ?
  AND `ProduitPhare` = 1;
';

const PHOTOS = '
  SELECT IdPhotoFournisseur
  FROM `photos_fournisseurs` 
  WHERE Siret = ?;
';

const PRODUCTEURS_PROCHES_VILLE = '
  SELECT `Siret`
  FROM fournisseurs
  WHERE `IdVille` = ?
  AND `Siret` != ?;
';

const VILLE = '
  SELECT `Nom`
  FROM `villes`
  WHERE `IdVille` = ?;
';

const DEPOT = '
  SELECT DISTINCT IdDepot
  FROM articles as a INNER JOIN stocker as s
  WHERE SiretProducteur = ?;
';

const FOURNISSEUR_SUR_DEPOT = '
  SELECT DISTINCT SiretProducteur
  FROM articles INNER JOIN stocker
  WHERE IdDepot = ?;
';

const DOMAINE_FOURNISSEUR = '
  SELECT DISTINCT r.Nom as "Nom"
  FROM rayons as r INNER JOIN articles as a
  WHERE a.SiretProducteur = ?;
';



// **********
// * Classe *
// **********

class Fournisseur {
  private $_siret;
  private $_nom;
  private $_description;
  private $_adresse;
  private $_email;
  private $_site;
  private $_facebook;
  private $_twitter;
  private $_instagram;
  private $_motDePasse;
  private $_photoProfil;
  private $_photoBanniere;
  private $_idVille;

  /**
   * Constructeur du Fournisseur
   * @param int|string $siret
   * @throws Exception
   */
  function __construct(int|string $siret) {
    // connexion à la base de données
    $link = dbConnect();

    // Préparation de la requête
    $stmt = $link->prepare(RECHERCHE_FOURNISSEUR);
    checkError($stmt, $link);
    $status = $stmt->bind_param('i', $siret);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    // Récupération du résultat
    $result = $stmt->get_result();
    checkError($result, $link);
    $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    // Fermeture de la connexion à la base de données et libération de la mémoire associée
    $result->close();
    $stmt->close();
    $link->close();

    // Vérification des résultats
    if (count($resultArray) == 1) {
      // Affectation
      $this->_siret = $resultArray[0]['Siret'];
      $this->_nom = $resultArray[0]['Nom'];
      $this->_description = $resultArray[0]['Description'];
      $this->_adresse = $resultArray[0]['Adresse'];
      $this->_email = $resultArray[0]['Email'];
      $this->_site = $resultArray[0]['Site'];
      $this->_facebook = $resultArray[0]['Facebook'];
      $this->_twitter = $resultArray[0]['Twitter'];
      $this->_instagram = $resultArray[0]['Instagram'];
      $this->_motDePasse = $resultArray[0]['MotDePasse'];
      $this->_photoProfil = $resultArray[0]['PhotoProfil'];
      $this->_photoBanniere = $resultArray[0]['PhotoBanniere'];
      $this->_idVille = $resultArray[0]['IdVille'];
    } else {
      throw new Exception("Le fournisseur de siret $siret n'existe pas.");
    }
  }

  // GETTERS
  function getSiret() {return $this->_siret;}
  function getNom() {return $this->_nom;}
  function getDescription() {return $this->_description;}
  function getAdresse() {return $this->_adresse;}
  function getEmail() {return $this->_email;}
  function getSite() {return $this->_site;}
  function getFacebook() {return $this->_facebook;}
  function getTwitter() {return $this->_twitter;}
  function getInstagram() {return $this->_instagram;}
  function getMotDePasse() {return $this->_motDePasse;}
  function getPhotoProfil() {return $this->_photoProfil;}
  function getPhotoBanniere() {return $this->_photoBanniere;}
  function getIdVille() {return $this->_idVille;}

  function getVille() {
    // Connexion à la base de données
    $link = dbConnect();

    // Préparation de la requête
    $stmt = $link->prepare(VILLE);
    checkError($stmt, $link);
    $status = $stmt->bind_param('i', $this->_idVille);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    // Récupération du résultat
    $result = $stmt->get_result();
    checkError($result, $link);
    $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    // Fermeture de la connexion à la base de données et libération de la mémoire associée
    $result->close();
    $stmt->close();
    $link->close();

    return $resultArray[0]['Nom'];
  }

  /**
   * Obtenir la liste des produits phares du fournisseur
   * @return array liste des identifiants des produits phares
   */
  function produitsPhares() : array {
    // Connexion à la base de données
    $link = dbConnect();

    // Préparation de la requête
    $stmt = $link->prepare(PRODUITS_PHARES);
    checkError($stmt, $link);
    $status = $stmt->bind_param('i', $this->_siret);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    // Récupération du résultat
    $result = $stmt->get_result();
    checkError($result, $link);
    $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    // Fermeture de la connexion à la base de données et libération de la mémoire associée
    $result->close();
    $stmt->close();
    $link->close();
    
    foreach ($resultArray as $key => $value)
      $resultArray[$key] = $value['IdArticle'];

    return $resultArray;
  }

  /**
   * Obtenir la liste des photos du fournisseur
   * @return array liste des photos du fournisseur
   */
  function photoFournisseur() : array {
    // Connexion à la base de données
    $link = dbConnect();

    // Préparation de la requête
    $stmt = $link->prepare(PHOTOS);
    checkError($stmt, $link);
    $status = $stmt->bind_param('i', $this->_siret);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    // Récupération du résultat
    $result = $stmt->get_result();
    checkError($result, $link);
    $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    // Fermeture de la connexion à la base de données et libération de la mémoire associée
    $result->close();
    $stmt->close();
    $link->close();
    
    foreach ($resultArray as $key => $value)
      $resultArray[$key] = $value['IdPhotoFournisseur'];

    return $resultArray;
  }

  /**
   * Obtenir le nombre de fournisseurs dans la base de données.
   * @return int Nombre de fournisseurs.
   */
  static function nombreFournisseur() : int {
    $nombre = 0;

    // Connexion à la base de données
    $link = dbConnect();

    // Exécution de la requête
    if ($result = $link->query(NOMBRE_FOURNISSEUR)) {
      // Il n'y a forcément qu'une ligne obtenue
      $nombre = $result->fetch_array()[0];
      $result->free_result();
    }
    $link->close();

    return $nombre;
  }

  /**
   * Obtenir la liste des autres producteurs dans la même ville.
   * @return array Liste des SIRET des autres fournisseurs.
   */
  function producteursProches() : array {
    // Connexion à la base de données
    $link = dbConnect();

    // Préparation de la requête
    $stmt = $link->prepare(PRODUCTEURS_PROCHES_VILLE);
    checkError($stmt, $link);
    $status = $stmt->bind_param('ii', $this->_idVille, $this->_siret);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    // Récupération du résultat
    $result = $stmt->get_result();
    checkError($result, $link);
    $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    // Fermeture de la connexion à la base de données et libération de la mémoire associée
    $result->close();
    $stmt->close();
    $link->close();

    foreach ($resultArray as $key => $value)
      $resultArray[$key] = $value['Siret'];

    return $resultArray;
  }

  /**
   * Connaître tous les dépôts où le producteur se trouve.
   */
  function getDepot() : Array {
    // Connexion à la base de données
    $link = dbConnect();

    // Préparation de la requête
    $stmt = $link->prepare(DEPOT);
    checkError($stmt, $link);
    $status = $stmt->bind_param('i', $this->_siret);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    // Récupération du résultat
    $result = $stmt->get_result();
    checkError($result, $link);
    $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    // Fermeture de la connexion à la base de données et libération de la mémoire associée
    $result->close();
    $stmt->close();
    $link->close();

    return $resultArray[0]['IdDepot'];
  }

  /**
   * Obtenir la liste des fournisseurs présents sur le dépôt.
   * @param int|string $idDepot Identifiant du dépôt.
   * @return array Liste des fournisseurs trouvés.
   */
  static function fournisseurSurDepot(int|String $idDepot) : Array {
    // Connexion à la base de données
    $link = dbConnect();

    // Préparation de la requête
    $stmt = $link->prepare(FOURNISSEUR_SUR_DEPOT);
    checkError($stmt, $link);
    $status = $stmt->bind_param('i', $idDepot);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    // Récupération du résultat
    $result = $stmt->get_result();
    checkError($result, $link);
    $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    // Fermeture de la connexion à la base de données et libération de la mémoire associée
    $result->close();
    $stmt->close();
    $link->close();

    foreach ($resultArray as $key => $value)
      $resultArray[$key] = $value['SiretProducteur'];

    return $resultArray;
  }

  /**
   * Obtenir la liste des noms des domaines du fournisseur.
   * @return array Liste des domaines.
   */
  function getDomaines() : Array {
    // Connexion à la base de données
    $link = dbConnect();

    // Préparation de la requête
    $stmt = $link->prepare(DOMAINE_FOURNISSEUR);
    checkError($stmt, $link);
    $status = $stmt->bind_param('i', $this->_siret);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    // Récupération du résultat
    $result = $stmt->get_result();
    checkError($result, $link);
    $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    // Fermeture de la connexion à la base de données et libération de la mémoire associée
    $result->close();
    $stmt->close();
    $link->close();

    foreach ($resultArray as $key => $value)
      $resultArray[$key] = $value['Nom'];

    return $resultArray;
  }
}
