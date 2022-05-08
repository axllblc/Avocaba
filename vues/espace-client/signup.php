<?php

/* 🔒 Page d'inscription (client) (sign-up page) */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/signin.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/verifier-client.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/inscription-client.php';



// ********************
// * Script principal *
// ********************

// Si l'utilisateur est déjà connecté, il est redirigé vers la page où il se trouvait
if ( !empty($_SESSION['Client']) ) {
  header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/avocaba/') );
  exit;
}

// Inscription client : Réception de données

if ( !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['passwordBis'])
     && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['accept'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $passwordBis = $_POST['passwordBis'];

  if ( $password === $passwordBis ) {
    $client = verifierClient($_POST['email'], $_POST['password']);

    $inscrire = inscrireClient($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['password']);

    if ($inscrire) {
      // Si l'inscription est un succès, on peut établir la mise en session et le rediriger vers l'espace client
      $client = verifierClient($email, $password);

      sessionClient($client);
    }
    else {
      // Message à afficher en cas d'adresse e-mail déjà utilisée
      $message = 'Cette adresse email est déjà utilisée, veuillez réessayer.';
    }
  } else
    $message = 'Les mots de passe saisis ne correspondent pas. Veuillez réessayer.';

}

?>

<!DOCTYPE html>
<html lang="fr">

<?php htmlHead('S\'inscrire – Avocaba'); ?>

<body class="authentification">
  <form class="authentification__form inscription" action="signup.php" method="post">
    <button class="nav__btn-retour btn" onclick="history.back();" title="Revenir à la page précédente">Retour</button>

    <h1 id="authentification__titre">Créer votre compte</h1>

    <?= !empty($message) ? "<div class='authentification__err'>$message</div>" : '' ?>

    <label>
      Prénom
      <input type="text" name="prenom" id="prenom"
             minlength="2" maxlength="30" pattern="[a-zA-Z -]{2,30}" required>
    </label>
    <label>
      Nom
      <input type="text" name="nom" id="nom"
             minlength="2" maxlength="30" pattern="[a-zA-Z -]{2,30}" required>
    </label>
    <label>
      Adresse email
      <input type="email" name="email" id="email" required>
    </label>
    <label>
      Mot de passe
      <input type="password" name="password" id="password"
             minlength="8" maxlength="16" pattern="([0-9a-zA-Z._#-]){8,16}" required>
    </label>
    <label>
      Confirmer le mot de passe
      <input type="password" name="passwordBis" id="passwordBis"
             minlength="8" maxlength="16" pattern="([0-9a-zA-Z._#-]){8,16}" required>
    </label>
    <label class="authentification__row">
      <input type="checkbox" name="accept" id="accept" required>
      <span>
        En créant votre compte, vous acceptez nos <a href="#">conditions d'utilisation</a> et nos <a href="#">conditions de vente</a>.
      </span>
    </label>
    <label class="authentification__row">
      <input type="checkbox" name="communications" id="communications">
      <span>
        Vous acceptez de recevoir des communications commerciales et bons plans par email de la part d'Avocaba.
        <small>Facultatif</small>
    </label>

    <input type="submit" class="btn btn--filled" name="signup" value="Créer mon compte"
           title="Valider et créer votre compte">

    <hr>

    <div class="authentification__footer">
      Vous avez déjà un compte&nbsp;? <a href="signin.php" title="Se connecter">Connectez-vous</a>.
    </div>
  </form>
</body>
</html>
