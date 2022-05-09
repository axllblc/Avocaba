<?php

/* 📂 Liste des rayons */

require_once 'db.inc.php';

// **************
// * Constantes *
// **************

// Requêtes à préparer

/** Liste des rayons disponibles dans un dépôt. */
const REQ_RAYONS = '
SELECT DISTINCT r.IdRayon, r.Nom
FROM rayons r
INNER JOIN articles a ON r.IdRayon = a.IdRayon
INNER JOIN stocker s ON a.IdArticle = s.IdArticle
INNER JOIN depots d ON s.IdDepot = d.IdDepot
WHERE d.IdDepot = ?
ORDER BY r.Nom;
';

/** Nom du rayon, connaissant son identifiant */
const NOM_RAYON = '
SELECT r.Nom
FROM RAYONS r
WHERE r.IdRayon = ?
';



// *************
// * Fonctions *
// *************

/**
 * Récupérer la liste des rayons disponibles dans un dépôt. Seuls les rayons contenant des articles figurent dans la
 * liste.
 * @param int $idMagasin L'identifiant du dépôt
 * @return array Liste des rayons disponibles dans le dépôt ayant pour identifiant $idMagasin
 */
function listeRayons (int $idMagasin): array {
  // Connexion à la base de données
  $link = dbConnect();

  // Préparation de la requête
  $stmt = $link->prepare(REQ_RAYONS);
  checkError($stmt, $link);

  $status = $stmt->bind_param('i', $idMagasin);
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


/**
 * Récupérer le nom du rayon d'après son identifiant.
 * @param int $idRayon L'identifiant du rayon
 * @return string|bool nom du rayon / false si non trouvé
 */
function nomRayon (int $idRayon): string|bool {
  // Connexion à la base de données
  $link = dbConnect();

  // Préparation de la requête
  $stmt = $link->prepare(NOM_RAYON);
  checkError($stmt, $link);

  $status = $stmt->bind_param('i', $idRayon);
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
  if(count($resultArray)>0){
    return $resultArray[0]['Nom'];
  }
  else{
    return false;
  }

}

//exemple : echo nomRayon(22);
