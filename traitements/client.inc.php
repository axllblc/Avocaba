<?php

/* Traitements liés aux clients */

error_reporting(0);

require_once 'db.inc.php';
require_once 'misc.inc.php';



// **************
// * Constantes *
// **************

// Expressions régulières

const REGEX_NOM = "/^[a-zA-Z\s\-àáâäæèéêëìíîïòóôöøœùúûüÀÁÂÄÆÈÉÊËÌÍÎÏÒÓÔÖØŒÙÚÛÜ]{2,30}$/";
const REGEX_MOTDEPASSE = '/^([0-9a-zA-Z._#-]){8,16}$/';


// Requêtes à préparer

  // Vérifications et recherche

const RECHERCHE_CLIENT = '
SELECT c.IdClient, c.`Nom`, c.`Prenom`, c.`Email`, c.`IdDepot` AS DernierDepot, c.`MotDePasse`
FROM CLIENTS AS c
WHERE c.`Email` = ?
LIMIT 1;
';

const RECUPERE_CLIENT = '
SELECT c.IdClient, c.`Nom`, c.`Prenom`, c.`Email`, c.`IdDepot` AS DernierDepot
FROM CLIENTS AS c
WHERE c.`Email` = ?
LIMIT 1;
';

  // Inscription

const EMAIL_EXISTE = '
SELECT count(*) AS occurrence
FROM CLIENTS
WHERE Email = ?;
';

const INSCRIRE_CLIENT = '
INSERT INTO CLIENTS (`Nom`, `Prenom`, `Email`, `MotDePasse`)
VALUES (?, ?, ?, ?);
';

  // Modification

const MODIFIER_CLIENT = '
UPDATE CLIENTS
SET Nom = ?,
Prenom = ?,
Email = ?,
MotDePasse = ?
WHERE IdClient = ?;
';



// *************
// * Fonctions *
// *************


// --------------------------
// Vérifications et recherche
// --------------------------

/**
 * Rechercher un client.
 * Si l'identifiant et le mot de passe sont corrects, la fonction retourne les infos du client, sinon elle retourne false.
 * @param $email : Adresse e-mail du client
 * @param $motdepasse : Mot de passe du client
 * @return array|false
 */
function verifierClient ($email, $motdepasse): array|false {
  // Connexion à la base de données
  $link = dbConnect();

  $resultRecherche = NULL;
  $resultArrayInfos = NULL;

  // Préparation de la requête
  if ( filter_var($email, FILTER_VALIDATE_EMAIL) and preg_match(REGEX_MOTDEPASSE, $motdepasse) ) {
    // Recherche si l'adresse e-mail est connue

    $stmtRecherche = $link->prepare(RECHERCHE_CLIENT);
    checkError($stmtRecherche, $link);

    $statusRecherche = $stmtRecherche->bind_param('s', $email);

    // Exécution de la requête de recherche de l'email client
    $statusRecherche = $stmtRecherche->execute();
    checkError($statusRecherche, $link);

    // Récupération du résultat
    $resultRecherche = $stmtRecherche->get_result();
    checkError($resultRecherche, $link);

    $resultArrayRecherche = $resultRecherche->fetch_all(MYSQLI_ASSOC);

    if( count($resultArrayRecherche) == 1 ){
      // Si l'on a trouvé l'adresse mail dans la base de données
      if( password_verify($motdepasse, $resultArrayRecherche[0]['MotDePasse']) ){
        // Si le mot de passe correspond avec celui associé à l'adresse e-mail renseignée,

        // Fermeture de la connexion à la base de données
        $link->close();

        return $resultArrayRecherche[0]; // On renvoie les infos client
      }
    }
  }
  // Fermeture de la connexion à la base de données
  $link->close();

  return false;
}



// -----------
// Inscription
// -----------

/**
 * Inscrire un client.
 * @param $nom
 * @param $prenom
 * @param $email
 * @param $motDePasse
 * @return bool true si le client a été inscrit, false en cas d'échec
 */
