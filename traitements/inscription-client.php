<?php

/* Recherche du client */

error_reporting(0);

require_once 'db.inc.php';
require_once 'misc.inc.php';
require_once 'verifier-client.php';



// **************
// * Constantes *
// **************

// Expressions régulières (email et mot de passe déjà définies dans connexion client)

const REGEX_NOM = "/^[a-zA-Z\s\-àáâäæèéêëìíîïòóôöøœùúûüÀÁÂÄÆÈÉÊËÌÍÎÏÒÓÔÖØŒÙÚÛÜ]{2,30}$/";


// Requêtes à préparer

const EMAIL_EXISTE = '
SELECT count(*) AS occurrence
FROM CLIENTS
WHERE Email = ?;
';

const INSCRIRE_CLIENT = '
INSERT INTO CLIENTS (`Nom`, `Prenom`, `Email`, `MotDePasse`)
VALUES (?, ?, ?, ?);
';



// *************
// * Fonctions *
// *************

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
