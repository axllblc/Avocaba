<?php

/* 📂 Liste des rayons */

error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/rayons.inc.php';

/**
 * Afficher la liste des rayons du magasin.
 * @param int $idMagasin Identifiant du magasin
 * @return void
 */
function htmlListeRayons (int $idMagasin): void {
  $listeRayons = listeRayons($idMagasin);

  if ( count($listeRayons) > 0 ) {
    echo '<ul class="ls-rayons">';

    foreach ($listeRayons as $rayon) { ?>

      <li class="ls-rayons__item tile"
          title="<?= $rayon['Nom'] ?>">
        <a href="<?= '/avocaba/vues/recherche.php?rayon=' . $rayon['IdRayon']; ?>">
          <img src="<?= '/avocaba/img/rayons/' . $rayon['IdRayon'] . '.png'; ?>"
               alt="<?= $rayon['Nom'] ?>">
          <span><?= $rayon['Nom'] ?></span>
        </a>
      </li>

    <?php }

    echo '</ul>';
  } else {
    echo 'Aucun rayon';
  }
}
