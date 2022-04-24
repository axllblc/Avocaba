<?php

/* Recherche du client */

require_once 'db.inc.php';
require_once 'misc.inc.php';
require_once 'verifier-client.php';

/**************
 * Constantes *
 **************/

 // Expressions régulières (email et mot de passe déjà définie dans verifier-client)
const REGEX_NOM= "/[a-zA-Z -]{2,30}$/";

// Requêtes à préparer

const MODIFIER_CLIENT = "
UPDATE CLIENTS
SET Nom = ?,
Prenom = ?,
Email = ?,
MotDePasse = ?
WHERE IdClient = ?;
";

/*************
 * Fonctions *
 *************/

/**
 * Modifier les informations d'un client
 * Retourne un booléen selon la réussitede la modification
 * @param $idclient : identifiant du client sur la base de donnée
 * @param $emailActuelle : Email du client avant modification
 * @param $motdepasseActuel : Mot de passe du client avant modification
 * @param $nom : Nouveau nom du client
 * @param $prenom : Nouveau prénom du client
 * @param $email : Nouvel email du client
 * @param $motdepasse1 : Nouveau mot de passe du client
 * @param $motdepasse2 : Nouveau mot de passe du client (vérification)
 */
function modifierClient ($idclient, $emailActuelle, $motdepasseActuel, $nom, $prenom, $email, $motdepasse1, $motdepasse2) {
  // Connexion à la base de données
  $link = dbConnect();

  $result = NULL;

  // Préparation de la requête: on s'ass
  if ( preg_match(REGEX_EMAIL, $emailActuelle) and preg_match(REGEX_MOTDEPASSE, $motdepasseActuel)
  and preg_match(REGEX_NOM, $nom) and preg_match(REGEX_NOM, $prenom) and preg_match(REGEX_EMAIL, $email)
  and $motdepasse1 == $motdepasse2 and (preg_match(REGEX_MOTDEPASSE, $motdepasse2) or $motdepasse1 == ""))
  {

    if (verifierClient($emailActuelle, $motdepasseActuel)){

          $stmt = $link->prepare(MODIFIER_CLIENT);
          checkError($stmt, $link);

          //On chiffre le mot de passe si il est modifié
          if($motdepasse1 != ""){
            $motdepasse =  password_hash($motdepasse1, PASSWORD_DEFAULT);
          }
          else{
            //On associe l'ancien mot de passe à la variable du nouveau, si l'utilisateur n'a pas saisie de nouveau mot de passe (on change juste la clé de cryptage mais le mot de passe pour se connecter reste le même)
            $motdepasse = password_hash($motdepasseActuel, PASSWORD_DEFAULT);
          }
          $status = $stmt->bind_param('ssssi', $nom, $prenom, $email, $motdepasse, $idclient);
          echo "1111111111111111111111111111111é<br>";
          var_dump($status);

          // Exécution de la requête
          $status = $stmt->execute();
          checkError($status, $link);

          $link->close();
          return $status;//booléen selon le succès de la requête

          }
    }
  $link->close();
  return false; //Si les informations renseignées sont incorrectes
}
