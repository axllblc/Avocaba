<?php

/* 📂 Liste des articles */

error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/articles.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/fournisseur.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/articles.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_qte-article.php';


/**
 * Afficher la liste des articles.
 * @param array $listeArticles Liste d'articles
 * @return void
 */
function htmlListeArticles (array $listeArticles): void {

  if ( count($listeArticles) > 0 ) {
    echo '<ul class="ls-articles">';

    foreach ($listeArticles as $article) {
      try {
        $fournisseur = new Fournisseur($article['SiretProducteur']);
        $nomFournisseur = $fournisseur->getNom();
      } catch (Exception $e) {
        $nomFournisseur = 'non renseigné';
      }

      ?>
      <li class="ls-articles__item tile" title="<?= $article['Nom']; ?>">
        <a href="<?= '/avocaba/vues/article.php?IdArticle=' . $article['IdArticle'] ?>"
           class="ls-articles__item-content">
          <img class="ls-articles__img"
               src="<?= !empty($article['PhotoVignette']) ?
                 $article['PhotoVignette'] : ('/avocaba/img/article-placeholder.png' ) ?>"
               alt="<?= $article['Nom'] ?>">
          <div class="ls-articles__item-info">
            <div class="ls-articles__nom"><?= $article['Nom']; ?></div>
            <div class="ls-articles__fournisseur"><?= 'Fournisseur&nbsp;: ' . $nomFournisseur ?></div>
            <div class="ls-articles__prix"><?= $article['Unite'] . ' · ' . $article['Prix'] . '&nbsp;€' ?></div>
          </div>
        </a>
        <?php if(isset($_SESSION['Depot']) and count(rechercherArticle($article['IdArticle'], "idArticle", $_SESSION['Depot']['IdDepot']))>0) htmlQteArticle($article['IdArticle'], getQteArticle($article['IdArticle']), $article['Nom']);?>
      </li>
    <?php

    }

    echo '</ul>';
  } else
    echo 'Aucun article';
}
