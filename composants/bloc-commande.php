<?php

/* Bloc de commande client */

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';

/**
 * Afficher la commande
 * @param $commande : un tableau contenant les informations d'une commande
 * @return void
 */
function afficheCommande ($commande) { ?>
  <a class="client__commande" href="account.php?btClient=commandes">
    <span class="client__date-commande"><?php if($commande) echo $commande['DateValidation']; ?></span>
    <span class="client__magasin-commande"><?php if($commande) echo $commande['NomDepot']; ?></span>
    <span class="client__prix-commande"><?php if($commande) echo $commande['PrixCommande'].'&nbsp;€'; ?></span>
  </a>
<?php }
