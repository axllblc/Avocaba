<?php

/* ⬆️ En-tête des pages du magasin */

/**
 * Afficher l'en-tête des pages du magasin.
 * @param bool $magasin Booléen indiquant si un clic sur le logo du site redirige vers l'accueil du magasin ou du site.
 *                      Vaut true pour une redirection vers l'accueil du magasin ; false pour une redirection vers
 *                      l'accueil du site (landing page).
 * @param string $query Contenu du champ de recherche
 * @return void
 */
function htmlHeader (bool $magasin = false, string $query = ''): void { ?>
  <header class="header-magasin">
    <a class="header__logo"
       href="<?= $magasin ? '/avocaba/vues/magasin.php' : '/avocaba' ?>">
      <img src="/avocaba/img/avocaba.svg" alt="Avocaba" title="Avocaba">
    </a>

    <a class="header__btn header__rayons"
       href="<?= $magasin ? '/avocaba/vues/magasin.php?lr' : '/avocaba' ?>">
      <span class="header__btn-ic material-icons">menu</span>
      <div class="header__btn-text">Rayons</div>
    </a>

    <form class="header__recherche recherche" action="/avocaba/vues/recherche.php" method="get">
      <input class="recherche__input" id="recherche"
             type="search" name="recherche"
             value="<?= $query ?>"
             placeholder="Rechercher un produit ou un producteur"
             required>
      <!-- Label permettant de "fabriquer" un bouton de recherche personnalisé -->
      <label class="recherche__btn">
        <input type="submit" value="Rechercher">
        <span class="material-icons">search</span>
      </label>
    </form>

    <a class="header__btn header__mes-produits" href="/avocaba/vues/mes-produits.php">
      <span class="header__btn-ic material-icons">summarize</span>
      <div class="header__btn-text">Mes produits</div>
    </a>
    <a class="header__btn header__mon-compte" href="/avocaba/vues/espace-client/account.php">
      <span class="header__btn-ic material-icons">account_circle</span>
      <div class="header__btn-text">Mon compte</div>
    </a>
    <a class="header__btn header__mon-cabas" href="/avocaba/vues/panier.php">
      <span class="header__btn-ic material-icons">shopping_basket</span>
      <div class="header__btn-text">Mon cabas</div>
    </a>
  </header>
<?php }
