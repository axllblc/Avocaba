<?php

/* 🧺 Gestion du panier ("cabas") */

error_reporting(E_ALL);

include_once 'panier.inc.php';

/*
 * Les fonctions de gestion du panier se trouvent dans le fichier `panier.inc.php`.
 * Le fichier `panier.main.php` (celui-ci !) contient le script permettant au client de gérer son panier au moyen d'une
 * requête GET.
 */



// ********************
// * Script principal *
// ********************

/*
 * Paramètres de la requête (GET) :
 * 'actionPanier' : Type d'action à effectuer (les valeurs autorisées sont celles définies ci-dessus).
 * Selon l'action demandée, les paramètres suivants peuvent être requis :
 * 'id' : Identifiant de l'article.
 * 'qte' : Quantité de l'article.
 */

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
}

// L'utilisateur est redirigé vers la page où il se trouvait
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
