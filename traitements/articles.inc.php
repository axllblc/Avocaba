<?php

/* Liste d'articles */

require_once 'db.inc.php';
require_once 'misc.inc.php';

/**************
 * Constantes *
 **************/

// Expressions régulières

const REGEX_NOM = '/^[\dA-Za-z\s]{2,30}$/';
const REGEX_ID = '/^[\d]{1,15}$/';


// Requêtes à préparer

const RECHERCHE_ARTICLE_ID_RAYON = '
SELECT DISTINCT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, a.IdRayon, a.SiretProducteur, f.IdVille
FROM ARTICLES a
INNER JOIN FOURNISSEURS f ON a.SiretProducteur = f.Siret
WHERE IdRayon = ? AND IdVille = ?
';

const RECHERCHE_ARTICLE_NOM_RAYON = '
SELECT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, a.IdRayon, a.SiretProducteur, f.IdVille
FROM ARTICLES a
INNER JOIN RAYONS r USING (IdRayon)
INNER JOIN FOURNISSEURS f ON a.SiretProducteur = f.Siret
WHERE r.Nom LIKE ? AND IdVille = ?
';

const RECHERCHE_ARTICLE_ID_FOURNISSEUR = '
SELECT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, a.IdRayon, a.SiretProducteur, f.IdVille
FROM ARTICLES a
INNER JOIN FOURNISSEURS f ON a.SiretProducteur = f.Siret
WHERE SiretProducteur = ? AND IdVille = ?
';

const RECHERCHE_ARTICLE_NOM_FOURNISSEUR = '
SELECT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, a.IdRayon, a.SiretProducteur, f.IdVille
FROM ARTICLES a
INNER JOIN FOURNISSEURS f ON a.SiretProducteur = f.Siret
WHERE f.Nom LIKE ? AND IdVille = ?
';

const RECHERCHE_ARTICLE_ID_ARTICLE = '
SELECT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, a.IdRayon, a.SiretProducteur, f.IdVille
FROM ARTICLES a
INNER JOIN FOURNISSEURS f ON a.SiretProducteur = f.Siret
WHERE IdArticle = ? AND IdVille = ?
';

const RECHERCHE_ARTICLE_NOM_ARTICLE = '
SELECT DISTINCT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, a.IdRayon, a.SiretProducteur, f.IdVille
FROM ARTICLES a
INNER JOIN FOURNISSEURS f ON a.SiretProducteur = f.Siret
LEFT JOIN MOTS_CLES_ARTICLES USING (IdArticle)
LEFT JOIN MOTS_CLES m USING (IdMotCle)
WHERE (a.Nom LIKE ? OR m.Nom LIKE ?) AND IdVille = ?
';

const RECHERCHE_ARTICLE_NOM = RECHERCHE_ARTICLE_NOM_FOURNISSEUR.' UNION '.RECHERCHE_ARTICLE_NOM_RAYON.' UNION '.RECHERCHE_ARTICLE_NOM_ARTICLE;

// NOTE : On ne peut pas utiliser la syntaxe "%?%" dans les requêtes préparées, donc c'est la variable qui sera entourée de "%"


/*************
 * Fonctions *
 *************/

/**
 * Rechercher un/des articles.
 * @param string|int $critere Critère de recherche : valeur
 * @param string $nature Nature du critère (au choix) : idArticle, nomArticle, idFournisseur, nomFournisseur, idRayon, nomRayon, nom (nom d'article, du fournisseur ou du rayon)
 * @param string|int $IdVille Identifiant de la ville où l'article doit être en vente (facultatif)
 * @return array Un tableau contenant les résultats (articles décrits par leur identifiant, nom,
 *               prix, prix relatif, unité, description, la photo de leur vignette (adresse),
 *               le booléen si produit est phare, l'identifiant du rayon auquel il appartient,
 *               le SIRET du fournisseur
 */
