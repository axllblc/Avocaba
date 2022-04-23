<?php

/* 📂 Liste des rayons */

// TODO créer la vue articles (recherche d'articles)
// TODO ajouter les images des rayons

require_once '../traitements/liste-rayons.php';

/**
 * Afficher la liste des rayons du magasin.
 * @param int $idMagasin Identifiant du magasin
 * @return void
 */
function htmlListeRayons (int $idMagasin): void {
  $listeRayons = listeRayons($idMagasin);

  if ( count($listeRayons) > 0 ) {
    echo '<ul class="ls-rayons" style="background-color: gainsboro;">';

    foreach ($listeRayons as $rayon) { ?>
      <li class="ls-rayons__item"
          title="<?php echo $rayon['Nom'] ?>">
        <a href="/avocaba/vues/articles.php?rayon=<?php echo $rayon['IdRayon'] ?>">
          <img src="<?php // TODO à compléter ?>"
               alt="<?php echo $rayon['Nom'] ?>">
          <span><?php echo $rayon['Nom'] ?></span>
        </a>
      </li>
    <?php }

    echo '</ul>';
  } else {
    echo 'Aucun rayon';
  }
}

