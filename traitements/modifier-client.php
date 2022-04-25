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
 * Modifier les informations d'un client.
 * Retourne un booléen selon la réussite de la modification.
 * @param $idClient : identifiant du client sur la base de donnée
 * @param $emailActuel : Email du client avant modification
 * @param $motDePasseActuel : Mot de passe du client avant modification
 * @param $nom : Nouveau nom du client
 * @param $prenom : Nouveau prénom du client
 * @param $email : Nouvel email du client
 * @param $motDePasse1 : Nouveau mot de passe du client
 * @param $motDePasse2 : Nouveau mot de passe du client (vérification)
 * @return bool Un booléen valant true si la modification a été effectuée, false en cas d'erreur
 */
function modifierClient ($idClient, $emailActuel, $motDePasseActuel, $nom, $prenom, $email, $motDePasse1, $motDePasse2): bool {
  // Connexion à la base de données
  $link = dbConnect();

  $result = NULL;

  // Préparation de la requête :
  if ( preg_match(REGEX_EMAIL, $emailActuel) and preg_match(REGEX_MOTDEPASSE, $motDePasseActuel)
       and preg_match(REGEX_NOM, $nom) and preg_match(REGEX_NOM, $prenom) and preg_match(REGEX_EMAIL, $email)
       and $motDePasse1 == $motDePasse2 and (preg_match(REGEX_MOTDEPASSE, $motDePasse2) or $motDePasse1 == "")
  ) {

    if (verifierClient($emailActuel, $motDePasseActuel)){

          $stmt = $link->prepare(MODIFIER_CLIENT);
          checkError($stmt, $link);

          // Le mot de passe est chiffré s'il est modifié
          if ($motDePasse1 != '') {
            $motDePasse =  password_hash($motDePasse1, PASSWORD_DEFAULT);
          }
          else{
            // On associe l'ancien mot de passe à la variable du nouveau, si l'utilisateur n'a pas saisie de nouveau mot de passe (on change juste la clé de cryptage, mais le mot de passe pour se connecter reste le même)
            $motDePasse = password_hash($motDePasseActuel, PASSWORD_DEFAULT);
          }
          $status = $stmt->bind_param('ssssi', $nom, $prenom, $email, $motDePasse, $idClient);
          var_dump($status);

          // Exécution de la requête
          $status = $stmt->execute();
          checkError($status, $link);

          $link->close();
          return $status; // Booléen selon le succès de la requête

          }
    }
  $link->close();
  return false; // Si les informations renseignées sont incorrectes
}
