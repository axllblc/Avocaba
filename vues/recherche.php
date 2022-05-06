<?php

/* 🧑‍🌾 Page de résultat de recherche d'articles */

// TODO: Améliorer le rendu du résultat de la recherche : rendre la liste plus responsive, adapter la taille des images, implémenter les boutons d'ajout au panier, améliorer le rendu d'une tuile d'article

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/articles.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/nom-rayon.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/magasin.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/misc.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_liste-articles.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';



/********************
 * Script principal *
 ********************/

$ok = false;
$q = "";
session_start();
// Récupération des résultats si il y a eu une recherche textuel (minimum 2 caractères)
if (isset($_GET['recherche']) and strlen($_GET['recherche']) > 1) {
  $q = $_GET['recherche'];
  $idDepot = 'aucun';

  if (isset($_SESSION['Depot']['IdDepot'])) {
    // Cas où l'on est connecté à un magasin : on affiche que les articles du magasin
    $idDepot = $_SESSION['Depot']['IdDepot'];
    $listeArticles = rechercherArticle($q, 'nomArticle', $idDepot);
  }
  else {
    // Pas de magasin renseigné, l'utilisateur peut consulter tout les articles de Avocaba
    $listeArticles = rechercherArticle($q, 'nomArticle', 'aucun');
  }
  if ($listeArticles != null) {
    $ok = true;
  }
}

// Récupération des résultats si il y a eu une recherche par rayon
if( isset($_GET['rayon']) ){
  $idRayon = $_GET['rayon'];
  $q = nomRayon($idRayon);
  if($q){
    // Si le rayon existe bien
    if (isset($_SESSION['Depot']['IdDepot'])) {
      // Cas où l'on est connecté à un magasin : on affiche que les articles du magasin
      $idDepot = $_SESSION['Depot']['IdDepot'];
      $listeArticles = rechercherArticle($idRayon, 'idRayon', $idDepot);
    }
    else {
      // Pas de magasin renseigné, l'utilisateur peut consulter tout les articles de Avocaba
      $listeArticles = rechercherArticle($idRayon, 'idRayon', 'aucun');
    }
    if ($listeArticles != null) {
      $ok = true;
    }

  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php htmlHead('🔍 ' . $q . ' – Avocaba'); ?>

<body>
  <?php htmlHeader(true, $q); ?>
  <main>
    <?php
      if ($ok)
        htmlListeArticles($listeArticles);
      else
        echo "Aucun résultat, veuillez réessayer.";
    ?>
  </main>
  <?php footer(); ?>
</body>
</html>