function rechercherArticle (string|int $critere, string $nature, string|int $IdVille = "IdVille"): array {
  if(is_int($IdVille)){
    $IdVille = strval($IdVille);
  }
  $link = dbConnect();

  // Préparation de la requête
  switch ($nature) {
    case 'idArticle':
      if (preg_match(REGEX_ID, $critere)) {
        // Rechercher l'article qui correspond à l'identifiant

        $stmt = $link->prepare(RECHERCHE_ARTICLE_ID_ARTICLE);
        checkError($stmt, $link);

        $status = $stmt->bind_param('is', $critere, $IdVille);
      }
      else{
        //Cas où le regex n'est pas bon
        $link->close();
        return array();
      }
      break;
    case 'nomArticle':
      $critere = slugify($critere);
      if (preg_match(REGEX_NOM, $critere)) {
        // Rechercher les articles correspondant à la recherche (chaine de caractère)

        $stmt = $link->prepare(RECHERCHE_ARTICLE_NOM_ARTICLE);
        checkError($stmt, $link);

        $critere = '%'.$critere.'%';

        $status = $stmt->bind_param('sss', $critere, $critere, $IdVille);
      }
      else{
        //Cas où le regex n'est pas bon
        $link->close();
        return array();
      }
      break;
    case 'idRayon':
      if (preg_match(REGEX_ID, $critere)) {
        // Rechercher les articles vendus dans le rayon (par l'identifiant)

        $stmt = $link->prepare(RECHERCHE_ARTICLE_ID_RAYON);
        checkError($stmt, $link);

        $status = $stmt->bind_param('is', $critere, $IdVille);
      }
      else{
        //Cas où le regex n'est pas bon
        $link->close();
        return array();
      }
      break;
    case 'nomRayon':
      $critere = slugify($critere);
      if (preg_match(REGEX_NOM, $critere)) {
        // Rechercher les articles vendus par le rayo (par le nom du rayon)

        $stmt = $link->prepare(RECHERCHE_ARTICLE_NOM_RAYON);
        checkError($stmt, $link);

        $critere = '%'.$critere.'%';

        $status = $stmt->bind_param('ss', $critere, $IdVille);
      }
      else{
        //Cas où le regex n'est pas bon
        $link->close();
        return array();
      }
      break;
    case 'idFournisseur':
      if (preg_match(REGEX_ID, $critere)) {
        // Rechercher les articles vendus par le fournisseur (par l'identifiant)

        $stmt = $link->prepare(RECHERCHE_ARTICLE_ID_FOURNISSEUR);
        checkError($stmt, $link);

        $status = $stmt->bind_param('is', $critere, $IdVille);
      }
      else{
        //Cas où le regex n'est pas bon
        $link->close();
        return array();
      }
      break;
    case 'nomFournisseur':
      $critere = slugify($critere);
      if (preg_match(REGEX_NOM, $critere)) {
        // Rechercher les articles vendus par le fournisseur (par le nom du fournisseur)
        $stmt = $link->prepare(RECHERCHE_ARTICLE_NOM_FOURNISSEUR);
        checkError($stmt, $link);

        $critere = '%'.$critere.'%';

        $status = $stmt->bind_param('ss', $critere, $IdVille);
      }
      else{
        //Cas où le regex n'est pas bon
        $link->close();
        return array();
      }
      break;

    default:
    if (preg_match(REGEX_NOM, $critere)) {
      // Rechercher les articles par le nom du rayon, de l'article ou du fournisseur

      $stmt = $link->prepare(RECHERCHE_ARTICLE_NOM);
      checkError($stmt, $link);

      $critere = '%'.$critere.'%';

      $status = $stmt->bind_param('sssssss', $critere, $IdVille, $critere, $IdVille, $critere, $critere, $IdVille);
      checkError($status, $link);
    }
    else{
      //Cas où le regex n'est pas bon
      $link->close();
      return array();
    }
    break;
  }

  checkError($link, $link);
  checkError($stmt, $link);
  checkError($status, $link);

  // Exécution de la requête
  $status = $stmt->execute();
  checkError($status, $link);

  // Récupération du résultat
  $result = $stmt->get_result();
  checkError($result, $link);

  $resultArray = $result->fetch_all(MYSQLI_ASSOC);

  // Fermeture de la connexion à la base de données
  $link->close();

  return $resultArray;
}

// exemple : var_dump(rechercherArticle("boulang", "nom"));
