<?php

/*  Espace client (account page) */

error_reporting(E_ALL);

require_once '../../composants/html_head.php';
require_once '../../composants/footer.php';

/*************
 * Fonctions *
 *************/

//Fonction qui sert à afficher les différents blocs de l'espace client
function affichage(){
  if (isset($_POST['btClient'])){
    //cas où le client a actionné un bouton pour l'affichage d'un bloc spécifique sur son espace client
    $section = $_POST['btClient'];
  }
  else{
    //cas où le client vient d'être redirigé vers son espace client
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
      include('account-display/datas.php');
      break;
    case 'deco':
    //Le client veut se déconnecter, on quitte la session
      session_start();
      session_unset();
      session_destroy();
      header('Location: ../landing.php');
      exit();
      break;
    default:
      break;
    }
}

//Script qui vérifie que la session est bien active, le cas échéant, redirection vers la page pour se connecter
session_start();
if (!isset($_SESSION["IdClient"])){
header('Location: login.php');
}



/********************
 * Script principal *
 ********************/
?>
<!DOCTYPE html>
<html lang="fr">

<?php htmlHead('Avocaba : Espace Client'); ?>

<body>
  <main class="client">
    <h1 class="client__titre">Mon compte</h1>
    <form class="client__onglet" action="account.php" method="post">
      <button id="client__bt-general" type="submit" name="btClient" value="accueil">Général</button>
      <button id="client__bt-commandes" type="submit" name="btClient" value="commandes">Mes commandes</button>
      <button id="client__bt-info-perso" type="submit" name="btClient" value="infos">Mes informations personnelles</button>
      <button id="client__bt-deconnexion" type="submit" name="btClient" value="deco">Déconnexion</button>
    </form>
    <div class="client__affichage">
      <?php affichage() ?>
    </div>
  </main>
<?php footer(); ?>
</body>
</html>
