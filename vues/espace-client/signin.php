<?php

/* 🔒 Page de connexion (client) (sign-in page) */

error_reporting(0);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/signin.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/client.inc.php';



// ********************
// * Script principal *
// ********************

session_start();

// Si l'utilisateur est déjà connecté, il est redirigé vers la page où il se trouvait
if ( !empty($_SESSION['Client']) ) {
  header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/avocaba/') );
  exit;
}

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
    <a class="nav__btn-retour btn" href="/avocaba/" title="Revenir à la page précédente">Retour</a>

    <h1 id="authentification__titre">Connexion</h1>

    <?= !empty($message) ? "<div class='feedback'>$message</div>" : '' ?>

    <label>
      Adresse email
      <input type="email" name="email" id="email"
             value="<?= !empty($_POST['email']) ? $_POST['email'] : '' ?>"
             autocomplete="email" required>
    </label>
    <label>
      Mot de passe
      <input type="password" name="password" id="password"
             minlength="8" maxlength="16" pattern="([0-9a-zA-Z._#-]){8,16}"
             autocomplete="current-password" required>
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
