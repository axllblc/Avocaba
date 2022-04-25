<?php

/* 🔒 Procédure de connexion au compte client */

/*************
 * Fonctions *
 *************/

/**
 * Établir la mise en session des informations sur l'utilisateur.
 * @param $client
 * @return void
 */
function sessionClient ($client) {
  // Initialisation de la session ou récupération de la session courante si elle existe déjà
  session_start();

  // Création d'un nouvel identifiant de session, pour empêcher les attaques utilisant des sessions volées
  session_regenerate_id(true);

  // Mise en session des informations sur le client
  // TODO modifier la structure de la session
  $_SESSION=$client;

  // L'utilisateur est redirigé
  // TODO modifier la redirection selon la page d'où vient l'utilisateur
  //      exemple : S'il vient de la page d'accueil, il est redirigé vers son dernier magasin fréquenté
  header('Location: account.php');
}