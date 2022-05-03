<?php

/* Recherche des commandes du client */

require_once 'db.inc.php';
require_once 'misc.inc.php';


// Requêtes à préparer

// FIXME : Cette requête ne produit pas le résultat attendu.
const RECHERCHE_COMMANDES = '
SELECT comm.IdCommande, comm.DateRetrait, comm.DateValidation, d.Nom AS NomDepot, SUM(a.Prix*cont.Quantite) AS PrixCommande
FROM COMMANDES AS comm
INNER JOIN CONTENIR AS cont USING (IdCommande)
INNER JOIN ARTICLES AS a USING (IdArticle)
INNER JOIN DEPOTS  AS d USING (IdDepot)
WHERE comm.IdClient = ?
GROUP BY comm.IdCommande
ORDER BY comm.DateValidation DESC
LIMIT ?;
';



/*************
 * Fonctions *
 *************/

/**
 * Rechercher des commandes du client.
 * @param $IdClient : identifiant du client
 * @param $nbComm : nombre maximal de commande effectuée du client voulue
 */
function rechercheCommandes ($IdClient, $nbComm): bool|array {
  // Connexion à la base de données
  $link = dbConnect();

  $result = NULL;

  // Préparation de la requête

  $stmt = $link->prepare(RECHERCHE_COMMANDES);
  checkError($stmt, $link);

  $status = $stmt->bind_param('ii', $IdClient, $nbComm);

  // Exécution de la requête de recherche de l'email client
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

  if (count($resultArray) > 0) {
    return $resultArray;
  }
  else {
    return false;
  }
}
