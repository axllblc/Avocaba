<?php

/* 🧑‍🌾 Page d'un fournisseur */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/fournisseur.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/articles.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/misc.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/ville.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/error.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/bouton-favoris.php';



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

$nom = $fournisseur->getNom();
$photoBanniere = $fournisseur->getPhotoBanniere();
$photoProfil = $fournisseur->getPhotoProfil();
$adresse = $fournisseur->getAdresse();
$codePostal = ville($fournisseur->getIdVille())['CodePos'];
$email = $fournisseur->getEmail();
$siteWeb = $fournisseur->getSite();
  
?>

<!DOCTYPE html>
<html lang="fr">
  <?php htmlHead($nom . ' – Avocaba'); ?>

  <body>
    <?php htmlHeader(!empty($_SESSION['Depot'])); ?>

    <main class="fournisseur">
      <!-- Bannière du producteur -->
      <div class="fournisseur__en-tete">
        <div class="fournisseur__banniere"
             style="background-image: url(<?=
               !empty($photoBanniere) ? $photoBanniere : '/avocaba/img/background.jpg'
             ?>);" >
          <!-- logos des médias -->
          
          <div class="fournisseur__banniere-media">
            <?php if (!empty($fournisseur->getFacebook())) { ?>
            <a href="https://www.facebook.com/<?php echo $fournisseur->getFacebook(); ?>" 
               title="Lien vers Facebook - <?php echo $nom; ?>">
              <img src="/avocaba/img/reseaux/facebook.svg" alt="Facebook">
            </a>
            <?php } if (!empty($fournisseur->getTwitter())) { ?>
            <a href="https://twitter.com/<?php echo $fournisseur->getTwitter(); ?>" 
               title="Lien vers Twitter - <?php echo $nom; ?>">
              <img src="/avocaba/img/reseaux/twitter.svg" alt="Twitter">
            </a>
            <?php } if (!empty($fournisseur->getInstagram())) { ?>
            <a href="https://www.instagram.com/<?php echo $fournisseur->getInstagram(); ?>" 
               title="Lien vers Instagram - <?php echo $nom; ?>">
              <img src="/avocaba/img/reseaux/instagram.svg" alt="Instagram">
            </a>
            <?php } ?>
          </div>
        </div>
        <img src="<?= !empty($photoProfil) ? $photoProfil : '/avocaba/img/farmer.png' ?>"
             alt="<?= $nom ?>"
             class="fournisseur__photo-profil">
        <h1 class="fournisseur__nom"><?= $nom; ?></h1>
        <p class="fournisseur__domaine">
          <?php 
          $domaines = $fournisseur->getDomaines();
          for ($i=0; $i < (count($domaines)-1); $i++)
            echo $domaines[$i].', ';
          echo $domaines[count($domaines)-1].'.';
          ?>
        </p>
        
        <div class="fournisseur__favori">
          <?php boutonFavoris('fournisseurs', $fournisseur->getSiret()); ?>
        </div>
      </div>

      <!-- Contenu principal -->
      <div class="fournisseur__principal">
        <div class="fournisseur__colonne-gauche">
          <h2 class="fournisseur__h2-description">Description</h2>
          <?php
          $description = $fournisseur->getDescription();
          if (!empty($description)) {
          ?>
          <p class="fournisseur__description"><?php echo lineBreakChange($description); ?></p>
          <?php } else { ?>
          <p class="fournisseur__description">Ce producteur n'a pas de description</p>
          <?php } ?>
          <?php 
          $photos_fournisseur = $fournisseur->photoFournisseur(); 
          if (!empty($photos_fournisseur)) {
          ?>
          <div class="fournisseur__photos">
            <div class="fournisseur__chevron">
              <span class="material-icons" id="fournisseur__chevron-left">chevron_left</span>
            </div>
            <?php 
            
            foreach ($photos_fournisseur as $key => $value) { ?>
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
          <?php } ?>
        </div>
        <div class="fournisseur__colonne-droite">
          <h2>Informations</h2>
          <address class="fournisseur__infos">
            <div>
              <?= (!empty($adresse) ? $adresse . ', ' : '') . $codePostal . ' ' . $fournisseur->getVille(); ?>
            </div>

            <?php if ( !empty($email) ) { ?>
            <a href="<?= 'mailto:' . $email ?>"
               title="Adresse e-mail du producteur"><?= $email ?></a>
            <?php } ?>

            <?php if ( !empty($siteWeb) ) { ?>
            <a href="<?= $siteWeb ?>"
               title="Site web du producteur"><?= $siteWeb ?></a>
            <?php } ?>
          </address>
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
          <li class="fournisseur__cellule tile">
            <a href="/avocaba/vues/article.php?IdArticle=<?php echo $article['IdArticle'] ?>">
              <img class="fournisseur__photo" 
                  src="<?= !empty($article['PhotoVignette']) ? $article['PhotoVignette'] : '/avocaba/img/article-placeholder.png' ?>" 
                  alt="<?php echo $article['Nom']; ?> - <?php echo $nom; ?>">
              <div class="fournisseur__etiquette">
                <p class="fournisseur__nom-produits-phares"><?php echo $article['Nom']; ?></p>
                <p class="fournisseur__prix-produits-phares"><?php echo $article['Prix']; ?> €</p>
              </div>
            </a>
          </li>
          <?php } ?>
        </ul>
        <a class="fournisseur__voir-autres-produits btn btn--large" 
           href="/avocaba/vues/recherche.php?producteur=<?php echo $fournisseur->getSiret(); ?>" 
           title="voir les autres produits phares de <?php echo $fournisseur->getNom(); ?>">
          Voir mes autres produits
        </a>
      </div>
      <?php } ?>

      <!-- Autres producteurs proches de chez moi -->
      <?php
      if (isset($_SESSION['Depot'])) {
        $producteurs_proches = Fournisseur::fournisseurSurDepot($_SESSION['Depot']['IdDepot']);
        if (count($producteurs_proches) != 0) {
      ?>
      <div class="fournisseur__producteur-proche">
          <h2 class="fournisseur__h2-producteur-proche">Autres producteurs proches de chez moi</h2>
          <ul class="fournisseur__table">
            <?php
            $i = 0;
            while ($i < 4 && isset($producteurs_proches[$i])) {
              // récupérer le fournisseur
              $fournisseur_proche = new Fournisseur($producteurs_proches[$i]);
              $i++;
            ?>
            <li class="fournisseur__cellule tile">
              <a href="/avocaba/vues/fournisseur.php?siret=<?php echo $fournisseur_proche->getSiret(); ?>">
                <img class="fournisseur__photo" 
                    src="<?= !empty($photoProfil) ? $photoProfil : '/avocaba/img/farmer.png' ?>" 
                    alt="producteur proche de chez moi - <?php echo $fournisseur_proche->getNom(); ?>">
                <div class="fournisseur__etiquette">
                  <p class="fournisseur__nom-producteur-proche"><?php echo $fournisseur_proche->getNom(); ?></p>
                  <p class="fournisseur__localisation-producteur-proche"><?php echo $fournisseur_proche->getVille(); ?></p>
                </div>
              </a>
            </li>
            <?php } ?>
          </ul>
      </div>
      <?php } } ?>
    </main>

    <?php footer(); ?>
  </body>
</html>
