<?php

// TODO: COLLATION pour recherche selon nom rayon

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

// Les déclarations de variables
const VARIABLE_CRITERE = '
SET @critere = ?;
';

const VARIABLE_DEPOT = '
SET @idDepot = ?;
';

//Les projections
const RECHERCHE_ARTICLE_ID_RAYON = '
SELECT DISTINCT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, r.IdRayon, a.SiretProducteur, r.Nom AS NomRayon
FROM ARTICLES a
LEFT JOIN RAYONS r USING (IdRayon)
INNER JOIN STOCKER s USING (IdArticle)
WHERE IdRayon = @critere AND (s.IdDepot = @idDepot OR @idDepot = \'aucun\')
';

const RECHERCHE_ARTICLE_NOM_RAYON = '
SELECT DISTINCT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, a.IdRayon, a.SiretProducteur, r.Nom AS NomRayon
FROM ARTICLES a
INNER JOIN RAYONS r USING (IdRayon)
INNER JOIN STOCKER s USING (IdArticle)
WHERE lower(r.Nom) LIKE @critere AND (s.IdDepot = @idDepot OR @idDepot = \'aucun\')
';

const RECHERCHE_ARTICLE_ID_FOURNISSEUR = '
SELECT DISTINCT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, a.IdRayon, a.SiretProducteur, r.Nom AS NomRayon
FROM ARTICLES a
LEFT JOIN RAYONS r USING (IdRayon)
INNER JOIN FOURNISSEURS f ON a.SiretProducteur = f.Siret
INNER JOIN STOCKER s USING (IdArticle)
WHERE SiretProducteur = @critere AND (s.IdDepot = @idDepot OR @idDepot = \'aucun\')
';

const RECHERCHE_ARTICLE_NOM_FOURNISSEUR = '
SELECT DISTINCT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, a.IdRayon, a.SiretProducteur, r.Nom AS NomRayon
FROM ARTICLES a
INNER JOIN STOCKER s USING (IdArticle)
LEFT JOIN RAYONS r USING (IdRayon)
INNER JOIN FOURNISSEURS f ON a.SiretProducteur = f.Siret
WHERE f.Nom LIKE @critere AND (s.IdDepot = @idDepot OR @idDepot = \'aucun\')
';

const RECHERCHE_ARTICLE_ID_ARTICLE = '
SELECT DISTINCT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, a.IdRayon, a.SiretProducteur, r.Nom AS NomRayon
FROM ARTICLES a
LEFT JOIN RAYONS r USING (IdRayon)
INNER JOIN STOCKER s USING (IdArticle)
WHERE IdArticle = @critere AND (s.IdDepot = @idDepot OR @idDepot = \'aucun\');
';

const RECHERCHE_ARTICLE_NOM_ARTICLE = '
SELECT DISTINCT a.IdArticle, a.Nom, a.Prix, a.PrixRelatif, a.Unite, a.Description,
        a.PhotoVignette, a.ProduitPhare, a.IdRayon, a.SiretProducteur, r.Nom AS NomRayon
FROM ARTICLES a
INNER JOIN STOCKER s USING (IdArticle)
LEFT JOIN RAYONS r USING (IdRayon)
LEFT JOIN MOTS_CLES_ARTICLES USING (IdArticle)
LEFT JOIN MOTS_CLES m USING (IdMotCle)
WHERE (a.Nom LIKE @critere OR m.Nom LIKE @critere) AND (s.IdDepot = @idDepot OR @idDepot = \'aucun\')
';

const RECHERCHE_ARTICLE_NOM = RECHERCHE_ARTICLE_NOM_FOURNISSEUR.' UNION '.RECHERCHE_ARTICLE_NOM_RAYON.' UNION '.RECHERCHE_ARTICLE_NOM_ARTICLE;

//Pour utiliser la même collation
const COLLATION = "SET character_set_server = 'utf8mb4_general_ci';";
/*************
 * Fonctions *
 *************/

/**
 * Rechercher un/des articles.
 * @param string|int $critere Critère de recherche : valeur
 * @param string $nature Nature du critère (au choix) : idArticle, nomArticle, idFournisseur, nomFournisseur, idRayon, nomRayon, nom (nom d'article, du fournisseur ou du rayon)
 * @param string|int $idDepot Identifiant du dépôt où l'article doit être en vente (facultatif)
 * @return array Un tableau contenant les résultats (articles décrits par leur identifiant, nom,
 *               prix, prix relatif, unité, description, la photo de leur vignette (adresse),
 *               le booléen si produit est phare, l'identifiant et le nom du rayon auquel il appartient,
 *               le SIRET du fournisseur
 */
function rechercherArticle (string|int $critere, string $nature, string|int $idDepot = 'aucun'): array {
  if(is_int($idDepot)){
    $idDepot = strval($idDepot);
  }

  // Si le critère n'est pas un identifiant, on doit l'entourer de % pour la clause WHERE avec LIKE
  if(!in_array($nature, array('idArticle', 'idFournisseur', 'idRayon'))){
    $critere = slugify($critere);
    if (preg_match(REGEX_NOM, $critere)) {
      $critere = '%'.$critere.'%';
    }
    else {
      return array();
    }
  }
  else{
    if (!preg_match(REGEX_ID, $critere)) {
      return array();
    }
  }

  $link = dbConnect();

  //Pour bien utiliser le bon interclassement
  $stmtCollation = $link->prepare(COLLATION);
  checkError($stmtCollation, $link);
  $stmtCollation->execute();
  checkError($stmtCollation, $link);

  $stmtCritere = $link->prepare(VARIABLE_CRITERE);
  checkError($stmtCritere, $link);
  $status = $stmtCritere->bind_param('s', $critere);
  checkError($status, $link);

  $stmtDepot = $link->prepare(VARIABLE_DEPOT);
  $status = $stmtDepot->bind_param('s',$idDepot);
  checkError($stmtDepot, $link);

  // Préparation de la requête
  switch ($nature) {
    case 'idArticle':
      // Rechercher l'article qui correspond à l'identifiant
      $stmtRecherche = $link->prepare(RECHERCHE_ARTICLE_ID_ARTICLE);
      checkError($stmtRecherche, $link);
      break;
    case 'nomArticle':
      // Rechercher les articles selon leur nom
      $stmtRecherche = $link->prepare(RECHERCHE_ARTICLE_NOM_ARTICLE);
      checkError($stmtRecherche, $link);
      break;
    case 'idRayon':
      // Rechercher les articles selon l'identifiant du rayon auquel il appartient
      $stmtRecherche = $link->prepare(RECHERCHE_ARTICLE_ID_RAYON);
      checkError($stmtRecherche, $link);
      break;
    case 'nomRayon':
      // Rechercher les articles vendus par le rayon (par le nom du rayon)
      $stmtRecherche = $link->prepare(RECHERCHE_ARTICLE_NOM_RAYON);
      checkError($stmtRecherche, $link);
      break;
    case 'idFournisseur':
      // Rechercher l'article vendu par un fournisseur (identifiant)
      $stmtRecherche = $link->prepare(RECHERCHE_ARTICLE_ID_FOURNISSEUR);
      checkError($stmtRecherche, $link);
      break;
    case 'nomFournisseur':
      // Rechercher les articles vendus par le fournisseur (par le nom du fournisseur)
      $stmtRecherche = $link->prepare(RECHERCHE_ARTICLE_NOM_FOURNISSEUR);
      checkError($stmtRecherche, $link);
      break;

    default:
      // Rechercher les articles par le nom du rayon, de l'article ou du fournisseur
      $stmtRecherche = $link->prepare(RECHERCHE_ARTICLE_NOM);
      checkError($stmtRecherche, $link);
      break;
  }

  checkError($link, $link);

  // Exécution de la requête
  $statusCritere = $stmtCritere->execute();
  checkError($statusCritere, $link);
  $statusDepot = $stmtDepot->execute();
  checkError($statusDepot, $link);
  $statusRecherche = $stmtRecherche->execute();
  checkError($statusRecherche, $link);

  // Récupération du résultat
  $result = $stmtRecherche->get_result();
  checkError($result, $link);

  $resultArray = $result->fetch_all(MYSQLI_ASSOC);

  // Fermeture de la connexion à la base de données
  $link->close();

  return $resultArray;
}

// exemple : var_dump(rechercherArticle(1, "idArticle"));
