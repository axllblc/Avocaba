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
// récupération de la recherche (minimum 2 caractères)
if (isset($_GET['recherche']) and strlen($_GET['recherche']) > 1) {
  $q = $_GET['recherche'];
  $IdVille = rechercherMagasin($_SESSION['IdMagasin'], true)[0]['IdVille'];
  $listeArticles = rechercherArticle($_GET['recherche'], 'nom', $IdVille);
  if ($listeArticles){
    $ok = true;
  }
}

?>

<!DOCTYPE html>
<html lang="fr">
<?php htmlHead($q . ' – Avocaba'); ?>

  <body>
    <?php htmlHeader($q); ?>
    <main>
      <?php
        if($ok){
          htmlListeRayons($listeArticles);
        }
        else{
          echo "Aucun résultat, veuillez réessayer.";
        }
      ?>
    </main>
    <?php footer(); ?>
  </body>
</html>
