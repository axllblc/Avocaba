<?php

/* 🧺 Panier - Cabas */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/panier.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/date.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_qte-article.php';

// Le panier est initialisé. À cette occasion, la session est initialisée.
initialiserPanier();



// *************
// * Fonctions *
// *************

/**
 * Liste de choix pour le jour du retrait de la commande.
 * @return void
 */
function choixJoursRetrait (): void {
  $now = getdate();

  for ($i = 1; $i <= 7; $i++) {
    $date = getdate($now[0] + $i * 86400);

    // Si la date n'est pas un dimanche (jour de fermeture)
    if ( $date['wday'] !== 0 ) {
      $dateVal = $date['year'] . '-' . $date['mon'] . '-' . $date['mday'];
      $dateAff = JOURS[$date['wday']] . ' ' . $date['mday'] . ' ' . MOIS[$date['mon']] . ' ' . $date['year'];

      echo '<option value="' . $dateVal . '">' . $dateAff . '</option>';
    }
  }
}


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
    $qte = $_SESSION['Panier']['Qte'][$key];

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

      <?php htmlQteArticle($id, $qte, $nom); ?>

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

/** Booléen indiquant le succès ou non de la modification de l'état du panier */
$status = false;



// ********************
// * Script principal *
// ********************

// Opérations sur le panier
// Vérifier la présence du paramètre $_GET['actionPanier'] dans la requête et modifier l'état du panier.
$status = actionPanier();


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
  <form class="panier__recapitulatif" action="paiement.php" method="get">

    <h2>Date et heure de retrait</h2>
    <div id="panier__creneau">
      <select class="panier__creneau_jour" name="choix_jour" title="Date de retrait">
        <?php choixJoursRetrait(); ?>
      </select>
      <select class="panier_creneau_heure" name="choix_jour" title="Heure de retrait (plage de deux heures)">
        <option value="8">8h-10h</option>
        <option value="10">10h-12h</option>
        <option value="12">12h-14h</option>
        <option value="16">16h-18h</option>
        <option value="18">18h-20h</option>
      </select>
    </div>

    <h2>Récapitulatif</h2>
    <div id="panier__montant-total">Total&nbsp;: <b><?= montantPanier() . '&nbsp;€' ?></b></div>
    <div><?= $affichageNbArticles?></div>
    <!-- -->
    <a class="panier__valider_panier" href="paiement.php">Valider mon cabas</a><br>
    <a href="<?= '?actionPanier=' . VIDER_PANIER ?>">Vider mon cabas</a>

  </form>
  <?php } ?>

</main>

<?php footer(); ?>

</body>
</html>

