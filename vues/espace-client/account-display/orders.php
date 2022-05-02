<?php

/* Section des commandes du client */

// TODO: Finir l'aspect dynamique

error_reporting(E_ALL);

require_once '../../traitements/commandes-client.php';
require_once '../../composants/bloc-commande.php';



/********************
* Script principal *
********************/

// On récupère les commandes (on se limite au 20 dernières)
$commandes = rechercheCommandes ($_SESSION['Client']['IdClient'], 20);



/***********
 * Contenu *
 ***********/
?>

<div class="client__affichage-commandes">
  <h2>Vos dernières commandes</h2>
  <?php
  if($commandes)
    foreach ($commandes as $key => $comm)
      afficheCommande($comm);
  ?>
</div>
