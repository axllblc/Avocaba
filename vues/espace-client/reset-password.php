<?php

/* 🔒 Page de réinitialisation du mot de passe */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';


/********************
 * Script principal *
 ********************/

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

<body>
<main class="reinitialise__mot__passe">
  <div id="reinitialise_formulaire">
    <form class="reinitialise_reinitialiser" action="reset-password.php" method="post">
      <button class="nav__btn-retour" onclick="history.back();" title="Revenir à la page précédente">Retour</button>
      <h1>Réinitialiser mon mot de passe</h1>
      <p>
        Pour réinitialiser votre mot de passe, entrez votre adresse email ci-dessous.
      </p>
      <p>
        Vous recevrez un mail avec un lien pour réinitialiser votre mot de passe.
      </p>
      <label for="email">Votre adresse email</label><br>
      <input type="text" name="email" id="email" size="40" pattern="^[a-zA-Z1-9-.]+@[a-zA-Z1-9-]+\.[a-zA-Z]{2,6}$" required><br><br>
      <input type="submit" id="reinitialise_bouton_envoyer" name="valider-reset" value="Réinitialiser" title="Réinitialiser le mot de passe"><br><br>
    </form>
  </div>
</main>
</body>

<?php } else { ?>

<body>
    <main class="message__mdp">
      <div id="message_affiche">
        <form class="message_mdp">
          <button class="nav__btn-retour" onclick="history.back();" title="Revenir à la page précédente">Retour</button>
          <h1>Réinitialiser mon mot de passe</h1>
            <p>
              Un email muni d'un lien permettant de réinitialiser votre mot de passe vous a été envoyé.
            </p>
            <span id="message_mdp_bouton_accueil"><a style="text-decoration:none;" href="../landing.php">Revenir à l'accueil</a></span>
        </form>
      </div>
    </main>
</body>

<?php } ?>

</html>
