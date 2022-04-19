<?php

/* Recherche du client */

require_once 'db.inc.php';
require_once 'misc.inc.php';

/**************
 * Constantes *
 **************/

// Expressions régulières

const REGEX_EMAIL = '/^[a-zA-Z1-9-\.]+@[a-zA-Z1-9-]+\.[a-zA-Z]{2,6}$/';
const REGEX_MOTDEPASSE = '/^([0-9a-zA-Z._#-]){8,16}$/';


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
 * Rechercher un client.
 * Si l'identifiant et le mot de passe sont corrects, la fonction retourne les infos du client, sinon elle retourne false.
 * @param $email : Adresse e-mail du client
 * @param $motdepasse : Mot de passe du client
 */
function verifierClient ($email, $motdepasse) {
  // Connexion à la base de données
  $link = dbConnect();

  $resultRecherche = NULL;
  $resultArrayInfos = NULL;

  // Préparation de la requête
  if ( preg_match(REGEX_EMAIL, $email) and preg_match(REGEX_MOTDEPASSE, $motdepasse) ) {
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
        // on récupère toutes les données d'information-client (e-mail, id du dernier dépôt fréquenté, nom et prénom)

        $stmtInfos = $link->prepare(RECUPERE_CLIENT);
        checkError($stmtInfos, $link);

        $statusInfos = $stmtInfos ->bind_param('s', $email);

        // Exécution de la requête pour les infos clients
        $statusInfos = $stmtInfos->execute();
        checkError($statusInfos, $link);

        // Récupération du résultat
        $resultInfos = $stmtInfos->get_result();
        checkError($resultRecherche, $link);

        $resultArrayInfos = $resultInfos->fetch_all(MYSQLI_ASSOC);
        $resultArrayInfos = $resultArrayInfos[0];

        // Fermeture de la connexion à la base de données
        $link->close();

        return $resultArrayInfos; // On renvoie les infos client
      }
      else{
        $link->close();

        // TODO remplacer la valeur de retour par false ?
        return "wrong password";
      }
    }
  }
  // Fermeture de la connexion à la base de données
  $link->close();

  return false;
}
