<?php

/* 📂 Liste des rayons */

// TODO : ajouter le lien vers la page de résultats

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/liste-rayons.php';

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
        <a href="<?php echo '/avocaba/vues/articles.php?rayon=' . $rayon['IdRayon']; ?>">
          <img src="<?php echo '/avocaba/img/rayons/' . $rayon['IdRayon'] . '.png'; ?>"
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

