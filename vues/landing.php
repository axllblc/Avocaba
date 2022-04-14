<?php

/* 🏠 Accueil du site (landing page) */

error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/avocaba/stylesheets/layout.css">
  <link rel="stylesheet" href="/avocaba/stylesheets/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <title>Avocaba : votre marché 100% local</title>
</head>

<body class="landing">

  <header class="landing__header">
    <div class="landing__header__logo">Logo Avocaba</div>
    <a class="landing__header__connexion landing__btn landing__btn--white" href="/avocaba/vues/espace-client/connexion/">Se connecter</a>
  </header>

  <div class="landing__haut">

    <h1 class="landing__titre">Avocaba</h1>
    <p class="landing__sous-titre">Votre marché 100% local</p>

    <p>Où souhaitez-vous retirer vos courses ?</p>
    
    <form class="recherche recherche-magasin" action="/avocaba/" method="get">
      <input class="recherche__input" type="search" name="recherche" id="recherche" placeholder="Ville, code postal ou département" required>
      <!-- Label permettant de "fabriquer" un bouton de recherche personnalisé -->
      <label class="recherche__btn">
        <input type="submit" value="Rechercher">
        <span class="material-icons">search</span>
      </label>
    </form>

    <div class="resultats-magasin">
      <div class="resultats-magasin__item">
        <a href="/avocaba/vues/magasin?id=1">
          <div class="resultats-magasin__nom">Avocaba Tours</div>
          <div class="resultats-magasin__adresse">12, Avenue Monge, 37200 Tours</div>
        </a>
      </div>
      <div class="resultats-magasin__item">
        <a href="/avocaba/vues/magasin?id=2">
          <div class="resultats-magasin__nom">Avocaba Fay-aux-Loges</div>
          <div class="resultats-magasin__adresse">316, Rue Aristide Briand, 45450 Fay-aux-Loges</div>
        </a>
      </div>
    </div>
    
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

  <footer>
    Pied de page
  </footer>

</body>

</html>