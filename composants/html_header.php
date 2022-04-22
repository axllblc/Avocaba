<?php

/* ⬆️ En-tête des pages du magasin */

// TODO modifier les liens

/**
 * Afficher l'en-tête des pages du magasin.
 * @param int|null $id Identifiant du magasin
 * @param string $query Contenu du champ de recherche
 * @return void
 */
function htmlHeader (int $id = NULL, string $query = ''): void { ?>
  <header class="header-magasin">
    <a class="header__logo"
       href="<?php echo isset($id) ? '/avocaba/vues/magasin.php?id='.$id : '/avocaba' ?>">
      Avocaba
    </a>

    <div class="header__btn header__rayons">
      <span class="header__btn-ic material-icons">menu</span>
      <div class="header__btn-text">Rayons</div>
    </div>

    <form class="header__recherche recherche" action="/magasin/recherche" method="get">
      <input class="recherche__input" id="recherche"
             type="search" name="recherche"
             value="<?php echo $query ?>"
             placeholder="Rechercher un produit ou un producteur"
             required>
      <!-- Label permettant de "fabriquer" un bouton de recherche personnalisé -->
      <label class="recherche__btn">
        <input type="submit" value="Rechercher">
        <span class="material-icons">search</span>
      </label>
    </form>

    <a class="header__btn header__mes-produits" href="/mes-produits">
      <span class="header__btn-ic material-icons">summarize</span>
      <div class="header__btn-text">Mes produits</div>
    </a>
    <a class="header__btn header__mon-compte" href="/espace-client/espace-client.html">
      <span class="header__btn-ic material-icons">account_circle</span>
      <div class="header__btn-text">Mon compte</div>
    </a>
    <a class="header__btn header__mon-cabas" href="/panier">
      <span class="header__btn-ic material-icons">shopping_basket</span>
      <div class="header__btn-text">Mon cabas</div>
    </a>
  </header>
<?php }