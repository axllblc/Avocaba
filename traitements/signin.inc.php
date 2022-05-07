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
function sessionClient ($client): void {
  // Initialisation de la session ou récupération de la session courante si elle existe déjà
  session_start();

  // Création d'un nouvel identifiant de session, pour empêcher les attaques utilisant des sessions volées
  session_regenerate_id(true);

  // Mise en session des informations sur le client
  $_SESSION['Client'] = array(
    'IdClient' => $client['IdClient'],
    'Nom' => $client['Nom'],
    'Prenom' => $client['Prenom'],
    'Email' => $client['Email']
  );

  // L'utilisateur est redirigé

  if(isset($_SESSION['HTTP-TO-REFER']) and strtolower(parse_url($_SESSION['HTTP-TO-REFER'])['path']) == '/avocaba/' and $client['DernierDepot'] != null){
    // Cas où l'utilisateur arrive sur le site, se connecte, et a déjà un dépôt dernièrement visité : il est redirigé vers ce dépôt
    header('Location: /avocaba/vues/magasin.php?id='. $client['DernierDepot']);
  }

  elseif(isset($_SESSION['HTTP-TO-REFER'])){
    // cas où une adresse de redirection a été enregistrée
    $adresse = $_SESSION['HTTP-TO-REFER'];
    unset($_SESSION['HTTP-TO-REFER']);
    header('Location: ' . $adresse);
  }
  else{
    header('Location: account.php');
  }
}
