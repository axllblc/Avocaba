<?php

/* 🧺 Panier - Cabas */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/panier.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';

// Le panier est initialisé. À cette occasion, la session est initialisée.
initialiserPanier();



// **************
// * Constantes *
// **************

// Actions

const DIMINUER     = 'dec';
const AUGMENTER    = 'inc';
const MODIFIER     = 'set';
const RETIRER      = 'rem';
const VIDER_PANIER = 'emp';



// *************
// * Fonctions *
// *************

/**
 * Afficher le contenu du panier.
 * @return void
 */
function affichagePanier (): void {
  echo '<h2>' . $GLOBALS['affichageNbArticles'] . '</h2>';
  echo '<div class="panier__ls-articles">';

  foreach ($_SESSION['Panier']['IdArticle'] as $key => $id) {
    $nom   = $_SESSION['Panier']['Nom'][$key];
    $unite = $_SESSION['Panier']['Unite'][$key];
    $prix  = $_SESSION['Panier']['Prix'][$key];
    $photoVignette = !empty($_SESSION['Panier']['PhotoVignette'][$key]) ?
                     $_SESSION['Panier']['PhotoVignette'][$key] : '/avocaba/img/article-placeholder.png';

    ?>
    <article class="panier__article">

      <img class="panier__img-article"
           src="<?= $photoVignette ?>"
           alt="<?= $nom ?>">
      <h3 class="panier__nom-article">
        <?= $nom . ' (' . $unite . ')' ?>
      </h3>
      <div class="panier__prix-article">
        <?= $prix . '&nbsp;€' ?>
      </div>

      <div class="panier__selection-qte">
        <input type="hidden" name="idArticle" value="<?= $id ?>">
        <a href="<?= '?action=' . DIMINUER . '&id=' . $id ?>"
           title="Diminuer la quantité"
           aria-label="<?= 'Diminuer la quantité de ' . $nom ?>">
          -
        </a>
        <input type="number" name="qte" class="panier__input-qte" data-id="<?= $id ?>"
               min="0" max="5" value="<?= $_SESSION['Panier']['Qte'][$key] ?>"
               title="Définir la quantité"
               aria-label="<?= 'Modifier la quantité de ' . $nom ?>">
        <a href="<?= '?action=' . AUGMENTER . '&id=' . $id ?>"
           title="Augmenter la quantité"
           aria-label="<?= 'Augmenter la quantité de ' . $nom ?>">
          +
        </a>
        <a href="<?= '?action=' . RETIRER . '&id=' . $id ?>"
           title="Retirer l'article du panier"
           aria-label="<?= 'Retirer ' . $nom . ' du cabas' ?>">
          Retirer
        </a>
      </div>

    </article>
  <?php }

  echo '</div>';
}
// Fin de la fonction affichagePanier



// *************
// * Variables *
// *************

/** Nombre d'articles dans le panier. */
$nbArticles = nbArticles();

/** Chaîne de caractères pour l'affichage du nombre d'articles dans le panier. */
$affichageNbArticles =
  ( $nbArticles > 0 ) ?
    ( $nbArticles === 1 ? 'Un article' : "$nbArticles articles" ) : '';

$status   = false;



// ********************
// * Script principal *
// ********************

// Opérations sur le panier

if ( !empty($_GET['action']) ) {
  switch ($_GET['action']) {
    case DIMINUER:
      if ( !empty($_GET['id']) && is_numeric($_GET['id']) )
        $status = diminuerQteArticle($_GET['id']);
      break;
    case AUGMENTER:
      if ( !empty($_GET['id']) && is_numeric($_GET['id']) )
        $status = ajouterArticle($_GET['id']);
      break;
    case MODIFIER:
      if (    !empty($_GET['id'])    && is_numeric($_GET['id'])
           && !empty($_GET['value']) && is_numeric($_GET['value']) )
        $status = modifierQteArticle($_GET['id'], $_GET['value']);
      break;
    case RETIRER:
      if ( !empty($_GET['id']) && is_numeric($_GET['id']) )
        $status = supprimerArticle($_GET['id']);
      break;
    case VIDER_PANIER:
      supprimerPanier();
      $status = true;
      break;
  }

  /*
   * Si la modification de l'état du panier a fonctionné, l'utilisateur est redirigé vers la même page, afin de
   * supprimer les paramètres passés dans l'URL.
   * Cela permet d'éviter des modifications répétées de l'état du panier si l'utilisateur navigue avec les boutons
   * [Précédent] et [Suivant] du navigateur.
   */
  if ($status) {
    header('Location: panier.php');
    exit;
  }
} else $status = true;


// Un test
//ajouterArticle(1);
//ajouterArticle(2);
//ajouterArticle(454);
//modifierQteArticle(1, 5);
//var_dump($_SESSION);

?>

<!DOCTYPE html>
<html lang="fr">

<?php htmlHead('Mon cabas – Avocaba'); ?>

<body>

<?php htmlHeader(true); ?>

<nav>
  <button class="nav__btn-retour" onclick="history.back();" title="Revenir à la page précédente">Retour</button>
  <div class="nav__sep"><!--Séparateur--></div>
  <span>Mon cabas</span>
  <div class="nav__sep"><!--Séparateur--></div>
  <a href="magasin.php"><?= $_SESSION['Depot']['Nom'] ?></a>
</nav>

<main class="panier">

  <div class="panier__section_articles">
    <h1 class="panier__titre">Mon cabas</h1>
    <?= !$status ? '⚠️ Impossible de modifier le contenu du panier.' : '' ?>
    <?php
      if ($nbArticles > 0)
        affichagePanier();
      else
        echo '<p>Votre cabas est vide. <a href="/avocaba/vues/magasin.php">Ajoutez-y des articles</a> pour commencer.</p>';
    ?>
  </div>

  <?php if ($nbArticles > 0) { ?>
  <div class="panier__recapitulatif">
    <h2>Date et heure de retrait</h2>
    <!-- TODO : Rendre le sélecteur de créneau dynamique -->
    <button type="button" name="recapitulatif__bouton_creneau">Choisir un créneau</button>
    <div id="panier__creneau">
      <!--Voir maquette pour respecter le style du choix du créneau!-->
      <select class="panier__creneau_jour" name="choix_jour" size="3">
        <option value="lundi 12">lundi 12</option>
        <option value="mardi 13">mardi 13</option>
        <option value="mercredi 14">mercredi 14</option>
        <option value="jeudi 14">jeudi 14</option>
      </select>
      <select class="panier_creneau_heure" name="choix_jour" size="3">
        <option value="8">8h-10h</option>
        <option value="10">10h-12h</option>
        <option value="12">12h-14h</option>
        <option value="16">16h-18h</option>
        <option value="18">18h-20h</option>
      </select>
    </div>
    <!-- -->
    <h2>Récapitulatif</h2>
    <div id="panier__montant-total">Total&nbsp;: <b><?= montantPanier() . '&nbsp;€' ?></b></div>
    <div><?= $affichageNbArticles?></div>
    <div>Date et heure de retrait : <b>Lundi 12 juin entre 8h et 10h</b></div>
    <!-- -->
    <a class="panier__valider_panier" href="/avocaba/vues/paiement.php">Valider mon cabas</a><br>
    <a href="<?= '?action=' . VIDER_PANIER ?>">Vider mon cabas</a>
  </div>
  <?php } ?>

</main>

<?php footer(); ?>

</body>
</html>

