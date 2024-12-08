<?php

/* Connexion à la base de données */

error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/error.php';

// Ne pas lever d'exception en cas d'erreur (comportement par défaut de PHP < 8.1)
mysqli_report(MYSQLI_REPORT_OFF);

const DB_PASSWORD = 'IPx@tVqM46!-zPyI';



/**
 * Se connecter à la base de données. Retourne un objet mysqli permettant de faire le lien avec la base de données.
 * @return bool|mysqli
 */
function dbConnect (): bool|mysqli {
  $link = mysqli_connect('localhost', 'Avocaba', DB_PASSWORD, 'avocaba');
  if (!$link) error(500, 'Erreur de connexion à la base de données.');

  $status = $link->set_charset('utf8mb4');
  checkError($status, $link);

  return $link;
}


/**
 * Vérifier la présence d'erreurs.
 * Si $data vaut false, la connexion à la base de données est fermée et le script est interrompu.
 * @param mixed $data Donnée à évaluer
 * @param mysqli $link Objet mysqli permettant de faire le lien avec la base de données
 * @param string $message Message à afficher en cas d'erreur
 */
function checkError (mixed $data, mysqli $link, string $message = 'Une erreur interne s\'est produite.'): void {
  if (!$data) {
    $link->close();
    error(500, $message);
  }
}
