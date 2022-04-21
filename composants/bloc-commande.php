<?php

/* ⬇️ Bloc de commande client */

require_once '../../composants/html_head.php';

/**
 * Afficher la commande
 * @param $commande : un tableau contenant les informations d'une commande
 * @return void
 */
function afficheCommande ($commande) { ?>
  <a class="client__derniere-commande" href="account.php?btClient=commandes">
    <span class="client__date-commande"><?php if($commande) echo $commande['DateValidation']; ?></span>
    <span class="client__magasin-commande"><?php if($commande) echo $commande['NomDepot']; ?></span>
    <span class="client__prix-commande"><?php if($commande) echo $commande['PrixCommande'].'€'; ?></span>
    <?php if(!$commande) echo "<span><p>Aucune commande effectuée</p></span>" ?>
  </a>
<?php }
