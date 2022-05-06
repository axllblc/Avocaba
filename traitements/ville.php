<?php

/* 📂 Information de la ville (selon son identifiant)*/

require_once 'db.inc.php';

/**************
 * Constantes *
 **************/

// Requête à préparer

const RECHERCHE_VILLE = '
SELECT v.IdVille, v.Nom, v.CodeCom, v.CodePos, v.CodeDep, v.Slug
FROM VILLES v
WHERE v.IdVille = ?
';



/*************
 * Fonctions *
 *************/

/**
 * Récupérer les informations de la ville d'après son identifiant.
 * @param int $idVille L'identifiant de la ville
 * @return array|bool informations sur la ville, false si non trouvé
 */
function ville (int $idVille): array|bool {
  // Connexion à la base de données
  $link = dbConnect();

  // Préparation de la requête
  $stmt = $link->prepare(RECHERCHE_VILLE);
  checkError($stmt, $link);

  $status = $stmt->bind_param('i', $idVille);
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
    return $resultArray;
  }
  else{
    return false;
  }


}

//exemple :
//var_dump(ville(22));
