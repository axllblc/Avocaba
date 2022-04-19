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
const INSCRIRE_CLIENT = '
INSERT INTO CLIENTS (`Nom`, `Prenom`, `Email`, `MotDePasse`)
VALUES (?, ?, ?, ?);
';

/*************
 * Fonctions *
 *************/

/**
 * Rechercher du client.
 * @param $str : critère de recherche : Email et MotDePasse
 */
function inscrireClient ($nom, $prenom, $email, $motdepasse) {
  // Sert à inscrire un client dans la base de donnée, renvoie un booléen selon le succès de la requete

  $link = dbConnect();

  $result = NULL;

  // Préparation de la requête
  if ( preg_match(REGEX_EMAIL, $email) and preg_match(REGEX_MOTDEPASSE, $motdepasse) and preg_match(REGEX_NOM, $nom) and preg_match(REGEX_NOM, $prenom)) {

    $stmt = $link->prepare(INSCRIRE_CLIENT);
    //checkError($stmt, $link);

    //On chiffre le mot de passe
    $motdepasseChiffre =  password_hash($motdepasse, PASSWORD_DEFAULT);

    $status = $stmt->bind_param('ssss', $nom, $prenom, $email, $motdepasseChiffre);

    // Exécution de la requête
    $status = $stmt->execute();
    //checkError($status, $link);

    // Récupération du résultat
    $result = $stmt->get_result();
    //checkError($result, $link);

    //On renvoie le booléen selon la réussite de l'inscription
    if(verifierClient($email, $motdepasse)){
      $link->close();
      return true;
    }
  }
  $link->close();
  return false;
}
