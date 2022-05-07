<?php
/* Fonctions en rapport avec les favoris (fournisseur et article) */

require_once 'db.inc.php';

const ARTICLES_FAVORIS = '
  SELECT DISTINCT a.IdArticle, a.Nom AS "NomArticle", Prix, PhotoVignette, SiretProducteur, f.nom AS "NomProducteur"
  FROM articles_favoris 
  INNER JOIN articles AS a
  INNER JOIN fournisseurs AS f ON (SiretProducteur = Siret)
  WHERE IdClient = ?;
';

const PRODUCTEURS_FAVORIS = '
  SELECT Siret
  FROM fournisseurs_favoris
  WHERE IdClient = ?;
';

/**
 * Obtenir la liste des producteurs favoris du client
 * @param int|string @idClient identifiant du client
 * @return array liste des sirets des producteurs favoris
 */
function getProducteursFavoris(int|String $idClient) : Array {
  // connexion à la base de données
  $link = dbConnect();

  // Préparation de la requête
  $stmt = $link->prepare(PRODUCTEURS_FAVORIS);
  checkError($stmt, $link);
  $status = $stmt->bind_param('i', $idClient);

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
 * Obtenir la listes des articles favoris du client
 * @param int|string indentifiant du client
 * @return array liste des articles ('IdArticle', 'NomArticle', 'Prix', 'PhotoVignette', 'SiretProducteur', 'NomProducteur')
 */
function getArticlesFavoris(int|String $idClient) : Array{
  // connexion à la base de données
  $link = dbConnect();

  // Préparation de la requête
  $stmt = $link->prepare(ARTICLES_FAVORIS);
  checkError($stmt, $link);
  $status = $stmt->bind_param('i', $idClient);

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

  return $resultArray;
}
?>