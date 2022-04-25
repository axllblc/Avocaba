<?php

/* Recherche du client */

require_once 'db.inc.php';
require_once 'misc.inc.php';

/**************
 * Constantes *
 **************/

// Expressions régulières (email et mot de passe déjà définie dans connexion client)

const REGEX_NOM= "/[a-zA-Z -]{2,30}$/";


// Requêtes à préparer

//IdClient est en Auto-increment, donc on ne le renseigne pas

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
 * Rechercher un client.
 * @param $nom
 * @param $prenom
 * @param $email
 * @param $motdepasse
 * @return bool
 */

function inscrireClient ($nom, $prenom, $email, $motdepasse) {
  // Sert à inscrire un client dans la base de donnée, renvoie un booléen selon le succès de la requete

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
 * Rechercher un email.
 * @param $email
 * @return bool
 */

function emailAbsente($email){
  // Sert à vérifier la présence ou non d'une adresse mail dans la base de donnée, renvoie un booléen

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

    //On renvoie le booléen selon la présence ou nom de l'email dans la base de donnée
    if($resultArray[0]['occurence'] == 0){
      return true;
    }
    else{
      return false;
    }
  }
  $link->close();
  return false; //cas où une erreur est lancée, on ne veut
}
