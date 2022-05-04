<?php

/* 🔒 Page d'inscription (client) (sign-up page) */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/signin.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/verifier-client.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/inscription-client.php';


/********************
 * Script principal *
 ********************/

// Inscription client : Réception de données

if ( !empty($_POST['email']) and !empty($_POST['motdepasse']) and !empty($_POST['nom']) and !empty($_POST['prenom']) and !empty($_POST['accepterCondi'])) {
  $email = $_POST['email'];
  $motdepasse = $_POST['motdepasse'];

  $client = verifierClient($_POST['email'], $_POST['motdepasse']);

  $inscrire = inscrireClient($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['motdepasse']);

  if ($inscrire) {
    // Si l'inscription est un succès, on peut établir la mise en session et le rediriger vers l'espace client
    $client = verifierClient($email, $motdepasse);

    sessionClient($client);
  }
  else{
    //Message à afficher en cas d'adresse e-mail déjà utilisée
    $message = "cette adresse email est déjà utilisée, veuillez réessayer";
  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php htmlHead('S\'inscrire – Avocaba'); ?>

<body>
  <main class="inscription">
    <div id="inscription_formulaire">
      <form class="inscription_inscrire" action="signup.php" method="post">
        <button class="nav__btn-retour" onclick="history.back();" title="Revenir à la page précédente">Retour</button>
        <h1 id="inscription__titre">Créer votre compte</h1>
        <label for="prenom">Prénom</label><br>
        <input type="text" name="prenom" id="prenom" minlength="2" size="40" pattern="[a-zA-Z -]{2,30}" required><br>
        <label for="nom">Nom</label><br>
        <input type="text" name="nom" id="nom" minlength="2" size="40" pattern="[a-zA-Z -]{2,30}" required><br>
        <label for="email">Adresse email</label><br>
        <input type="text" name="email" id="email"  pattern="^[a-zA-Z1-9-\.]+@[a-zA-Z1-9-]+\.[a-zA-Z]{2,6}$" required><br>
        <label for="motdepasse">Mot de passe</label><br>
        <input type="password" name="motdepasse" id="motdepasse" minlength="8" maxlength="16" pattern="([0-9a-zA-Z._#-]){8,16}" required><br>
        <input type="checkbox" name="accepterCondi" id="check1" required>
        <label for="check1">En créant votre compte, vous acceptez nos conditions d'utilisation et nos conditions de vente.</label><br>
        <input type="checkbox" name="accepterComm" id="check2">
        <label for="check2">Vous acceptez de recevoir les communications commerciales et bons plans par email de la part d'Avocaba. <span id="inscription_facultatif">(facultatif)</span></label><br><br>
        <input type="submit" id="inscription_bouton_inscrire" name="creercompte" value="Créer mon compte" title="Cliquez ici pour valider et créer votre compte"><br><br>
        <hr>
        <?php if (isset($message)) echo "<strong>$message</strong>"; // Si l'utilisateur a déjà rentré de mauvaises infos, on lui indique qu'il doit réessayer?>
        <p>Vous avez déjà un compte ?</p>
        <span id="inscription_bouton_connexion"><a style="text-decoration:none;" href="signin.php" title="Vous avez déjà un compte, cliquez ici pour vous connecter">Se connecter</a></span>
      </form>
    </div>
  </main>
</body>
</html>
