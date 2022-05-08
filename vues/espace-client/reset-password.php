<?php

/* 🔒 Page de réinitialisation du mot de passe */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';


/********************
 * Script principal *
 ********************/

// Si l'utilisateur est déjà connecté, il est redirigé vers la page où il se trouvait
if ( !empty($_SESSION['Client']) ) {
  header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/avocaba/') );
  exit;
}

$done = false;
if (isset($_POST['email'])) {
  if (preg_match('/^[a-zA-Z1-9-.]+@[a-zA-Z1-9-]+\.[a-zA-Z]{2,6}$/', $_POST['email'])) {
    $done = true;
  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php htmlHead('Réinitialisation du mot de passe – Avocaba');

if (!$done) { ?>

<body class="authentification">
  <form class="authentification__form" action="reset-password.php" method="post">
    <a class="nav__btn-retour btn" href="signin.php" title="Revenir à la page précédente">Retour</a>
    <h1>Réinitialiser mon mot de passe</h1>
    <div>
      Pour réinitialiser votre mot de passe, entrez votre adresse email ci-dessous.
    </div>
    <div>
      Vous recevrez un mail avec un lien pour réinitialiser votre mot de passe.
    </div>
    <label>
      Votre adresse email
      <input type="email" name="email" id="email" required>
    </label>
    <input type="submit" class="btn btn--filled" name="valider-reset" value="Réinitialiser le mot de passe">
  </form>
</body>

<?php } else { ?>

<body class="authentification">
  <div class="authentification__form">
    <button class="nav__btn-retour btn" onclick="history.back();" title="Revenir à la page précédente">Retour</button>
    <h1>Réinitialiser mon mot de passe</h1>
    <p>
      Un email muni d'un lien permettant de réinitialiser votre mot de passe vous a été envoyé.
    </p>
    <a class="btn" href="/avocaba">Revenir à l'accueil</a>
  </div>
</body>

<?php } ?>

</html>
