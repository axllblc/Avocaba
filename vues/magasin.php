<?php

/* 🏠 Accueil du magasin */

error_reporting(E_ALL);

require_once '../composants/html_head.php';
require_once '../composants/html_header.php';
require_once '../composants/footer.php';
require_once '../composants/html_liste-rayons.php';
require_once '../composants/annonce-accueil.php';
require_once '../composants/error.php';
require_once '../traitements/magasin.inc.php';



/********************
 * Script principal *
 ********************/

/*
 * Si un identifiant de magasin est passé en paramètre, celui-ci est enregistré dans la session et l'accueil du magasin
 * correspondant est affiché.
 * Sinon, si un identifiant de magasin est enregistré dans la session, alors l'accueil du magasin correspondant est
 * affiché.
 * Sinon (il n'y a pas d'identifiant de magasin passé en paramètre ou enregistré dans la session), l'utilisateur est
 * redirigé vers l'accueil du site (landing page).
 * Dans le cas où l'identifiant de magasin ne correspond pas à un magasin existant (enregistré dans la base de données),
 * une erreur 404 est affichée.
 */

// Démarrage ou récupération de la session
session_start();

// Vérifier qu'un identifiant de magasin est passé en paramètre et, le cas échéant, enregistrer celui-ci dans la session
if ( isset($_GET['id']) && is_numeric($_GET['id']) )
  $_SESSION['IdMagasin'] = intval($_GET['id']);

// Vérifier qu'un identifiant de magasin est enregistré dans la session
if ( isset($_SESSION['IdMagasin']) ) {
  // Récupérer le magasin ayant pour identifiant celui enregistré dans la session
  @$magasin = rechercherMagasin($_SESSION['IdMagasin'], true)[0];

  /*
   * Si le magasin enregistré dans la session n'existe pas, alors une erreur 404 s'affiche ; l'identifiant du magasin
   * est supprimé de la session
   */
  if (!isset($magasin)) {
    unset($_SESSION['IdMagasin']);
    error(404);
  }
} else {
  // Redirection vers l'accueil
  header('Location: /avocaba');
  exit;
}



?>

<!DOCTYPE html>

<html lang="fr">

<?php htmlHead($magasin['Nom'] . ' – Avocaba'); ?>

<body>
<?php htmlHeader($magasin['IdDepot']); ?>

<div class="magasin">
  <div class="magasin__bienvenue">Bienvenue dans votre magasin</div>
  <h1 class="magasin__nom"><?php echo $magasin['Nom'] ?></h1>
  <address class="magasin__adresse">
    <?php echo $magasin['Adresse'] . ', ' . $magasin['CodePostal'] . ' ' . $magasin['Ville'] ?>
  </address>
  <a class="magasin__switch" href="/avocaba" title="Sélectionner un autre magasin">Changer de magasin</a>
</div>

<main>
  <?php annonceAccueil($_SESSION['IdMagasin']); ?>

  <div class="rayons">
    <h2>Rayons</h2>
    <?php htmlListeRayons($_SESSION['IdMagasin']); // TODO : Améliorer le CSS ?>
  </div>
</main>

<?php footer(); ?>
</body>
</html>