function inscrireClient ($nom, $prenom, $email, $motDePasse): bool {
  $link = dbConnect();

  $result = NULL;

  // Préparation de la requête
  if ( filter_var($email, FILTER_VALIDATE_EMAIL) and preg_match(REGEX_MOTDEPASSE, $motDePasse)
    and preg_match(REGEX_NOM, $nom) and preg_match(REGEX_NOM, $prenom) and emailAbsente($email)) {

    $stmt = $link->prepare(INSCRIRE_CLIENT);
    checkError($stmt, $link);

    //On chiffre le mot de passe
    $motDePasseChiffre =  password_hash($motDePasse, PASSWORD_DEFAULT);

    $status = $stmt->bind_param('ssss', $nom, $prenom, $email, $motDePasseChiffre);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    //On renvoie le booléen selon la réussite de l'inscription
    if (verifierClient($email, $motDePasse)) {
      // Fermeture de la connexion à la base de données
      $link->close();

      return true;
    }
  }

  // Fermeture de la connexion à la base de données
  $link->close();

  return false;
}


/**
 * Vérifier la présence ou non d'une adresse e-mail dans la base de données.
 * @param string $email Email à vérifier
 * @return bool true si l'email est absent de la base de donnée, false sinon
 */
function emailAbsente (string $email): bool {
  $link = dbConnect();

  $result = NULL;

  // Préparation de la requête
  if ( filter_var($email, FILTER_VALIDATE_EMAIL) ) {

    $stmt = $link->prepare(EMAIL_EXISTE);
    checkError($stmt, $link);

    $status = $stmt->bind_param('s',$email);
    checkError($status, $link);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    $result = $stmt->get_result();
    $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    // Fermeture de la connexion à la base de données
    $link->close();

    // On renvoie le booléen selon la présence ou nom de l'email dans la base de données
    if ($resultArray[0]['occurrence'] == 0) {
      return true;
    }
    else{
      return false;
    }
  }

  $link->close();

  return false; // Cas où une erreur est lancée
}



// ------------
// Modification
// ------------

/**
 * Modifier les informations d'un client.
 * Retourne un booléen selon la réussite de la modification.
 * @param $idClient : identifiant du client sur la base de donnée
 * @param $emailActuel : Email du client avant modification
 * @param $motDePasseActuel : Mot de passe du client avant modification
 * @param $nom : Nouveau nom du client
 * @param $prenom : Nouveau prénom du client
 * @param $email : Nouvel email du client
 * @param $motDePasse1 : Nouveau mot de passe du client
 * @param $motDePasse2 : Nouveau mot de passe du client (vérification)
 * @return bool Un booléen valant true si la modification a été effectuée, false en cas d'erreur
 */
function modifierClient ($idClient, $emailActuel, $motDePasseActuel, $nom, $prenom, $email, $motDePasse1, $motDePasse2): bool {
  // Connexion à la base de données
  $link = dbConnect();

  $result = NULL;

  // Préparation de la requête :
  if ( filter_var_array($emailActuel, FILTER_VALIDATE_EMAIL) and preg_match(REGEX_MOTDEPASSE, $motDePasseActuel)
    and preg_match(REGEX_NOM, $nom) and preg_match(REGEX_NOM, $prenom) and filter_var($email, FILTER_VALIDATE_EMAIL)
    and $motDePasse1 == $motDePasse2 and (preg_match(REGEX_MOTDEPASSE, $motDePasse2) or $motDePasse1 == "")
  ) {

    if (verifierClient($emailActuel, $motDePasseActuel)){

      $stmt = $link->prepare(MODIFIER_CLIENT);
      checkError($stmt, $link);

      // Le mot de passe est chiffré s'il est modifié
      if ($motDePasse1 != '') {
        $motDePasse =  password_hash($motDePasse1, PASSWORD_DEFAULT);
      }
      else{
        // On associe l'ancien mot de passe à la variable du nouveau, si l'utilisateur n'a pas saisie de nouveau mot de passe (on change juste la clé de chiffrement, mais le mot de passe pour se connecter reste le même)
        $motDePasse = password_hash($motDePasseActuel, PASSWORD_DEFAULT);
      }
      $status = $stmt->bind_param('ssssi', $nom, $prenom, $email, $motDePasse, $idClient);

      // Exécution de la requête
      $status = $stmt->execute();
      checkError($status, $link);

      $link->close();
      return $status; // Booléen selon le succès de la requête

    }
  }
  $link->close();
  return false; // Si les informations renseignées sont incorrectes
}

