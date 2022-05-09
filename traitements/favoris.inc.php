<?php

/* ⭐ Gestion des favoris */

error_reporting(0);

require_once 'db.inc.php';

// lien entre la table et sa clé primaire
const TABLE_ID = Array('fournisseurs' => 'Siret', 'articles' => 'IdArticle');

const ARTICLES_FAVORIS = '
  SELECT DISTINCT a.IdArticle, a.Nom AS "NomArticle", Prix, PhotoVignette, SiretProducteur, f.nom AS "NomProducteur"
  FROM articles_favoris AS af
  INNER JOIN articles AS a ON af.IdArticle = a.IdArticle
  INNER JOIN fournisseurs AS f ON (SiretProducteur = Siret)
  WHERE IdClient = ?;
';

const PRODUCTEURS_FAVORIS = '
  SELECT Siret
  FROM fournisseurs_favoris
  WHERE IdClient = ?;
';


const ARTICLE_EST_FAVORIS = '
  SELECT *
  FROM articles_favoris
  WHERE IdClient = ?
  AND IdArticle = ?;
';

const PRODUCTEUR_EST_FAVORIS = '
  SELECT *
  FROM fournisseurs_favoris
  WHERE IdClient = ?
  AND Siret = ?;
';

const ARTICLE_AJOUTER_FAVORIS = '
  INSERT INTO articles_favoris
  VALUES (?,?);
';

const PRODUCTEUR_AJOUTER_FAVORIS = '
  INSERT INTO fournisseurs_favoris
  VALUES (?,?);
';

const ARTICLE_SUPPRIMER_FAVORIS = '
  DELETE FROM articles_favoris
  WHERE IdClient = ?
  AND IdArticle = ?;
';

const PRODUCTEUR_SUPPRIMER_FAVORIS = '
  DELETE FROM fournisseurs_favoris
  WHERE IdClient = ?
  AND Siret = ?;
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



/**
 * Regarder si un article ou un fournisseur est dans les favoris
 * @param string $table "fournisseurs" ou "articles"
 * @param string|int $idClient identifiant du client
 * @param string|int $idFavoris siret du fournisseur ou identifiant de l'article en fonction de la table
 * @param mysqli $link objet de connexion à la base
 */
function estPresentFavoris(String $table, String|int $idClient, String|int $idFavoris) {
  // connexion à la base de données
  $link = dbConnect();

  // Préparation de la requête
  if ($table = 'articles')
    $stmt = $link->prepare(ARTICLE_EST_FAVORIS);
  else
    $stmt = $link->prepare(PRODUCTEUR_EST_FAVORIS);
  
  checkError($stmt, $link);
  $status = $stmt->bind_param('ii', $idClient, $idFavoris);

  // Exécution de la requête
  $status = $stmt->execute();
  checkError($status, $link);

  // Récupération du résultat
  $result = $stmt->get_result();
  checkError($result, $link);
  $resultArray = $result->fetch_all(MYSQLI_ASSOC);

  // libération de la mémoire associée
  $link->close();
  $result->close();
  $stmt->close();

  return !empty($resultArray);
}

/**
 * Suppression ou ajout d'un article ou d'un fournisseur en favoris
 * @param string $table "fournisseurs" ou "articles"
 * @param string|int $idClient identifiant du client
 * @param string|int $idFavoris siret du fournisseur ou identifiant de l'article en fonction de la table
 */
function actionsFavoris(String $table, String|int $idClient, String|int $idFavoris) : void {
  // connexion à la base de données
  $link = dbConnect();

  // requête d'ajout ou de suppression
  $present = estPresentFavoris($table, $idClient, $idFavoris, $link);
  if ($present && $table == 'articles')
    $stmt = $link->prepare(ARTICLE_SUPPRIMER_FAVORIS);
  elseif ($present && $table == 'fournisseurs')
    $stmt = $link->prepare(PRODUCTEUR_SUPPRIMER_FAVORIS);
  elseif (!$present && $table == 'articles')
    $stmt = $link->prepare(ARTICLE_AJOUTER_FAVORIS);
  else
    $stmt = $link->prepare(PRODUCTEUR_AJOUTER_FAVORIS);

  checkError($stmt, $link);
  $status = $stmt->bind_param('ii', $idClient, $idFavoris);

  // Exécution de la requête
  $status = $stmt->execute();
  checkError($status, $link);

  // Fermeture de la connexion à la base de données et libération de la mémoire associée
  $stmt->close();
  $link->close();
}
?>