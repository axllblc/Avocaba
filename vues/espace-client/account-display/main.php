<?php

/* Section principale de l'espace client */

error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/commandes-client.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/bloc-commande.php';



// *************
// * Fonctions *
// *************

function recupNom (): void {
  if ( isset($_SESSION['Client']['IdClient']) ){
    echo $_SESSION['Client']['Prenom'] . ' ' . $_SESSION['Client']['Nom'];
  }
}



// ********************
// * Script principal *
// ********************

// On essaie de récupérer la commande la plus récente du client
$commande = rechercheCommandes($_SESSION['Client']['IdClient'], 1);
if ($commande) {
  // Si le client a déjà effectué une commande
  $commande = $commande[0];
}



// ***********
// * Contenu *
// ***********
?>

<div class="client__affichage-general">
  <h2>Bonjour, <?php recupNom(); ?> !</h2>
  <p>Avocaba est ravi de vous retrouver sur votre espace client !</p>

  <?php
  // Affichage de la dernière commande
  if (!empty($commande)) {
    echo '<p>Dernière commande :</p>';
    afficheCommande($commande);
  }
  else
    echo '<p><em>Aucune commande effectuée.</em></p>';
  ?>

  <a class="client__voir-commandes" href="/avocaba/vues/espace-client/account.php?btClient=commandes">
    Voir toutes mes commandes
  </a>
</div>
