<?php

/* 🧑‍🌾 Page d'un fournisseur */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/fournisseur.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/articles.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/misc.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/error.php';

session_start();



// ********************
// * Script principal *
// ********************

// Récupération du siret du fournisseur en GET

if (isset($_GET['siret'])) {
  try {
    $fournisseur = new Fournisseur($_GET['siret']);
  } catch (Exception $e) {
    error(404);
    exit;
  }
} else {
  error(404);
  exit;
}
  
?>

<!DOCTYPE html>
<html lang="fr">
  <?php htmlHead($fournisseur->getNom() . ' – Avocaba'); ?>

  <body>
    <?php htmlHeader( !empty($_SESSION['Depot']) ); ?>

    <main class="fournisseur">
      <!-- Bannière du producteur -->
      <div class="fournisseur__en-tete">
        <div class="fournisseur__banniere"
             style="background-image: url('<?php echo $fournisseur->getPhotoBanniere(); ?>');">
          <!-- logos des médias -->
          <div class="fournisseur__banniere-media">
            Logo des médias
          </div>
        </div>
        <div class="fournisseur__photo-profil" style="background-image: url('<?php echo $fournisseur->getPhotoProfil(); ?>');"></div>
        <h1 class="fournisseur__nom"><?php echo $fournisseur->getNom(); ?></h1>
        <p class="fournisseur__domaine">
          <?php 
          $domaines = $fournisseur->getDomaines();
          for ($i=0; $i < (count($domaines)-1); $i++)
            echo $domaines[$i].', ';
          echo $domaines[count($domaines)-1].'.';
          ?>
        </p>
        
        <div class="fournisseur__favori">
          &#9733;
        </div>
      </div>

      <!-- Contenu principal -->
      <div class="fournisseur__principal">
        <div class="fournisseur__colonne-gauche">
          <h2 class="fournisseur__h2-description">Description</h2>
          <p class="fournisseur__description"><?php echo lineBreakChange($fournisseur->getDescription()); ?></p>
          <div class="fournisseur__photos">
            <div class="fournisseur__chevron">
              <span class="material-icons" id="fournisseur__chevron-left">chevron_left</span>
            </div>
            <?php foreach ($fournisseur->photoFournisseur() as $key => $value) { ?>
              <img class="fournisseur__photo-slider"  
                  src="<?php echo $value ?>" 
                  alt="photo <?php echo $key ?>"
                  id="photo-<?php echo $key ?>"
                  style="display: <?php 
                                    if ($key == 0)
                                      echo 'block';
                                    else
                                      echo 'none';
                                  ?>;">
            <?php } ?>
            <div class="fournisseur__chevron">
              <span class="material-icons" id="fournisseur__chevron-right">chevron_right</span>
            </div>
            <script>  
              const chevron_left = document.getElementById("fournisseur__chevron-left");
              const chevron_right = document.getElementById("fournisseur__chevron-right");

              let photos = [<?php for ($i=0; $i < count($fournisseur->photoFournisseur()); $i++) { ?>
                document.getElementById("photo-<?php echo $i ?>"),
              <?php } ?>];

              let index = 0;

              chevron_right.addEventListener("click", () => {
                photos[index].style.display = "none";
                  index+=1;
                  if(index === photos.length) {
                      index=0;
                      photos[index].style.display = "block";
                  } else {
                    photos[index].style.display = "block";
                  }
              })

              chevron_left.addEventListener("click", () => {
                photos[index].style.display = "none";
                  index-=1;
                  if(index === -1) {
                      index=photos.length-1;
                      photos[index].style.display = "block";
                  } else {
                    photos[index].style.display = "block";
                  }
              })
            </script>
          </div>
        </div>
        <div class="fournisseur__colonne-droite">
          <h2>Information</h2>
          <p><?php echo $fournisseur->getAdresse(); ?></p>
          <p><?php echo $fournisseur->getVille(); ?></p>
          <p><?php echo $fournisseur->getEmail(); ?></p>
          <a href="#"
             title="site web du producteur"><?php echo $fournisseur->getSite(); ?></a>
          <!-- Carte temporaire -->
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6222.185358365255!2d2.141973084391871!3d47.928802557131064!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6de6a4e5b93cc26f!2sBoulangerie%20GM!5e0!3m2!1sfr!2sfr!4v1646519078967!5m2!1sfr!2sfr" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>

      <!-- Produits phares -->
      <?php
      $articles_phares = $fournisseur->produitsPhares();
      if (count($articles_phares) != 0) {
      ?>
      <div class="fournisseur__produits-phares">
        <h2 class="fournisseur__h2-produits-phares">Mes produits phares</h2>
        <ul class="fournisseur__table">
          <?php
          $i = 0;
          while ($i < 4 && isset($articles_phares[$i])) {
            // récupérer article
            $article = rechercherArticle($articles_phares[$i], 'idArticle')[0];
            $i++;
          ?>
          <li class="fournisseur__cellule">
            <img class="fournisseur__photo" src="<?php echo $article['PhotoVignette']; ?>" alt="produit phare <?php echo $i; ?>">
            <div class="fournisseur__etiquette">
              <p class="fournisseur__nom-produits-phares"><?php echo $article['Nom']; ?></p>
              <p class="fournisseur__prix-produits-phares"><?php echo $article['Prix']; ?> €</p>
            </div>
          </li>
          <?php } ?>
        </ul>
        <a class="fournisseur__voir-autres-produits" href="#" title="voir les autres produits phares du producteur">
          Voir mes autres produits
        </a>
      </div>
      <?php } ?>

      <!-- Autres producteurs proches de chez moi -->
      <?php
      $producteurs_proches = $fournisseur->producteursProches();
      if (count($producteurs_proches) != 0) {
      ?>
      <div class="fournisseur__producteur-proche">
          <h2 class="fournisseur__h2-producteur-proche">Autres producteurs proches de chez moi</h2>
          <ul class="fournisseur__table">
            <?php
            $i = 0;
            while ($i < 4 && isset($producteurs_proches[$i])) {
              // récupérer article
              $fournisseur_proche = $fournisseur->producteursProches();
              $i++;
            ?>
            <li class="fournisseur__cellule">
              <img class="fournisseur__photo" src="<?php echo $fournisseur_proche->getPhotoProfil(); ?>" alt="producteur proche de chez moi <?php echo $i; ?>">
              <div class="fournisseur__etiquette">
                <p class="fournisseur__nom-producteur-proche"><?php echo $fournisseur_proche->getNom(); ?></p>
                <p class="fournisseur__localisation-producteur-proche"><?php echo $fournisseur_proche->getVille(); ?></p>
              </div>
            </li>
            <?php } ?>
          </ul>
      </div>
      <?php } ?>
    </main>

    <?php footer(); ?>
  </body>
</html>
