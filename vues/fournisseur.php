<?php
/* 🧑‍🌾 Page d'un fournisseur */
error_reporting(E_ALL);

require_once '../traitements/fournisseur.inc.php';
require_once '../traitements/misc.inc.php';
require_once '../composants/html_head.php';
require_once '../composants/html_header.php';
require_once '../composants/footer.php';


/*************
 * Fonctions *
 *************/

/**
* Afficher la page d'erreur 404. Cette fonction met fin à l'exécution du script.
* @return void
*/
function erreur404() : void {
  http_response_code(404);
  include '404.php';
  exit;
}


/********************
 * Script principal *
 ********************/

// récupération du siret du fournisseur en GET
if (isset($_GET['siret'])) {
  try {
    $fournisseur = new Fournisseur($_GET['siret']);
  } catch (Exception $e) {
    erreur404();
  }
} else {
  erreur404();
}
  
?>

<!DOCTYPE html>
<html lang="fr">
  <?php htmlHead($fournisseur->getNom() . ' – Avocaba'); ?>

  <body>
    <?php htmlHeader(); // TODO ?> 

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
        <p class="fournisseur__domaine">[Domaine]</p> <!-- TODO à dégager -->
        
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

              var index = 0;

              chevron_right.addEventListener("click", (e) => {
                photos[index].style.display = "none";
                  index+=1;
                  if(index == photos.length) {
                      index=0;
                      photos[index].style.display = "block";
                  } else {
                    photos[index].style.display = "block";
                  }
              })

              chevron_left.addEventListener("click", (e) => {
                photos[index].style.display = "none";
                  index-=1;
                  if(index == -1) {
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
          <p>[ville]</p>
          <p><?php echo $fournisseur->getEmail(); ?></p>
          <a href="#"
             title="site web du producteur"><?php echo $fournisseur->getSite(); ?></a>
          <!-- Carte temporaire -->
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6222.185358365255!2d2.141973084391871!3d47.928802557131064!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6de6a4e5b93cc26f!2sBoulangerie%20GM!5e0!3m2!1sfr!2sfr!4v1646519078967!5m2!1sfr!2sfr" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>

      <!-- Producteurs phares -->
      <div class="fournisseur__produits-phares">
        <h2 class="fournisseur__h2-produits-phares">Mes produits phares</h2>
        <ul class="fournisseur__table">
          <li class="fournisseur__cellule">
            <img class="fournisseur__photo" src="/img/placeholder.svg" alt="produit phare 1">
            <div class="fournisseur__etiquette">
              <p class="fournisseur__nom-produits-phares">[Produit 1]</p>
              <p class="fournisseur__prix-produits-phares">[Prix]</p>
            </div>
          </li>
          <li class="fournisseur__cellule">
            <img class="fournisseur__photo" src="/img/placeholder.svg" alt="produit phare 2">
            <div class="fournisseur__etiquette">
              <p class="fournisseur__nom-produits-phares">[Produit 2]</p>
              <p class="fournisseur__prix-produits-phares">[Prix]</p>
            </div>
          </li>
          <li class="fournisseur__cellule">
            <img class="fournisseur__photo" src="/img/placeholder.svg" alt="produit phare 3">
            <div class="fournisseur__etiquette">
              <p class="fournisseur__nom-produits-phares">[Produit 3]</p>
              <p class="fournisseur__prix-produits-phares">[Prix]</p>
            </div>
          </li>
          <li class="fournisseur__cellule">
            <img class="fournisseur__photo" src="/img/placeholder.svg" alt="produit phare 4">
            <div class="fournisseur__etiquette">
              <p class="fournisseur__nom-produits-phares">[Produit 4]</p>
              <p class="fournisseur__prix-produits-phares">[Prix]</p>
            </div>
          </li>
        </ul>
        <a class="fournisseur__voir-autres-produits" href="#" title="voir les autres produits phares du producteur">
          Voir mes autres produits
        </a>
      </div>

      <!-- Autres producteurs proches de chez moi -->
      <div class="fournisseur__producteur-proche">
        <h2 class="fournisseur__h2-producteur-proche">Autres producteurs proches de chez moi</h2>
        <ul class="fournisseur__table">
          <li class="fournisseur__cellule">
            <img class="fournisseur__photo" src="/img/placeholder.svg" alt="producteur proche de chez moi 1">
            <div class="fournisseur__etiquette">
              <p class="fournisseur__nom-producteur-proche">[Producteur 1]</p>
              <p class="fournisseur__localisation-producteur-proche">[Localisation]</p>
            </div>
          </li>
          <li class="fournisseur__cellule">
            <img class="fournisseur__photo" src="/img/placeholder.svg" alt="producteur proche de chez moi 2">
            <div class="fournisseur__etiquette">
              <p class="fournisseur__nom-producteur-proche">[Producteur 2]</p>
              <p class="fournisseur__localisation-producteur-proches">[Localisation]</p>
            </div>
          </li>
          <li class="fournisseur__cellule">
            <img class="fournisseur__photo" src="/img/placeholder.svg" alt="producteur proche de chez moi 3">
            <div class="fournisseur__etiquette">
              <p class="fournisseur__nom-producteur-proche">[Producteur 3]</p>
              <p class="fournisseur__localisation-producteur-proche">[Localisation]</p>
            </div>
          </li>
          <li class="fournisseur__cellule">
            <img class="fournisseur__photo" src="/img/placeholder.svg" alt="producteur proche de chez moi 4">
            <div class="fournisseur__etiquette">
              <p class="fournisseur__nom-producteur-proche">[Producteur 4]</p>
              <p class="fournisseur__localisation-producteur-proche">[Localisation]</p>
            </div>
          </li>
        </ul>
      </div>

    </main>

    <?php footer(); ?>
  </body>
</html>
