<?php

// Ne pas lever d'exception en cas d'erreur (comportement par défaut de PHP < 8.1)
mysqli_report(MYSQLI_REPORT_OFF);

/**
 * Se connecter à la base de données. Retourne un objet mysqli
 */
function dbConnect ()
{
    $link = mysqli_connect('localhost', 'avocaba', 'IPx@tVqM46!-zPyI', 'avocaba');
    checkError($link, $link, 'Erreur de connexion à la base de données.');

    $status = $link->set_charset('utf8mb4');
    checkError($status, $link);

    return $link;
}

/**
 * Vérifier la présence d'erreurs.
 * Si $data vaut false, la connexion à la base de données est fermée et le script est interrompu.
 */
function checkError ($data, $link, $message = 'Une erreur s\'est produite.') {
    if (!$data) {
        $link?->close();
        exit($message);
    }
}
