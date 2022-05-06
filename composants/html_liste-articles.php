<?php

// TODO: Mieux afficher les images (régler la taille)

/* 📂 Liste des articles */

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/articles.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/fournisseur.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/articles.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_qte-article.php';



/**
 * Afficher la liste des articles.
 * @param array $listeArticles Liste d'articles
 * @return void
 */
function htmlListeArticles ($listeArticles): void {

  if ( count($listeArticles) > 0 ) {
    echo '<ul class="ls-articles" style="background-color: gainsboro;">';

    foreach ($listeArticles as $article) {
      try {
        $fournisseur = new Fournisseur($article['SiretProducteur']);
        if(is_object($fournisseur)){
          $nomFournisseur = $fournisseur->getNom();
        }
        else{
          $nomFournisseur = "non renseigné";
        }
      } catch (Exception $e) {
        $nomFournisseur = "non renseigné";
      }?>
      <li class="ls-articles__item" title="<?php echo $article['Nom']; ?>">
        <a class="ls-articles__description" href="/avocaba/vues/article.php?IdArticle=<?php echo $article['IdArticle'] ?>">
          <img class="ls-articles__img" src="<?php echo $article['PhotoVignette']; ?>" alt="<?php echo $article['Nom']; ?>">
          <p class="ls-articles__titre-article"><?php echo $article['Nom']; ?></p>
          <p class="ls-articles__producteur">Producteur : <?php if($nomFournisseur) echo $nomFournisseur; ?></p>
          <div class="ls-articles__prix"><?php echo $article['Nom']; ?>
            <?php htmlQteArticle($article['IdArticle'], getQteArticle($article['IdArticle']), $article['Nom']);?>
          </div>
        </a>
      </li>
    <?php }
    echo '</ul>';
  } else {
    echo 'Aucun article';
  }
}
