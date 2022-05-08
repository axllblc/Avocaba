<?php

/* ✅ Page de confirmation de commande */

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/misc.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';

// On n'affiche cette page que si l'on vient d'effectuer une commande
if(!isset($_GET['no'])){
  header('Location: espace-client/account.php');
}

?>

<!DOCTYPE html>
<html lang="fr">
  <?php htmlHead('Confirmation de commande – Avocaba'); ?>
  <body>
    <?php htmlHeader() ?>
    <main>
      <h1>Commande validée</h1>
      <p>Numéro de commande : <b><?php if(isset($_GET['no'])) echo $_GET['no'] ?></b></p>
      <p>
        Votre commande a bien été validée par Avocaba&nbsp! Merci pour votre confiance. Vous allez recevoir un email
        vous précisant les conditions de retrait de votre commande.
      </p>
      <a id="commande__bouton-commande" class="btn btn--filled" href="espace-client/account.php?btClient=commandes">Accéder à mes commandes</a>
    </main>
    <?php footer() ?>
  </body>
</html>
