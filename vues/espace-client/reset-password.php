<?php

/*  Page de connexion (client) (login page) */

// TODO: Gérer les cas où l'utilisateur ne souhaite pas que l'on "se souvienne" de lui

error_reporting(E_ALL);
require_once '../../composants/html_head.php';


/********************
 * Script principal *
 ********************/
if(isset($_POST['email'])){
  if(preg_match('/^[a-zA-Z1-9-\.]+@[a-zA-Z1-9-]+\.[a-zA-Z]{2,6}$/', $_POST['email'])){
    header('Location: confirmation-reinitialiser.php');
  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php htmlHead('Avocaba : Se connecter'); ?>

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
          <input type="text" name="email" id="email" size="40" pattern="^[a-zA-Z1-9-\.]+@[a-zA-Z1-9-]+\.[a-zA-Z]{2,6}$" required><br><br>
        <input type="submit" id="reinitialise_bouton_envoyer" name="valider-reset" value="Reinitialiser" title="reinitialiser le mot de passe"><br><br>

    </form>
  </div>
</main>
</body>
</html>
