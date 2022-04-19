<?php

/* 🏠 Accueil du site (landing page) */

error_reporting(E_ALL);

require_once '../traitements/recherche-magasin.php';
require_once '../composants/html_head.php';
require_once '../composants/footer.php';

/*************
 * Fonctions *
 *************/

function afficherMagasins ($magasins) {
  echo '<div class="resultats-magasins">';
  if (!empty($magasins)) {
    foreach ($magasins as $magasin)
      echo '
      <div class="resultats-magasins__item">
        <a href="/avocaba/vues/magasin?id='.$magasin['IdDepot'].'">
          <div class="resultats-magasins__nom">'.$magasin['Nom'].'</div>
          <div class="resultats-magasins__adresse">'.$magasin['Adresse'].', '.$magasin['CodePostal'].' '.$magasin['Ville'].'</div>
        </a>
      </div>
      ';
  } else
    echo '<div class="resultats-magasins__vide">Aucun résultat</div>';
  echo '</div>';
}



/********************
 * Script principal *
 ********************/

// Recherche d'un magasin : Réception de données

if ( !empty($_GET['recherche']) ) {
  $magasins = rechercherMagasin($_GET['recherche']);
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php htmlHead('Avocaba : votre marché 100% local'); ?>

<body>

<div class="landing">
  <header class="landing__header">
    <div class="landing__header__logo">Logo Avocaba</div>
    <a class="landing__header__connexion landing__btn landing__btn--white" href="/avocaba/vues/espace-client/account.php">Se connecter</a>
  </header>

  <div class="landing__haut">

    <h1 class="landing__titre">Avocaba</h1>
    <p class="landing__sous-titre">Votre marché 100% local</p>

    <p>Où souhaitez-vous retirer vos courses ?</p>

    <form class="recherche recherche-magasin" action="/avocaba" method="get">
      <input class="recherche__input"
             type="search" name="recherche" id="recherche"
             placeholder="Ville, code postal ou numéro de département"
             value="<?php echo $_GET['recherche'] ?? '' ?>"
             required maxlength="50">

      <!-- Label permettant de "fabriquer" un bouton de recherche personnalisé -->
      <label class="recherche__btn">
        <input type="submit" value="Rechercher">
        <span class="material-icons">search</span>
      </label>
    </form>


    <?php
    // Affichage des résultats de recherche
    if (isset($magasins))
      afficherMagasins($magasins);
    ?>

  </div>

  <div class="landing__milieu">
    <h2>Commandez vos produits régionaux, où vous voulez, quand vous voulez !</h2>
    <p>Avocaba est le premier <em>click-and-collect</em> en circuit-court. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores, quae architecto? Modi sed inventore magni esse, at dignissimos consectetur quasi perspiciatis atque dolore omnis. Cumque officia delectus nisi nam. Perspiciatis!</p>
    <a href="/avocaba/vues/a-propos" class="landing__btn">En savoir plus</a>

    <h2>Comment ça marche ?</h2>
    <div class="landing__fonctionnement">Infographie</div>
    <a href="/avocaba/vues/a-propos" class="landing__btn">En savoir plus</a>

    <h2>Pourquoi Avocaba ?</h2>
    <div class="landing__avantages">Infographie</div>
    <a href="/avocaba/vues/a-propos/engagements" class="landing__btn">Découvrez nos engagements</a>
  </div>

  <div class="landing__bas">
    <p>Avocaba, votre marché 100% local</p>
    <a href="#" class="landing__btn landing__btn--white">Haut de page</a>
  </div>
</div>

<?php footer(); ?>

</body>

</html>
