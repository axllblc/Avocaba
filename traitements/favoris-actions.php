<?php
/* Page appelée lors d'un click d'un "bouton" sur les pages articles, mes produits et fournisseur */

error_reporting(E_ALL);

/** Url du type /avocaba/traitements/favoris-action.php?table=A&id=B
 * A = "fournisseurs" ou "articles"
 * B = identifient du fournisseur ou de l'article en fonction du A choisi
 * L'identifiant du client est directement récupéré ici
 * Le choix de l'action à faire (supprimer ou ajouter) est en fonction de ce qu'il y a déjà dans la base de données
*/

require_once 'db.inc.php';
require_once 'favoris.inc.php';

session_start();

// Dans un premier temps l'utilisateur doit forcément être connecté pour enregistrer un produit en favoris
// Du coup s'il n'est pas connecté il est envoyé sur la page de connexion
if(!isset($_SESSION['Client'])) {
  header('Location: ../vues/espace-client/account.php');
}

// On récupère l'action de l'opération en GET
if (isset($_GET['table']) &&
    isset($_GET['id'])) {
  actionsFavoris($_GET['table'], $_SESSION['Client']['IdClient'], $_GET['id']);
  header('Location: ../vues/mes-produits.php');
}
?>