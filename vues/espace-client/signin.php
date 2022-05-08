<?php

/* 🔒 Page de connexion (client) (sign-in page) */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/signin.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/verifier-client.php';



// ********************
// * Script principal *
// ********************

session_start();

// Recherche de l'utilisateur : Réception de données
if ( !empty($_POST['email']) and !empty($_POST['password']) ) {
  // On vérifie que le client est dans la base de données
  $client = verifierClient($_POST['email'], $_POST['password']);

  if ($client) {
    // Si l'adresse e-mail est présente et que le mot de passe est correct, une session est initialisée
    sessionClient($client);
  }
  else {
    // On définit un message qui indique que les informations de connexion ne sont pas correctes
    $message = "Identifiant ou mot de passe incorrect, veuillez réessayer.";
  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php htmlHead('Se connecter – Avocaba'); ?>

<body class="authentification">
  <form class="authentification__form" action="signin.php" method="POST">
    <button class="nav__btn-retour btn" onclick="history.back();" title="Revenir à la page précédente">Retour</button>

    <h1 id="authentification__titre">Connexion</h1>

    <?= !empty($message) ? "<div class='authentification__err'>$message</div>" : '' ?>

    <label>
      Adresse email
      <input type="email" name="email" id="email"
             value="<?= !empty($_POST['email']) ? $_POST['email'] : '' ?>"
             required>
    </label>
    <label>
      Mot de passe
      <input type="password" name="password" id="password"
             minlength="8" maxlength="16" pattern="([0-9a-zA-Z._#-]){8,16}" required>
      <a href="reset-password.php" title="Réinitialiser votre mot de passe">
        J'ai oublié mon mot de passe
      </a>
    </label>

    <input type="submit" class="btn btn--filled" name="signin" value="Se connecter">

    <hr>

    <div class="authentification__footer">
      Vous n'avez pas encore de compte Avocaba&nbsp;?
      <a href="signup.php" title="Créer un compte">Créez votre compte</a> dès à présent&nbsp;!
    </div>
  </form>
</body>

</html>
