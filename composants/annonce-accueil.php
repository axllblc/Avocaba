<?php

/* 📣 Annonces sur la page d'accueil */

// TODO : Rendre le composant dynamique

/**
 * Composant annonce sur la page d'accueil
 * @param string|int $depot Identifiant du dépôt actif du client
 */
function annonceAccueil (string|int $depot) : void { ?>
<div class="annonce-accueil">
  <div class="annonce-accueil__slideshow-container">
    <div class="annonce-accueil__mySlides annonce-accueil__fade">
      <?php // TODO : Ajouter un texte alternatif pour les images ?>
      <img src="https://imgs.search.brave.com/ajdNI4vI1cNXIH4eHQiV_0gT7Lh44H_J9qugc5dkFWA/rs:fit:1200:1200:1/g:ce/aHR0cDovL2xlc3ll/dXhncm9nbm9ucy5j/b20vd3AtY29udGVu/dC91cGxvYWRzLzIw/MTgvMDgvMzQxM0NF/MjMtNThEMC00QTkx/LUI0OTktNzI3QUVD/NDU4QjJFLmpwZWc" >
    </div>
  </div>

  <script>
    let slideIndex = 0;
    showSlides();
    
    function showSlides() {
      let i;
      let slides = document.getElementsByClassName("annonce-accueil__mySlides");
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
      }
      slideIndex++;
      if (slideIndex > slides.length) {slideIndex = 1}    
      
      slides[slideIndex-1].style.display = "block";  
      setTimeout(showSlides, 4000);
    }
  </script>
  
  <div class="annonce-accueil__droite">
    <h2 class="annonce-accueil__decouvrir">Venez découvrir [nom du producteur]</h2>
    <p class="annonce-accueil__produits-phares">
      <a href="#">[produit 1]</a>, 
      <a href="#">[produit 2]</a>, 
      <a href="#">[produit 3]</a>, 
      <a href="#">[produit 4]</a>, 
      <a href="#">[produit 5]</a>, 
      <a href="#">[produit 6]</a>...
    </p>
  </div>
  <div class="annonce-accueil__savoir">
    <div>
      <a href="#">En savoir plus</a>
    </div>
  </div>
</div>
<?php } ?>