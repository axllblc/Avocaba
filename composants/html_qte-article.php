<?php

/* Composant permettant de choisir la quantité d'un article / ajouter, supprimer un article du panier */

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/panier.inc.php';



/**
 * Afficher un composant permettant d'ajouter ou supprimer un article du panier, ou bien modifier sa quantité.
 * @param int $id Identifiant de l'article.
 * @param int $qte Quantité courante de l'article.
 * @param string $nom Nom de l'article.
 * @return void
 */
function htmlQteArticle (int $id, int $qte, string $nom = 'cet article'): void {

  if ($qte === 0) { ?>
    <a href="<?= '?actionPanier=' . AUGMENTER . '&id=' . $id ?>"
       title="Ajouter au cabas"
       aria-label="<?= 'Ajouter ' . $nom . ' au cabas' ?>">
      +
    </a>
  <?php } else { ?>
    <div class="panier__selection-qte">
      <input type="hidden" name="idArticle" value="<?= $id ?>">
      <a href="<?= '?actionPanier=' . DIMINUER . '&id=' . $id ?>"
         title="Diminuer la quantité"
         aria-label="<?= 'Diminuer la quantité de ' . $nom ?>">
        -
      </a>
      <input type="number" name="qte" class="panier__input-qte" data-id="<?= $id ?>"
             min="0" max="5" value="<?= $qte ?>"
             title="Définir la quantité"
             aria-label="<?= 'Modifier la quantité de ' . $nom ?>">
      <a href="<?= '?actionPanier=' . AUGMENTER . '&id=' . $id ?>"
         title="Augmenter la quantité"
         aria-label="<?= 'Augmenter la quantité de ' . $nom ?>">
        +
      </a>
      <a href="<?= '?actionPanier=' . RETIRER . '&id=' . $id ?>"
         title="Retirer l'article du panier"
         aria-label="<?= 'Retirer ' . $nom . ' du cabas' ?>">
        Retirer
      </a>
    </div>
<?php }

}
