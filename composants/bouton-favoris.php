<?php
// "bouton" favoris

/**
 * Bouton favoris
 * @param string $table "fournisseurs" ou "articles"
 * @param int|string $idElement Siret ou IdArticle en fonction de $table
 */
function boutonFavoris(String $table, int|String $idElement) : void { ?>

<a href="/avocaba/traitements/favoris-actions.php?table=<?php echo $table; ?>&id=<?php echo $idElement; ?>">
  <span class="material-icons">star</span>
</a>

<?php }?>