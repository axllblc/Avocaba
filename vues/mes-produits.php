<?php
/* Page des produits favoris */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/favoris.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/misc.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/error.php';

session_start();

// Si l'utilisateur n'est pas connecté, il est redirigé vers la page de connexion.
if(!isset($_SESSION['Client'])){
  header('Location: espace-client/account.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
  <?php htmlHead('Mes produits – Avocaba'); ?>

  <body>
    <?php htmlHeader(!empty($_SESSION['Depot'])); ?>

    <main>
      <h1>Mes Produits</h1>

      <?php 
      $articles_favoris = getArticlesFavoris($_SESSION['Client']['IdClient']);

      if (count($articles_favoris) == 0) {?>
      <p>Vous n'avez pas d'articles en favoris</p>
      <?php
      } else {
        ?>
        <table class="mes-produits__tableaux">
          <tbody>
            <?php foreach ($articles_favoris as $key => $value) { ?>
            <tr class="mes-produits__produit">
              <td class="mes-produits__cellule-image">
                <img src="<?= !empty($value['PhotoVignette']) ? $value['PhotoVignette'] : '/avocaba/img/article-placeholder.png' ?>" 
                    alt="image de vignette de <?php echo $value['NomArticle']; ?>">
              </td>

              <td class="mes-produits__descriptif">
                <h2><a href="/avocaba/vues/article.php?IdArticle=<?php echo $value['IdArticle']; ?>"><?php echo $value['NomArticle']; ?></a></h2>
                <p><a href="/avocaba/vues/fournisseur.php?siret=<?php echo $value['SiretProducteur']; ?>" class="mes-produits__nom-producteur"><?php echo $value['NomProducteur']; ?></a></p>
                <p><?php echo $value['Prix']; ?> €</p>
              </td>

              <td>
                <a href="/avocaba/traitements/favoris-actions.php?table=articles&id=<?php echo $value['IdArticle']; ?>">
                  <span class="material-icons mes-produit__delete">delete</span>
                </a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php } ?>
    </main>

    <?php footer(); ?>
  </body>
</html>