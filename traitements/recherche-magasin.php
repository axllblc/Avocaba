<?php

/* Recherche de magasins */

require_once 'db.inc.php';
require_once 'misc.inc.php';

/**************
 * Constantes *
 **************/

// Expressions régulières

const REGEX_CODE_POSTAL = '/^\d{5}$/';
const REGEX_CODE_DEPT = '/^(?>\d{2,3})|(?>2[AB])$/';


// Requêtes à préparer

const RECHERCHE_CP = '
SELECT d.`IdDepot`, d.`Nom`, d.`Adresse`, v.`Nom` AS `Ville`, v.`CodePos` AS `CodePostal`
FROM DEPOTS AS d
INNER JOIN VILLES AS v USING (`IdVille`)
WHERE v.`CodePos` = ?
LIMIT 10;
';

const RECHERCHE_DEPT = '
SELECT d.`IdDepot`, d.`Nom`, d.`Adresse`, v.`Nom` AS `Ville`, v.`CodePos` AS `CodePostal`
FROM DEPOTS AS d
INNER JOIN VILLES AS v USING (`IdVille`)
WHERE v.CodeDep = ?
LIMIT 10;
';

const RECHERCHE_VILLE = '
SELECT d.`IdDepot`, d.`Nom`, d.`Adresse`, v.`Nom` AS `Ville`, v.`CodePos` AS `CodePostal`
FROM DEPOTS AS d
INNER JOIN VILLES AS v USING (`IdVille`)
WHERE v.`Slug` LIKE ?
LIMIT 10;
';

const RECHERCHE_ID = '
SELECT d.`IdDepot`, d.`Nom`, d.`Adresse`, v.`Nom` AS `Ville`, v.`CodePos` AS `CodePostal`
FROM DEPOTS AS d
INNER JOIN VILLES AS v USING (`IdVille`)
WHERE d.`IdDepot` = ?
LIMIT 1;
';



/*************
 * Fonctions *
 *************/

/**
 * Rechercher un dépôt.
 * @param string|int $str Critère de recherche : il peut s'agir d'un code postal, d'un numéro de département ou d'un nom
 *                        de ville OU de l'identifiant d'un dépôt.
 * @param bool $id Vaut false si le premier paramètre correspond à un code postal, un numéro de département ou un nom de
 *                 ville ; vaut true si le premier paramètre correspond à l'identifiant d'un dépôt.
 * @return array Un tableau contenant les résultats (dépôts décrits par leur identifiant, nom, adresse, ville et code
 *               postal).
 */
function rechercherMagasin (string|int $str, bool $id = false): array {
  $link = dbConnect();

  // Préparation de la requête
  if ($id) {
    $stmt = $link->prepare(RECHERCHE_ID);
    checkError($stmt, $link);

    $status = $stmt->bind_param('i', $str);

  } else {
    if (preg_match(REGEX_CODE_POSTAL, $str)) {
      // Rechercher le(s) dépôt(s) présents dans la ville dont on connaît le code postal
      $cp = +$str;

      $stmt = $link->prepare(RECHERCHE_CP);
      checkError($stmt, $link);

      $status = $stmt->bind_param('i', $cp);

    } elseif (preg_match(REGEX_CODE_DEPT, $str)) {
      // Rechercher le(s) dépôt(s) présents dans un département
      $stmt = $link->prepare(RECHERCHE_DEPT);
      checkError($stmt, $link);

      $status = $stmt->bind_param('s', $str);

    } else {
      // Rechercher un/des dépôt(s) par nom de ville
      $slug = slugify($str);

      $stmt = $link->prepare(RECHERCHE_VILLE);
      checkError($stmt, $link);

      $status = $stmt->bind_param('s', $slug);

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

  // Fermeture de la connexion à la base de données
  $link->close();

  return $resultArray;
}