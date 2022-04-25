<?php

/* Recherche du client */

require_once 'db.inc.php';
require_once 'misc.inc.php';
require_once 'verifier-client.php';

/**************
 * Constantes *
 **************/

// Expressions régulières (email et mot de passe déjà définie dans connexion client)

const REGEX_NOM = "/^[a-zA-Z\s-]{2,30}$/";


// Requêtes à préparer

// IdClient est en Auto-increment, donc on ne le renseigne pas

const EMAIL_EXISTE = '
SELECT count(*) AS occurence
FROM CLIENTS
WHERE Email = ?;
';

const INSCRIRE_CLIENT = '
INSERT INTO CLIENTS (`Nom`, `Prenom`, `Email`, `MotDePasse`)
VALUES (?, ?, ?, ?);
';

/*************
 * Fonctions *
 *************/

/**
 * Inscrire un client.
 * @param $nom
 * @param $prenom
 * @param $email
 * @param $motdepasse
 * @return bool
 */
function inscrireClient ($nom, $prenom, $email, $motdepasse) {
  $link = dbConnect();

  $result = NULL;

  // Préparation de la requête
  if ( preg_match(REGEX_EMAIL, $email) and preg_match(REGEX_MOTDEPASSE, $motdepasse)
  and preg_match(REGEX_NOM, $nom) and preg_match(REGEX_NOM, $prenom) and emailAbsente($email)) {

    $stmt = $link->prepare(INSCRIRE_CLIENT);
    checkError($stmt, $link);

    //On chiffre le mot de passe
    $motdepasseChiffre =  password_hash($motdepasse, PASSWORD_DEFAULT);

    $status = $stmt->bind_param('ssss', $nom, $prenom, $email, $motdepasseChiffre);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    //On renvoie le booléen selon la réussite de l'inscription
    if(verifierClient($email, $motdepasse)){
      $link->close();
      return true;
    }
  }
  $link->close();
  return false;
}

/**
 * Vérifier la présence ou non d'une adresse e-mail dans la base de données.
 * @param $email
 * @return bool
 */
function emailAbsente ($email) {
  $link = dbConnect();

  $result = NULL;

  // Préparation de la requête
  if ( preg_match(REGEX_EMAIL, $email) ) {

    $stmt = $link->prepare(EMAIL_EXISTE);
    checkError($stmt, $link);

    $status = $stmt->bind_param('s',$email);

    // Exécution de la requête
    $status = $stmt->execute();
    checkError($status, $link);

    $result = $stmt->get_result();
    $resultArray = $result->fetch_all(MYSQLI_ASSOC);

    $link->close();

    // On renvoie le booléen selon la présence ou nom de l'email dans la base de données
    if($resultArray[0]['occurence'] == 0){
      return true;
    }
    else{
      return false;
    }
  }
  $link->close();
  return false; // cas où une erreur est lancée, on ne veut
}
