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
if ( !isset($_SESSION['Client']) ){

  // On enregistre l'adresse à rediriger si l'utilisateur arrive sur va arriser sur la page de connexion depuis une page du site
  if(isset($_SERVER['HTTP_REFERER']) and (!str_contains($_SERVER['HTTP_REFERER'], 'signin.php') or !str_contains($_SERVER['HTTP_REFERER'], 'signup.php'))){
    $_SESSION['HTTP-TO-REFER'] = $_SERVER['HTTP_REFERER'];
  }
  header('Location: signin.php');
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
        <ul>
          <?php foreach ($articles_favoris as $key => $value) { ?>
          <li>
            <img src="<?php echo $value['PhotoVignette']; ?>" 
                 alt="image de vignette de <?php echo $value['NomArticle']; ?>">
            <div class="mes-produits__descriptif">
              <h2><a href="/avocaba/vues/article.php?IdArticle=<?php echo $value['IdArticle']; ?>"><?php echo $value['NomArticle']; ?></a></h2>
              <p><a href="/avocaba/vues/fournisseur.php?siret=<?php echo $value['SiretProducteur']; ?>" class="mes-produits__nom-producteur"><?php echo $value['NomProducteur']; ?></a></p>
              <p><?php echo $value['Prix']; ?></p>
            </div>
            <a href="">
              <span class="material-icons">delete</span>
            </a>
          </li>
          <?php } ?>
        </ul>
        <?php } ?>
    </main>

    <?php footer(); ?>
  </body>
</html>