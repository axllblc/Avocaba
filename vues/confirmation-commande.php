<?php

/* Page de confirmation de commande */

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/misc.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';

?>

<!DOCTYPE html>
<html lang="fr">
  <?php htmlHead('Confirmation de commande – Avocaba'); ?>
  <body>
    <?php htmlHeader() ?>
    <main>
      <h1>Commande validée</h1>
      <p>Numéro de commande : <span><?php if(isset($_GET['no'])) echo $_GET['no'] ?></span></p>
      <p>Félicitations, votre commande a bien été validée par Avocaba. Vous allez recevoir un email vous précisant les conditions de retrait de votre commande</p>
      <a id="commande__bouton-commande" href="espace-client/account.php?btClient=commandes">Accéder à mes commandes</a>
    </main>
    <?php footer() ?>
  </body>
</html>
