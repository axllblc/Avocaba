<?php

/* Recherche des commandes du client */

require_once 'db.inc.php';
require_once 'misc.inc.php';


// **************
// * Constantes *
// **************

// Requêtes à préparer

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

const AJOUTER_COMMANDE = '
INSERT INTO COMMANDES (IdClient, IdDepot, DateRetrait, DateValidation)
VALUES (?, ?, ?, NOW());
';

const RECUPERER_DERNIER_ID = '
SELECT LAST_INSERT_ID() AS IdCommande;
';

const AJOUTER_ARTICLE_COMMANDE = '
INSERT INTO CONTENIR (IdArticle, IdCommande, Quantite)
VALUES (?, ?, ?);
';



// *************
// * Fonctions *
// *************

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

/**
 * Ajouter une commande à la base de donnée
 * @param int : $IdClient : identifiant du client
 * @param int $IdDepot : identifiant du dépôt
 * @param string $DateRetrait : date et heure de retrait de la commande de type DATETIME dans une chaine de caractère
 * @return int|bool l'indentifiant associé à la commande si succès de la requête, false sinon
 */
 function ajouterCommande (int $IdClient, int $IdDepot, string $DateRetrait): int|bool {
   // Connexion à la base de données
   $link = dbConnect();

   $result = NULL;

   // Préparation de la requête

   $stmtAjout = $link->prepare(AJOUTER_COMMANDE);
   $stmtId = $link->prepare(RECUPERER_DERNIER_ID);
   checkError($stmtAjout, $link);
   checkError($stmtId, $link);

   $statusAjout = $stmtAjout->bind_param('iis', $IdClient, $IdDepot, $DateRetrait);
   checkError($statusAjout, $link);

   // Exécution de la requête d'ajout de la commande client
   $statusAjout = $stmtAjout->execute();
   checkError($statusAjout, $link);

   // Récupération de l'identifiant de commande
   $statusId = $stmtId->execute();
   checkError($statusId, $link);

   $result = $stmtId->get_result();
   checkError($result, $link);

   $resultArray = $result->fetch_all(MYSQLI_ASSOC);

   // Fermeture de la connexion à la base de données et libération de la mémoire associée
   $result->close();
   $stmtAjout->close();
   $stmtId->close();
   $link->close();

   return $resultArray[0]['IdCommande'];
 }

 /**
   * @param int : $IdArticle : identifiant de l'article
   * @param int $IdCommande : identifiant de la commande
   * @param int $Quantite : quantite de l'article dans la commande
   */
 function ajouterArticleACommande ($IdArticle, $IdCommande, $Quantite): void {
   // Connexion à la base de données
   $link = dbConnect();

   // Préparation de la requête

   $stmt = $link->prepare(AJOUTER_ARTICLE_COMMANDE);
   checkError($stmt, $link);

   $status = $stmt->bind_param('iii', $IdArticle, $IdCommande, $Quantite);

   // Exécution de la requête de recherche de l'email client
   $status = $stmt->execute();
   checkError($status, $link);

   // Fermeture de la connexion à la base de données et libération de la mémoire associée
   $stmt->close();
   $link->close();
 }

 //exemple :
 //var_dump(ajouterCommande(1, 1, '2022-2-2 12:13'));
