<?php

/* ⭐ Bouton favori */

error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/favoris.inc.php';

session_start();

/**
 * Bouton favoris
 * @param string $table "fournisseurs" ou "articles"
 * @param int|string $idElement Siret ou IdArticle en fonction de $table
 */
function boutonFavoris(String $table, int|String $idElement) : void { 
  // si le client est connecté, on regarde dans la base si l'élément est dans les favoris
  $present = 0; // par défaut false
  if (isset($_SESSION['Client'])) {
    $present = estPresentFavoris($table, $_SESSION['Client']['IdClient'], $idElement);
  }
    
?>

<a href="/avocaba/traitements/favoris-actions.php?table=<?php echo $table; ?>&id=<?php echo $idElement; ?>"
   class="bouton-favoris"
  <?php if (!$present) { ?>
   title="ajouter aux favoris">
   <span class="material-icons star-border">star_border</span>
  <?php } else { ?>
   title="retirer des favoris">
   <span class="material-icons star">star</span>
  <?php } ?>
</a>

<?php }?>