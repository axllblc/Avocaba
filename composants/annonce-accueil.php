<?php

/* 📣 Annonces sur la page d'accueil */

error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/fournisseur.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/articles.inc.php';

/**
 * Composant annonce sur la page d'accueil
 * @param string|int $depot Identifiant du dépôt actif du client
 */
function annonceAccueil(string|int $depot) : void {
  // récupération des fournisseurs sur le dépôt du client
  $fournisseurs = Fournisseur::fournisseurSurDepot($depot);

  // choix aléatoire d'un fournisseur
  $siret_fournisseur = $fournisseurs[rand(0, count($fournisseurs)-1)];
  try {
    $fournisseur = new Fournisseur($siret_fournisseur);
  } catch (Exception $e) {
    return;
  }

  ?>
<div class="annonce-accueil">
  <div class="annonce-accueil__slideshow-container">
    <?php
    $photos = $fournisseur->photoFournisseur();
    foreach ($photos as $key => $value) {
    ?>
    <div class="annonce-accueil__mySlides annonce-accueil__fade">
      <img src="<?php echo $value; ?>"
           alt="photo <?php echo $fournisseur->getNom(); echo ' '.$key; ?>">
    </div>
    <?php } ?>
  </div>

  <div class="annonce-accueil__droite">
    <h2 class="annonce-accueil__decouvrir">Venez découvrir <?php echo $fournisseur->getNom(); ?></h2>
    <p class="annonce-accueil__produits-phares">
      Découvrez les produits proposés par <b><?php echo $fournisseur->getNom(); ?></b>&nbsp;:
      <?php
      $produits_phares = $fournisseur->produitsPhares();
      $i = 0;
      while ($i < count($produits_phares)-1) {
        $produit = rechercherArticle($produits_phares[$i], 'idArticle', $depot);
        if($produit != null){
          $produit = $produit[0];

      ?>
      <a href="/avocaba/vues/article.php?IdArticle=<?php echo $produit['IdArticle']; ?>"><?php echo $produit['Nom']; ?></a>,
      <?php
        }
        $i++;
      }
      $produit = rechercherArticle($produits_phares[$i], 'idArticle', $depot);
      if($produit != null){
        $produit = $produit[0];
      ?>
      <a href="/avocaba/vues/article.php?IdArticle=<?php echo $produit['IdArticle']; ?>"><?php echo $produit['Nom']; ?></a>,
      <?php } ?>
      et bien plus encore...
    </p>
  </div>
  <div class="annonce-accueil__plus">
    <a href="/avocaba/vues/fournisseur.php?siret=<?php echo $fournisseur->getSiret(); ?>">En savoir plus</a>
  </div>

  <script src="/avocaba/js/annonces.js"></script>
</div>
<?php } ?>
