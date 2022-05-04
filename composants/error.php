<?php

/* ⚠️ Page d'erreur */

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';

/**
 * Afficher une page d'erreur. L'appel de cette fonction met fin à l'exécution du script.
 * @param int $status Code HTTP décrivant l'erreur (404 par exemple)
 * @param string|null $message Message à afficher
 * @return void
 */
function error (int $status, string $message = NULL): void {
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