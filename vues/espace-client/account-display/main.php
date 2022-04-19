<?php
/*  Bloc main de l'espace client */
// TODO: Finir l'aspect dynamique
error_reporting(E_ALL);

/*************
 * Fonctions *
 *************/
function recupNom(){
  if(isset($_SESSION['IdClient']))
    echo $_SESSION['Prenom'].' '.$_SESSION['Nom'];
}
//Contenu de la section main/accueil de l'espace client
?>
<!DOCTYPE html>
<html lang="fr">
<div class="client__affichage-general">
  <h2>Bonjour,  <?php recupNom(); ?> !</h2>
  <p>Avocaba est ravi de vous retrouver sur votre espace client</p>
  <p>Dernière commande :</p>
  <a class="client__derniere-commande" href="account.php">
    <span class="client__date-commande">02/06/2022</span>
    <span class="client__magasin-commande">Tours</span>
    <span class="client__prix-commande">37,87€</span>
  </a>
  <br>
  <a class="client__voir-commandes" href="account.php">Voir toutes mes commandes</a>
</div>
</html>
