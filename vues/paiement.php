<?php

/* 💳 Page de paiement */

error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/panier.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/date.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/commandes-client.php';



// **************
// * Constantes *
// **************

const REGEX_NOM_PRENOM = '/^[A-Za-z\s\-\']{3,25}\s+[A-Za-z\s\-\']{3,25}$/';
const REGEX_CVV = '/^\d{3,4}$/';
const REGEX_DATE = '/^\d{4}$/';
const REGEX_NUMERO = '/^(\d{4}\s){3}[0-9]{4}$/';



// *************
// * Fonctions *
// *************

/**
 * Vérifier que la date d'expiration de carte est valide et n'est pas dépassée.
 * @param string $dateExp Chaîne de caractères représentant la date d'expiration au format <code>MMAA</code>
 * @return bool Booléen indiquant la validité ou non de la date d'expiration.
 */
function verifierDateExp (string $dateExp): bool {
  if ( preg_match(REGEX_DATE, $dateExp) ) {
    $mois  = intval(substr($dateExp, 0, 2));
    $annee = intval(substr($dateExp, 2, 2));

    $date = getdate();
    $moisCourant   = $date['mon'];
    $anneeCourante = intval(substr($date['year'], 2, 2));

    if (  ($mois > 0) && ($mois <= 12) &&
          ( ($annee > $anneeCourante) || ($annee === $anneeCourante && $mois >= $moisCourant) )  )
      return true;
  }

  return false;
}



// ********************
// * Script principal *
// ********************

session_start();

// Si l'utilisateur veut revenir au panier
if (isset($_POST['retour'])) {
  $_SESSION['Panier']['Verrouillage'] = false;
  header('Location: panier.php');
}

// On vérifie que le client est connecté
if (!isset($_SESSION['Client'])) {
  $_SESSION['Panier']['Verrouillage'] = false;
  header('Location: espace-client/account.php');
}
elseif (isset($_SESSION['Panier']) and count($_SESSION['Panier']['IdArticle']) > 0 and isset($_POST['validerPanier'])) {
  // Cas où le client arrive sur la page de paiement
  // On vérifie que le client a un panier non-vide et qu'il arrive bien sur cette page
  //  depuis le panier (où il a dû choisir une date de retrait)
  $_SESSION['Panier']['Verrouillage'] = true;
  $panier = $_SESSION['Panier'];
}
elseif (isset($_POST['payer'])) {
  // Cas où le client vient de remplir le formulaire de paiement
  if ( isset($_POST['choix_jour']) && isset($_POST['choix_heure']) && preg_match(REGEX_NOM_PRENOM, $_POST['nom'])
       && preg_match(REGEX_NUMERO, $_POST['no']) && verifierDateExp($_POST['cardExpiration'])
       && preg_match(REGEX_CVV, $_POST['cvv']) ) {

    // Si les informations renseignées sont correctes, on valide sa commande

    // Ajout de la commande dans la base de donnée
    $IdCommande = ajouterCommande($_SESSION['Client']['IdClient'], $_SESSION['Depot']['IdDepot'], $_POST['choix_jour'].' '.$_POST['choix_heure'].':00:00');

    // Ajout des articles associés à la commande
    for ($i=0; $i < nbArticles(); $i++) {
      ajouterArticleACommande($_SESSION['Panier']['IdArticle'][$i], $IdCommande, $_SESSION['Panier']['Qte'][$i]);
    }

    // Suppression du panier dans la session
    unset($_SESSION['Panier']);

    // Redirection vers la page de confirmation de commande
    header('Location: confirmation-commande.php?no=' . $IdCommande);
  }
  else {
    // Les informations de paiement sont incorrectes
    $feedback = 'Les informations saisies sont erronées. Veuillez réessayer.';
  }
}
else{
  // Autres cas : le client n'arrive pas sur cette page de manière officielle (on le redirige vers le magasin)
  header('Location: magasin.php');
  exit;
}

$date = explode('-', $_POST['choix_jour']);
$heureDebut = $_POST['choix_heure'];

?>

<!DOCTYPE html>
<html lang="fr">

  <?php htmlHead(); ?>

  <body>
    <nav>
      <form action="paiement.php" method="post">
        <input class="nav__btn-retour btn" type="submit" name="retour" title="Revenir à la page précédente" value='Retour'>
      </form>
      <div class="nav__sep"><!--Séparateur--></div>
      <p>Récapitulatif et paiement</p>
    </nav>

    <main class="paiement">
      <h1 class="paiement__titre">Récapitulatif et paiement</h1>

      <div class="paiement__main">
        <h2>Informations de paiement</h2>

        <form class="paiement__form" action="paiement.php?" method="post">
          <?= !empty($feedback) ? '<div class="feedback">' . $feedback . '</div>' : '' ?>
          <label>
            Nom du porteur
            <input type="text" id="nom" name="nom" title="Nom Prénom" pattern="([A-Za-z']{3,25}\s+[A-Za-z']{3,25})"
                   autocomplete="cc-name" required value="<?= $_POST['nom'] ?? '' ?>">
          </label>
          <label>
            Numéro de carte
            <input type="text" id="no" name="no"
                   title="La saisie doit contenir uniquement des chiffres et respecter le format : XXXX XXXX XXXX XXXX"
                   placeholder="XXXX XXXX XXXX XXXX" pattern="([0-9]{4}\s){3}[0-9]{4}" autocomplete="cc-number"
                   required value="<?= $_POST['no'] ?? '' ?>">
          </label>
          <label>
            Date de validité
            <input type="text" id="cardExpiration" name="cardExpiration" title="Date de validité au format MMAA"
                   size="4" pattern="[0-9]{4}" placeholder="MMAA" autocomplete="cc-exp" required
                   value="<?= $_POST['cardExpiration'] ?? '' ?>">
          </label>
          <label>
            Code de sécurité
            <input type="text" id="cvv" name="cvv" value="" pattern="\d{3,4}" size="4" autocomplete="cc-csc" required>
          </label>
          <input type="hidden" id="choix_jour" name="choix_jour" value="<?php echo $_POST['choix_jour'] ?>">
          <input type="hidden" id="choix_heure" name="choix_heure" value="<?php echo $_POST['choix_heure'] ?>">
          <input class="btn btn--filled" type="submit" name="payer" value="Confirmer ma saisie">
        </form>
      </div>

      <div class="paiement__recapitulatif">
        <h2>Récapitulatif de la commande</h2>
        <p>Retrait : <b><?= $date[2] . ' ' . MOIS[$date[1]] . ' ' . $date[0] ?></b> à <b><?= $heureDebut ?>&nbsp;h</b></p>
        <p>Nombre d'articles : <b><?= nbArticles() ?></b></p>
        <p>Montant total de la commande : <b><?= montantPanier() ?>&nbsp;€</b></p>
        <p>Lieu de retrait : <b><?= $_SESSION['Depot']['Nom'] ?></b></p>
      </div>
    </main>

    <script src="/avocaba/js/paiement.js"></script>
  </body>
</html>
