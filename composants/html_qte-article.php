<?php

/* Composant permettant de choisir la quantité d'un article / ajouter, supprimer un article du panier */

error_reporting(E_ALL);

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
    <a href="<?= URL_TRAITEMENT_PANIER . '?actionPanier=' . AUGMENTER . '&id=' . $id ?>"
       title="Ajouter au cabas"
       aria-label="<?= 'Ajouter ' . $nom . ' au cabas' ?>"
       class="selection-qte__add ic-btn">
      <span class="material-icons">shopping_basket</span>
    </a>
  <?php }

  else { ?>
    <div class="panier__selection-qte">
      <input type="hidden" name="idArticle" value="<?= $id ?>">
      <a href="<?= URL_TRAITEMENT_PANIER . '?actionPanier=' . DIMINUER . '&id=' . $id ?>"
         title="Diminuer la quantité"
         aria-label="<?= 'Diminuer la quantité de ' . $nom ?>"
         class="selection-qte__dec ic-btn">
        <span class="material-icons">remove</span>
      </a>
      <input type="number" name="qte" class="selection-qte__set"
             data-id="<?= $id ?>" data-qte="<?= $qte ?>"
             min="0" max="5" value="<?= $qte ?>"
             title="Définir la quantité"
             aria-label="<?= 'Modifier la quantité de ' . $nom ?>">
      <a href="<?= URL_TRAITEMENT_PANIER . '?actionPanier=' . AUGMENTER . '&id=' . $id ?>"
         title="Augmenter la quantité"
         aria-label="<?= 'Augmenter la quantité de ' . $nom ?>"
         class="selection-qte__inc ic-btn">
        <span class="material-icons">add</span>
      </a>
      <a href="<?= URL_TRAITEMENT_PANIER . '?actionPanier=' . RETIRER . '&id=' . $id ?>"
         title="Retirer l'article du panier"
         aria-label="<?= 'Retirer ' . $nom . ' du cabas' ?>"
         class="selection-qte__rem btn">
        Retirer
      </a>
    </div>
<?php }

}
