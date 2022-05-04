<?php
/**
 * 
 */
function annonceAccueil() : void {?>
<div class="annonce-accueil">
  <div class="annonce-accueil__slideshow-container">
    <div class="annonce-accueil__mySlides annonce-accueil__fade">
      <img src="https://imgs.search.brave.com/ajdNI4vI1cNXIH4eHQiV_0gT7Lh44H_J9qugc5dkFWA/rs:fit:1200:1200:1/g:ce/aHR0cDovL2xlc3ll/dXhncm9nbm9ucy5j/b20vd3AtY29udGVu/dC91cGxvYWRzLzIw/MTgvMDgvMzQxM0NF/MjMtNThEMC00QTkx/LUI0OTktNzI3QUVD/NDU4QjJFLmpwZWc" >
    </div>
    
    <div class="annonce-accueil__mySlides annonce-accueil__fade">
      <img src="https://imgs.search.brave.com/Pl-jMTaTHsP676_-F0BCC5WdgeEH4cr31I1Xe33H6t8/rs:fit:1200:800:1/g:ce/aHR0cHM6Ly91bmVs/aW1vbmFkZWF0b21i/b3VjdG91LmZyL3dw/LWNvbnRlbnQvdXBs/b2Fkcy8yMDIxLzAx/L3BhcmlzLWJyZXN0/LXByYWxpbm9pcy5q/cGc">
    </div>
    
    <div class="annonce-accueil__mySlides annonce-accueil__fade">
      <img src="https://imgs.search.brave.com/qfE2EMCw2tpUJQgrHnvLqyQ9tqEvkrM6Plkcfnmmeig/rs:fit:452:584:1/g:ce/aHR0cHM6Ly9ib3V0/aXF1ZS1tdXNxdWFy/LmNvbS83Mi1tZWRp/dW1fZGVmYXVsdC9w/YXJpcy1icmVzdC5q/cGc">
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
<?php}?>