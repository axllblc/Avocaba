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
  if (empty($_SESSION['Panier'])) $_SESSION['Panier'] = array();
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
  if ( !isset($_SESSION['Panier'][$idArticle]) ) {
    // L'article à ajouter au panier est recherché dans la base de données.
    @$article = rechercherArticle($idArticle, 'idArticle')[0];

    // Si l'article recherché est inexistant, il n'est pas ajouté au panier.
    if ( empty($article) ) return false;

    // L'article est ajouté au panier, avec une quantité de 1.
    $_SESSION['Panier'][$idArticle] = array(
      'Nom' => $article['Nom'],
      'Prix' => $article['Prix'],
      'PhotoVignette' => $article['PhotoVignette'],
      'Qte' => 1
    );
  } else {
    // La quantité de l'article est incrémentée, à condition qu'elle n'excède pas la quantité maximale autorisée.
    $quantite = &$_SESSION['Panier'][$idArticle]['Qte'];
    if ($quantite < QUANTITE_MAX)
      $quantite++;
    else return false;
  }

  return true;
}

