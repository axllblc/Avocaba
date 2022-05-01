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
session_start();
// récupération de la recherche (minimum 2 caractères)
if (isset($_GET['recherche']) and strlen($_GET['recherche']) > 1) {
  $listeArticles = rechercherArticle($_GET['recherche'], 'nom');

  // On retire les articles dont le founisseur ne vend pas dans le dépôt
  if ($listeArticles){
    foreach ($listeArticles as $key => $article) {
      $siret = $article['SiretProducteur'];
      $fournisseur = new Fournisseur($siret);
      $IdVille = rechercherMagasin($_SESSION['IdMagasin']);
      if($fournisseur->getIdVille() != $IdVille){
        //unset($listeArticles[$key]);
      }
    }
    if (count($listeArticles)>0){
      $ok = true;
    }
  }
}

?>

<!DOCTYPE html>
<html lang="fr">
  <?php htmlHead('Recherche – Avocaba'); ?>

  <body>
    <main>
    <?php htmlHeader(); // TODO
    if($ok){
      htmlListeRayons($listeArticles);
    }
    else{
      echo "Aucun résultat, veuillez réessayer.";
    }?>
    </main>
    <?php footer(); ?>
  </body>
</html>
