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
