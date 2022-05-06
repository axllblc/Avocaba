<?php

/* 🧺 Gestion du panier ("cabas") */

require_once 'articles.inc.php';

/*
 * Le contenu du panier est enregistré dans $_SESSION['Panier'].
 */



// **************
// * Constantes *
// **************

/** Quantité maximale pouvant être ajoutée au panier pour un même article. */
const QUANTITE_MAX = 5;

// Actions sur le panier
const DIMINUER     = 'dec';
const AUGMENTER    = 'inc';
const MODIFIER     = 'set';
const RETIRER      = 'rem';
const VIDER_PANIER = 'emp';



// *************
// * Fonctions *
// *************

// Fonctions de gestion du panier

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
    $_SESSION['Panier']['Verrouillage'] = false;
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

  // Si le panier est verrouillé, l'ajout n'est pas effectué.
  if ( $_SESSION['Panier']['Verrouillage'] ) return false;

  /** Position de l'article dans le panier. */
  $index = array_search($idArticle, $_SESSION['Panier']['IdArticle']);

  // Si l'article n'est pas présent dans le panier
  if ( $index === false ) {
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

  // Si le panier est verrouillé, la suppression n'est pas effectuée.
  if ( $_SESSION['Panier']['Verrouillage'] ) return false;

  /** Position de l'article dans le panier. */
  $index = array_search($idArticle, $_SESSION['Panier']['IdArticle']);

  // Si l'article est présent dans le panier, il est supprimé
  if ( $index !== false ) {
    // Suppression de l'article du panier
    array_splice($_SESSION['Panier']['IdArticle'], $index, 1);
    array_splice($_SESSION['Panier']['Nom'], $index, 1);
    array_splice($_SESSION['Panier']['Prix'], $index, 1);
    array_splice($_SESSION['Panier']['Unite'], $index, 1);
    array_splice($_SESSION['Panier']['PhotoVignette'], $index, 1);
    array_splice($_SESSION['Panier']['Qte'], $index, 1);

    return true;
  }

  return false;
}


/**
 * Modifier la quantité d'un article dans le panier.
 * Si la quantité passée en paramètre est négative ou nulle, alors l'article est retiré du panier.
 * @param int $idArticle Identifiant de l'article dont la quantité doit être modifiée.
 * @param int $quantite Quantité à définir, inférieure à <code>QUANTITE_MAX</code> (inclus).
 * @return bool Booléen indiquant le succès ou non de la modification : true si la quantité a été modifiée, false en cas
 *              d'échec (quantité invalide, article absent du panier).
 */
function modifierQteArticle (int $idArticle, int $quantite): bool {
  initialiserPanier();

  // Si le panier est verrouillé, la modification n'est pas effectuée.
  if ( $_SESSION['Panier']['Verrouillage'] ) return false;

  /** Position de l'article dans le panier. */
  $index = array_search($idArticle, $_SESSION['Panier']['IdArticle']);

  // Si l'article est présent dans le panier, sa quantité est modifiée
  if ( $index !== false ) {

    if ( $quantite <= 0 ) {
      // Suppression de l'article du panier
      return supprimerArticle($idArticle);
    }

    if ( $quantite <= QUANTITE_MAX ) {
      // Modification de la quantité
      $_SESSION['Panier']['Qte'][$index] = $quantite;
      return true;
    }

  }

  return false;
}


/**
 * Décrémenter la quantité d'un article dans le panier. L'article est retiré du panier si sa quantité devient nulle.
 * @param int $idArticle Article dont la quantité doit être décrémentée.
 * @return bool Booléen indiquant le succès ou non de la modification : true si la quantité a été modifiée, false en cas
 *              d'échec (article absent du panier).
 */
