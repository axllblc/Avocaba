<?php

/* 📂 Nom du rayon (selon son identifiant)*/

require_once 'db.inc.php';

/**************
 * Constantes *
 **************/

// Requête à préparer

const NOM_RAYON = '
SELECT r.Nom
FROM RAYONS r
WHERE r.IdRayon = ?
';



/*************
 * Fonctions *
 *************/

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

//exemple :
//echo nomRayon(22);
