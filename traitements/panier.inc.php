<?php

/* 🧺 Gestion du panier ("cabas") */

require_once 'articles.inc.php';

/*
 * Le contenu du panier est enregistré dans $_SESSION['Panier'].
 */



/**************
 * Constantes *
 **************/

/**
 * Quantité maximale pouvant être ajoutée au panier pour un même article.
 */
const QUANTITE_MAX = 5;



/*************
 * Fonctions *
 *************/

/**
 * Initialiser le panier dans la session.
 * Cette fonction ne fait rien si le panier est déjà initialisé.
 * @return void
 */
function initialiserPanier (): void {
  if (!isset($_SESSION)) session_start();
  if (empty($_SESSION['Panier'])) {
    $_SESSION['Panier'] = array();

    $_SESSION['Panier']['IdArticle'] = array();
    $_SESSION['Panier']['Nom'] = array();
    $_SESSION['Panier']['Prix'] = array();
    $_SESSION['Panier']['Unite'] = array();
    $_SESSION['Panier']['PhotoVignette'] = array();
    $_SESSION['Panier']['Qte'] = array();
  }
}


/**
 * Ajouter un article au panier.
 * Si l'article n'est pas présent dans le panier, il est ajouté ; sinon, sa quantité est incrémentée.
 * @param int $idArticle Identifiant de l'article à ajouter au panier.
 * @return bool Booléen indiquant le succès ou non de l'ajout : true si l'article a été ajouté ; false en cas d'échec
 *              (article inexistant, quantité maximale atteinte).
 */
function ajouterArticle (int $idArticle): bool {
  initialiserPanier();

  // Si l'article n'est pas présent dans le panier
  if ( !in_array($idArticle, $_SESSION['Panier']['IdArticle']) ) {
    // L'article à ajouter au panier est recherché dans la base de données.
    @$article = rechercherArticle($idArticle, 'idArticle')[0] ?? NULL;

    // Si l'article recherché est inexistant, il n'est pas ajouté au panier.
    if ( empty($article) ) return false;

    // L'article est ajouté au panier, avec une quantité de 1.
    array_push($_SESSION['Panier']['IdArticle'], $idArticle);
    array_push($_SESSION['Panier']['Nom'], $article['Nom']);
    array_push($_SESSION['Panier']['Prix'], $article['Prix']);
    array_push($_SESSION['Panier']['Unite'], $article['Unite']);
    array_push($_SESSION['Panier']['PhotoVignette'], $article['PhotoVignette']);
    array_push($_SESSION['Panier']['Qte'], 1);
  } else {
    /** Position de l'article dans le panier. */
    $index = array_search($idArticle, $_SESSION['Panier']['IdArticle']);

    // La quantité de l'article est incrémentée, à condition qu'elle n'excède pas la quantité maximale autorisée.
    $quantite = &$_SESSION['Panier']['Qte'][$index];
    if ($quantite < QUANTITE_MAX)
      $quantite++;
    else return false;
  }

  return true;
}


/**
 * Retirer un article du panier.
 * @param int $idArticle Identifiant de l'article à supprimer du panier.
 * @return bool Booléen indiquant le succès ou non de la suppression : true si l'article a été retiré du panier, false
 *              en cas d'échec (article absent du panier).
 */
function supprimerArticle (int $idArticle): bool {
  initialiserPanier();

  // Si l'article est présent dans le panier, il est supprimé
  if ( in_array($idArticle, $_SESSION['Panier']['IdArticle']) ) {
    /** Position de l'article dans le panier. */
    $index = array_search($idArticle, $_SESSION['Panier']['IdArticle']);

    // Suppression de l'article du panier
    array_splice($_SESSION['Panier']['IdArticle'], $index, 1);
    array_splice($_SESSION['Panier']['Nom'], $index, 1);
    array_splice($_SESSION['Panier']['Prix'], $index, 1);
    array_splice($_SESSION['Panier']['Unite'], $index, 1);
    array_splice($_SESSION['Panier']['PhotoVignette'], $index, 1);
    array_splice($_SESSION['Panier']['Qte'], $index, 1);
  } else return false;

  return true;
}


/**
 * Modifier la quantité d'un article dans le panier.
 * Si la quantité passée en paramètre est nulle, alors l'article est retiré du panier.
 * @param int $idArticle Identifiant de l'article dont la quantité doit être modifiée.
 * @param int $quantite Quantité à définir, comprise entre 0 et <code>QUANTITE_MAX</code> (bornes incluses).
 * @return bool Booléen indiquant le succès ou non de la modification : true si la quantité a été modifiée, false en cas
 *              d'échec (quantité invalide, article absent du panier).
 */
function modifierQteArticle (int $idArticle, int $quantite): bool {
  initialiserPanier();

  // Si l'article est présent dans le panier, sa quantité est modifiée
  if ( in_array($idArticle, $_SESSION['Panier']['IdArticle']) ) {

    if ( $quantite === 0 ) {
      // Suppression de l'article du panier
      return supprimerArticle($idArticle);
    }

    if ( $quantite > 0 && $quantite <= QUANTITE_MAX ) {
      /** Position de l'article dans le panier. */
      $index = array_search($idArticle, $_SESSION['Panier']['IdArticle']);

      // Modification de la quantité
      $_SESSION['Panier']['Qte'][$index] = $quantite;
      return true;
    }

  }

  return false;
}