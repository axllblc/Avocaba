<?php

/* Recherche de dépôts */

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/db.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/misc.inc.php';



// **************
// * Constantes *
// **************

// Expressions régulières

/** Expression régulière vérifiée par un code postal */
const REGEX_CODE_POSTAL = '/^\d{5}$/';

/** Expression régulière vérifiée par un code de département (la Corse est prise en compte) */
const REGEX_CODE_DEPT = '/^(?>\d{2,3})|(?>2[AB])$/';


// Requêtes à préparer : recherche de dépôt

/** Recherche de dépôt par code postal. Cette requête se limite à 10 dépôts. */
const RECHERCHE_CP = '
SELECT d.`IdDepot`, d.`Nom`, d.`Adresse`, v.`Nom` AS `Ville`, v.`CodePos` AS `CodePostal`
FROM DEPOTS AS d
INNER JOIN VILLES AS v USING (`IdVille`)
WHERE v.`CodePos` = ?
LIMIT 10;
';

/** Recherche de dépôt par code de département. Cette requête se limite à 10 dépôts. */
const RECHERCHE_DEPT = '
SELECT d.`IdDepot`, d.`Nom`, d.`Adresse`, v.`Nom` AS `Ville`, v.`CodePos` AS `CodePostal`
FROM DEPOTS AS d
INNER JOIN VILLES AS v USING (`IdVille`)
WHERE v.CodeDep = ?
LIMIT 10;
';

/** Recherche de dépôt par nom de ville. Cette requête se limite à 10 dépôts. */
const RECHERCHE_DEPOT_VILLE = '
SELECT d.`IdDepot`, d.`Nom`, d.`Adresse`, v.`Nom` AS `Ville`, v.`CodePos` AS `CodePostal`
FROM DEPOTS AS d
INNER JOIN VILLES AS v USING (`IdVille`)
WHERE v.`Slug` LIKE ? OR d.Nom LIKE CONCAT(\'Avocaba \', ?)
LIMIT 10;
';

/** Recherche de dépôt par identifiant. Cette requête ne retourne qu'un seul dépôt. */
const RECHERCHE_ID = '
SELECT d.`IdDepot`, d.`Nom`, d.`Adresse`, v.`Nom` AS `Ville`, v.`CodePos` AS `CodePostal`, d.`IdVille`
FROM DEPOTS AS d
INNER JOIN VILLES AS v USING (`IdVille`)
WHERE d.`IdDepot` = ?
LIMIT 1;
';


// Requête à préparer : enregistrement du dernier dépôt fréquenté par l'utilisateur

/** Enregistrement du dernier dépôt fréquenté par l'utilisateur. */
const ENREGISTREMENT_DERNIER_DEPOT = '
UPDATE CLIENTS
SET `IdDepot` = ?
WHERE `IdClient` = ?;
';



// *************
// * Fonctions *
// *************

// Requêtes vers la base de données

/**
 * Rechercher un dépôt.
 * @param string|int $query Critère de recherche : il peut s'agir d'un code postal, d'un numéro de département ou d'un
 *                          nom de ville OU de l'identifiant d'un dépôt.
 * @param bool $id Vaut false si le premier paramètre correspond à un code postal, un numéro de département ou un nom de
 *                 ville ; vaut true si le premier paramètre correspond à l'identifiant d'un dépôt.
 * @return array Un tableau contenant les résultats (dépôts décrits par leur identifiant, nom, adresse, ville et code
 *               postal).
 */
function rechercherMagasin (string|int $query, bool $id = false): array {
  $link = dbConnect();

  // Préparation de la requête
  if ($id) {

    // Rechercher le dépôt correspondant à l'identifiant passé en paramètre
    $stmt = $link->prepare(RECHERCHE_ID);
    checkError($stmt, $link);

    $status = $stmt->bind_param('i', $query);

  } else {

    if (preg_match(REGEX_CODE_POSTAL, $query)) {

      // Rechercher le(s) dépôt(s) présents dans la ville dont on connaît le code postal
      $cp = +$query;

      $stmt = $link->prepare(RECHERCHE_CP);
      checkError($stmt, $link);

      $status = $stmt->bind_param('i', $cp);

    } elseif (preg_match(REGEX_CODE_DEPT, $query)) {

      // Rechercher le(s) dépôt(s) présents dans un département
      $stmt = $link->prepare(RECHERCHE_DEPT);
      checkError($stmt, $link);

      $status = $stmt->bind_param('s', $query);

    } else {

      // Rechercher un/des dépôt(s) par nom de ville
      $slug = slugify($query);

      $stmt = $link->prepare(RECHERCHE_DEPOT_VILLE);
      checkError($stmt, $link);

      $status = $stmt->bind_param('ss', $slug, $slug);

    }

  }

  checkError($status, $link);

  // Exécution de la requête
  $status = $stmt->execute();
  checkError($status, $link);

  // Récupération du résultat
  $result = $stmt->get_result();
  checkError($result, $link);

  $resultArray = $result->fetch_all(MYSQLI_ASSOC);

  // Fermeture de la connexion à la base de données et libération de la mémoire associée
  $result->close();
  $stmt->close();
  $link->close();

  return $resultArray;
}


/**
 * Enregistrer le dernier dépôt fréquenté par l'utilisateur, lorsqu'il est connecté.
 * @param int $idClient Identifiant du client.
 * @param int $idDepot Identifiant du dernier dépôt fréquenté par ce client.
 * @return void
 */
function enregistrerDernierDepot (int $idClient, int $idDepot): void {
  $link = dbConnect();

  // Préparation de la requête de mise à jour
  $stmt = $link->prepare(ENREGISTREMENT_DERNIER_DEPOT);
  checkError($stmt, $link);

  $status = $stmt->bind_param('ii', $idDepot, $idClient);
  checkError($status, $link);

  // Exécution de la requête de mise à jour
  $status = $stmt->execute();
  checkError($status, $link);

  // Fermeture de la connexion à la base de données
  $link->close();
}


// Sélection du dépôt et mise en session

/**
 * Enregistrer dans la session les informations sur le dépôt sélectionné par l'utilisateur.
 * @param int $id Identifiant du dépôt sélectionné par l'utilisateur.
 * @return bool Booléen indiquant le succès ou non de la mise en session des informations sur le dépôt :
 *              <code>true</code> en cas de succès ; <code>false</code> en cas d'échec (dépôt inexistant).
 */
function selectionDepot (int $id): bool {
  // Récupérer le dépôt ayant pour identifiant celui passé en paramètre.
  @$magasin = rechercherMagasin($id, true)[0];

  // Si le dépôt correspondant n'existe pas, la fonction retourne false.
  if ( !isset($magasin) ) return false;

  // Les informations sur le dépôt sont enregistrées dans la session.
  if ( !isset($_SESSION) ) session_start();

  $_SESSION['Depot'] = array();
  $_SESSION['Depot']['IdDepot'] = $magasin['IdDepot'];
  $_SESSION['Depot']['Nom'] = $magasin['Nom'];
  $_SESSION['Depot']['Adresse'] = $magasin['Adresse'];
  $_SESSION['Depot']['Ville'] = $magasin['Ville'];
  $_SESSION['Depot']['CodePostal'] = $magasin['CodePostal'];

  return true;
}