function diminuerQteArticle (int $idArticle): bool {
  initialiserPanier();

  // Si le panier est verrouillé, la modification n'est pas effectuée.
  if ( $_SESSION['Panier']['Verrouillage'] ) return false;

  /** Position de l'article dans le panier. */
  $index = array_search($idArticle, $_SESSION['Panier']['IdArticle']);

  // Si l'article est présent dans le panier
  if ($index !== false) {
    // La quantité de l'article est décrémentée.
    $quantite = &$_SESSION['Panier']['Qte'][$index];
    if ($quantite > 1) {
      $quantite--;
      return true;
    } else return supprimerArticle($idArticle);
  }

  return false;
}


/**
 * Obtenir la quantité d'un article dans le panier.
 * @param int $idArticle Article dont on souhaite connaître la quantité.
 * @return int Quantité de l'article. Vaut 0 si l'article est absent du panier.
 */
function getQteArticle (int $idArticle): int {
  initialiserPanier();

  /** Position de l'article dans le panier. */
  $index = array_search($idArticle, $_SESSION['Panier']['IdArticle']);

  if ($index !== false) {
    return $_SESSION['Panier']['Qte'][$index];
  }

  return 0;
}


/**
 * Calculer le montant total du panier.
 * @return int|float Montant total du panier
 */
function montantPanier (): int|float {
  initialiserPanier();

  $montantPanier = 0;

  foreach ( $_SESSION['Panier']['Prix'] as $key => $prix ) {
    $montantPanier += $prix * $_SESSION['Panier']['Qte'][$key];
  }

  return $montantPanier;
}


/**
 * Calculer le nombre d'articles différents présents dans le panier (chaque article présent dans le panier est compté
 * une fois).
 * @return int Nombre d'articles différents présents dans le panier
 */
function nbArticles (): int {
  initialiserPanier();

  return count( $_SESSION['Panier']['IdArticle'] );
}


/**
 * Calculer le nombre total d'articles présents dans le panier (chaque article présent dans le panier est compté
 * <i>n</i> fois, où <i>n</i> est la quantité de l'article).
 * @return int Nombre total d'articles présents dans le panier
 */
function nbArticlesTotal (): int {
  initialiserPanier();

  $nbArticles = 0;

  foreach ($_SESSION['Panier']['Qte'] as $qte) {
    $nbArticles += $qte;
  }

  return $nbArticles;
}


/**
 * Supprimer le panier.
 * @return void
 */
function supprimerPanier (): void {
  unset($_SESSION['Panier']);
}



// Actions sur le panier

/**
 * Vérifier la présence du paramètre <code>$_GET['actionPanier']</code> dans la requête et modifier l'état du panier.
 * @return bool Booléen indiquant le succès ou non de la modification de l'état du panier
 */
function actionPanier (): bool {
  initialiserPanier();

  $status = false;

  if ( !empty($_GET['actionPanier']) ) {
    switch ($_GET['actionPanier']) {
      case DIMINUER:
        if ( !empty($_GET['id']) && is_numeric($_GET['id']) )
          $status = diminuerQteArticle($_GET['id']);
        break;
      case AUGMENTER:
        if ( !empty($_GET['id']) && is_numeric($_GET['id']) )
          $status = ajouterArticle($_GET['id']);
        break;
      case MODIFIER:
        if ( is_numeric($_GET['id']) && is_numeric($_GET['qte']) )
          $status = modifierQteArticle($_GET['id'], $_GET['qte']);
        break;
      case RETIRER:
        if ( !empty($_GET['id']) && is_numeric($_GET['id']) )
          $status = supprimerArticle($_GET['id']);
        break;
      case VIDER_PANIER:
        supprimerPanier();
        $status = true;
        break;
    }

    /*
     * Si la modification de l'état du panier a fonctionné, l'utilisateur est redirigé vers la même page, afin de
     * supprimer les paramètres passés dans l'URL.
     * Cela permet d'éviter des modifications répétées de l'état du panier si l'utilisateur navigue avec les boutons
     * [Précédent] et [Suivant] du navigateur.
     */
    if ($status) {
      header('Location: ?');
      exit;
    }
  } else $status = true;

  return $status;
}
