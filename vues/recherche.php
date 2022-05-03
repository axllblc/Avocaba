<?php
/* 🧑‍🌾 Page de résultat de recherche d'articles */

// TODO: Améliorer le rendu du résultat de la recherche : rendre la liste plus responsive, adapter la taille des images, implémenter les boutons d'ajout au panier, améliorer le rendu d'une tuile d'article

error_reporting(E_ALL);

require_once '../traitements/articles.inc.php';
require_once '../traitements/recherche-magasin.php';
require_once '../traitements/misc.inc.php';
require_once '../composants/html_head.php';
require_once '../composants/html_header.php';
require_once '../composants/html_liste-articles.php';
require_once '../composants/footer.php';



/********************
 * Script principal *
 ********************/

$ok = false;
$q = "";
session_start();
// Récupération des résultats de la recherche (minimum 2 caractères)
if (isset($_GET['recherche']) and strlen($_GET['recherche']) > 1) {
  $q = $_GET['recherche'];
  $idDepot = 'aucun';

  if(isset($_SESSION['IdMagasin'])){
    // Cas où l'on est connecté à un magasin : on affiche que les articles du magasin
    $idDepot = $_SESSION['IdMagasin'];
    $listeArticles = rechercherArticle($q, 'nomArticle', $idDepot);
  }
  else{
    // Pas de magasin renseigné, l'utilisateur peut consulter tout les articles de Avocaba
    $listeArticles = rechercherArticle($q, 'nomArticle', 'aucun');
  }
  if ($listeArticles != null){
    $ok = true;
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
