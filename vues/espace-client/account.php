<?php

/* Espace client (account page) */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';

/*************
 * Fonctions *
 *************/

/**
 * Afficher les différents blocs de l'espace client
 * @return void
 */
function affichage () {
  if ( isset($_GET['btClient']) ) {
    // Cas où le client a actionné un bouton pour l'affichage d'un bloc spécifique sur son espace client
    $section = $_GET['btClient'];
  }
  else{
    // Cas où le client vient d'être redirigé vers son espace client
    $section = 'accueil';
  }

  switch ($section) {
    case 'accueil':
      include('account-display/main.php');
      break;
    case 'commandes':
      include('account-display/orders.php');
      break;
    case 'infos':
      include('account-display/data.php');
      break;
    case 'deco':
      // Déconnexion : la session est détruite
      session_start();
      session_unset();
      session_destroy();
      setcookie(session_name(), '', 0, '/');       // Le cookie de session est effacé sur le client
      header('Location: /avocaba');         // Redirection
      exit();
    default:
      break;
    }
}

/********************
 * Script principal *
 ********************/

// Si l'utilisateur n'est pas connecté, il est redirigé vers la page de connexion.
session_start();

if ( !isset($_SESSION['Client']) ){

  // On enregistre l'adresse à rediriger si l'utilisateur arrive sur la page de connexion depuis une page du site
  if(isset($_SERVER['HTTP_REFERER']) and (!str_contains($_SERVER['HTTP_REFERER'], 'signin.php') or !str_contains($_SERVER['HTTP_REFERER'], 'signup.php'))){
    $_SESSION['HTTP-TO-REFER'] = $_SERVER['HTTP_REFERER'];
  }
  header('Location: signin.php');
}

$section = $_GET['btClient'] ?? '';

?>
<!DOCTYPE html>
<html lang="fr">

<?php htmlHead('Espace Client – Avocaba'); ?>

<body>
  <?php htmlHeader( isset($_SESSION['Depot']['IdDepot']) ); ?>

  <main class="client">
    <h1 class="client__titre">Mon compte</h1>
    <form class="client__onglet" action="account.php" method="GET">
      <button id="client__bt-general"
              class="<?= ($section === 'accueil' || $section === '')  ? 'client__bt-selection' : '' ?>"
              type="submit" name="btClient" value="accueil">Général</button>
      <button id="client__bt-commandes"
              class="<?= $section === 'commandes' ? 'client__bt-selection' : '' ?>"
              type="submit" name="btClient" value="commandes">Mes commandes</button>
      <button id="client__bt-info-perso"
              class="<?= $section === 'infos' ? 'client__bt-selection' : '' ?>"
              type="submit" name="btClient" value="infos">Mes informations personnelles</button>
      <button id="client__bt-deconnexion" type="submit" name="btClient" value="deco">Déconnexion</button>
    </form>
    <div class="client__affichage">
      <?php affichage(); ?>
    </div>
  </main>

  <?php footer(); ?>
</body>
</html>
