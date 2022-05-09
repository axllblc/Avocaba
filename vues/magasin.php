<?php

/* 🏠 Accueil du magasin */

error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/magasin.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/error.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/annonce-accueil.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_liste-rayons.php';

session_start();



// **************
// * Constantes *
// **************

/** Message affiché en cas de magasin inexistant */
const ERR_DEPOT_INEXISTANT =
  'Le magasin auquel vous tentez d\'accéder n\'existe pas... Retournez à l\'accueil pour retrouver votre chemin !';



// ********************
// * Script principal *
// ********************

/*
 * Si un identifiant de dépôt est passé en paramètre, les informations sur le dépôt correspondant sont enregistrées dans
 * la session (sauf si aucun dépôt ne correspond ; dans ce cas, une erreur 404 est affichée).
 *
 * Si l'identifiant de dépôt passé en paramètre est invalide, c'est-à-dire si ce n'est pas une valeur numérique, alors
 * une erreur 400 (Bad Request) est affichée.
 *
 * Si aucun identifiant n'est passé en paramètre et qu'un dépôt est enregistré dans la session, alors l'accueil du
 * magasin correspondant est affiché.
 *
 * Si aucun identifiant n'est passé en paramètre et qu'il n'y a pas de dépôt enregistré dans la session, l'utilisateur
 * est redirigé vers l'accueil du site (landing page).
 *
 * (voir `magasin.inc.php` pour l'enregistrement des informations sur le dépôt dans la session)
 */

// Si un identifiant de dépôt est passé en paramètre...
if ( isset($_GET['id']) ) {
  $depotCourant = $_SESSION['Depot']['IdDepot'] ?? 0;

  // Si cet identifiant n'est pas une valeur numérique, une erreur 400 est affichée.
  if ( !is_numeric($_GET['id']) )      error(400);

  // Les informations du dépôt correspondant sont enregistrées dans la session.
  // En cas d'échec, une erreur 404 est affichée.
  if ( !selectionDepot($_GET['id']) )  error(404, ERR_DEPOT_INEXISTANT);

  // Si l'utilisateur change de dépôt, le panier est vidé pour éviter de commander des produits indisponibles
  if ( isset($_SESSION['Panier']) && intval($_GET['id']) !== $depotCourant )
    unset($_SESSION['Panier']);

  // Si l'utilisateur est connecté, le dépôt qu'il a sélectionné est enregistré en tant que dernier dépôt fréquenté
  if ( isset($_SESSION['Client']) )
    enregistrerDernierDepot($_SESSION['Client']['IdClient'], $_GET['id']);
}

// Si aucun dépôt n'est enregistré dans la session, l'utilisateur est redirigé vers l'accueil
if ( !isset($_SESSION['Depot']) ) {
  header('Location: /avocaba');
  exit;
}

?>

<!DOCTYPE html>

<html lang="fr">

<?php htmlHead($_SESSION['Depot']['Nom'] . ' – Avocaba'); ?>

<body>
<?php htmlHeader($_SESSION['Depot']['IdDepot']); ?>

<div class="magasin">
  <div class="magasin__bienvenue">Bienvenue dans votre magasin</div>
  <h1 class="magasin__nom"><?= $_SESSION['Depot']['Nom'] ?></h1>
  <address class="magasin__adresse">
    <?php echo $_SESSION['Depot']['Adresse'] . ', ' . $_SESSION['Depot']['CodePostal'] . ' ' . $_SESSION['Depot']['Ville'] ?>
  </address>
  <a class="magasin__switch btn btn--white" href="/avocaba" title="Sélectionner un autre magasin">Changer de magasin</a>
</div>

<main>
  <?php if ( !isset($_GET['lr']) ) annonceAccueil($_SESSION['Depot']['IdDepot']); ?>

  <div class="rayons">
    <h2>Rayons</h2>
    <?php htmlListeRayons($_SESSION['Depot']['IdDepot']); ?>
  </div>
</main>

<?php footer(); ?>
</body>
</html>