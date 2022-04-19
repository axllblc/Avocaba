<?php

/* Recherche du client */

require_once 'db.inc.php';
require_once 'misc.inc.php';

/**************
 * Constantes *
 **************/

// Expressions régulières

const REGEX_EMAIL = '/^[a-zA-Z1-9-\.]+@[a-zA-Z1-9-]+\.[a-zA-Z]{2,6}$/';
const REGEX_MOTDEPASSE = '/([0-9a-zA-Z._#-]){8,16}$/';


// Requêtes à préparer

const RECHERCHE_CLIENT = '
SELECT c.`Email`, c.`MotDePasse`
FROM CLIENTS AS c
WHERE c.`Email` = ?
LIMIT 1;
';

const RECUPERE_CLIENT = '
SELECT c.IdClient, c.`Nom`, c.`Prenom`, c.`Email`, c.`IdDepot`
FROM CLIENTS AS c
WHERE c.`Email` = ?
LIMIT 1;
';



/*************
 * Fonctions *
 *************/

/**
 * Rechercher du client.
 * @param $str : critère de recherche : Email et MotDePasse
 */
function verifierClient ($email, $motdepasse) {
  // Sert à rechecher un client dans la base de donnée
  // Si identifiant et mot de passe correct, on renvoie les infos du clients, sinon on renvoie false

  $link = dbConnect();

  $resultRecherche = NULL;
  $resultArrayInfos = NULL;

  // Préparation de la requête
  if ( preg_match(REGEX_EMAIL, $email) and preg_match(REGEX_MOTDEPASSE, $motdepasse)) {
    // Recherche si l'adresse mail est connue

    $stmtRecherche = $link->prepare(RECHERCHE_CLIENT);
    //checkError($stmtRecherche, $link);

    $statusRecherche = $stmtRecherche->bind_param('s', $email);

    // Exécution de la requête de recherche de l'email client
    $statusRecherche = $stmtRecherche->execute();
    //checkError($statusRecherche, $link);

    // Récupération du résultat
    $resultRecherche = $stmtRecherche->get_result();
    //checkError($resultRecherche, $link);

    $resultArrayRecherche = $resultRecherche->fetch_all(MYSQLI_ASSOC);

    if(count($resultArrayRecherche) == 1){
      // Si l'on a trouvé l'adresse mail dans la base de donnée
      if(password_verify($motdepasse, $resultArrayRecherche[0]["MotDePasse"])){
        // Si le mot de passe correspond avec celui associé à l'adresse mail renseigné,
        // on récupère toutes les données d'information-client (email, id du dépot favori, nom et prénom)

        $stmtInfos = $link->prepare(RECUPERE_CLIENT);
        //checkError($stmtInfos, $link);

        $statusInfos = $stmtInfos ->bind_param('s', $email);

        // Exécution de la requête pour les infos clients
        $statusInfos = $stmtInfos->execute();
        //checkError($statusInfos, $link);

        // Récupération du résultat
        $resultInfos = $stmtInfos->get_result();
        //checkError($resultRecherche, $link);

        $resultArrayInfos = $resultInfos->fetch_all(MYSQLI_ASSOC);
        $resultArrayInfos = $resultArrayInfos[0];

        // Fermeture de la connexion à la base de données
        $link->close();

        return $resultArrayInfos; // On renvoie les infos client
      }
      else{
        return "wrong password";
      }
    }
  }
  // Fermeture de la connexion à la base de données
  $link->close();

  return false;
}
