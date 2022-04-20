<?php
/*  Bloc main de l'espace client */
error_reporting(E_ALL);
require_once '../../traitements/commandes-client.php';
require_once '../../composants/bloc-commande.php';

/*************
 * Fonctions *
 *************/
function recupNom(){
  if(isset($_SESSION['IdClient'])){
    echo $_SESSION['Prenom'].' '.$_SESSION['Nom'];
  }
}

/********************
 * Script principal *
 ********************/

//On essaie de récupérer la commande la plus récente du client
$commande = rechercheCommandes ($_SESSION['IdClient'],1);
if($commande){
  //Si le client a déjà effectuée une commande
  $commande = $commande[0];
}

//Contenu de la section main/accueil de l'espace client
?>
<!DOCTYPE html>
<html lang="fr">
<div class="client__affichage-general">
  <h2>Bonjour,  <?php recupNom(); ?> !</h2>
  <p>Avocaba est ravi de vous retrouver sur votre espace client</p>
  <p>Dernière commande :</p>
  <a class="client__derniere-commande" href="account.php?btClient=commandes">
    <?php afficheCommande($commande); ?>
  </a>
  <br>
  <a class="client__voir-commandes" href="account.php?btClient=commandes">Voir toutes mes commandes</a>
</div>
</html>
