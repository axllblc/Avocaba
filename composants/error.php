<?php

/* ⚠️ Page d'erreur */

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';



// **************
// * Constantes *
// **************

// Messages d'erreur par défaut

const ERR_MSG = array(
  400 => 'Votre requête n\'est pas valide.',
  403 => 'Accès refusé.',
  404 => 'La page demandée est introuvable. Elle a peut-être été déplacée ou supprimée.'
);



// *************
// * Fonctions *
// *************

/**
 * Afficher une page d'erreur. L'appel de cette fonction met fin à l'exécution du script.
 * @param int $status Code HTTP décrivant l'erreur (404 par exemple)
 * @param string|null $message Message à afficher
 * @return void
 */
function error (int $status, string $message = NULL): void {
  // Message d'erreur par défaut
  if ( empty($message) ) @$message = ERR_MSG[$status];

  http_response_code($status);

  ?>

  <!DOCTYPE html>
  <html lang="fr">
  <?php htmlHead(); ?>
  <body>
    <p>
      <strong>Erreur <?php echo $status ?></strong>
      <?php
        echo $message ? '&nbsp;: ' . htmlspecialchars($message) : '';
      ?>
    </p>

    <a href="/avocaba">Revenir à l'accueil</a>
  </body>
  </html>

  <?php exit;
}