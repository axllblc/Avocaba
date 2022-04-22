<?php

/* 🏠 Accueil du magasin */

error_reporting(E_ALL);

require_once '../composants/html_head.php';
require_once '../composants/html_header.php';
require_once '../composants/footer.php';
require_once '../traitements/recherche-magasin.php';



/*************
 * Fonctions *
 *************/

/**
 * Afficher la page d'erreur 404. Cette fonction met fin à l'exécution du script.
 * @return void
 */
function erreur404 (): void {
  http_response_code(404);
  include '404.php';
  exit;
}



/********************
 * Script principal *
 ********************/


// Vérifier qu'un identifiant de magasin a été passé en paramètre (sinon erreur 404)
if (!isset($_GET['id'])) erreur404();

// Récupérer le magasin ayant pour identifiant celui passé en paramètre
@$magasin = rechercherMagasin($_GET['id'], true)[0];

// Vérifier l'existence du magasin correspondant à l'identifiant passé en paramètre (sinon erreur 404)
if (!isset($magasin)) erreur404();

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
  <div class="annonces">
    <?php // TODO annonces ?>
    Zone réservée aux annonces (producteurs et produits mis en avant)
  </div>

  <div class="rayons">
    <h2>Rayons</h2>
    <?php // TODO liste des rayons ?>
    Ici apparaîtra la liste des rayons
  </div>
</main>

<?php footer(); ?>
</body>
</html>