<?php

/* 🔒 Page de connexion (client) (sign-in page) */

// TODO: Gérer les cas où l'utilisateur souhaite que l'on "se souvienne" de lui

error_reporting(E_ALL);

require_once '../../composants/html_head.php';
require_once '../../traitements/signin.inc.php';
require_once '../../traitements/verifier-client.php';


/********************
 * Script principal *
 ********************/

// Recherche de l'utilisateur : Réception de données

if ( !empty($_POST['email']) and !empty($_POST['motdepasse']) ) {
  // On vérifie que le client est dans la base de données
  $client = verifierClient($_POST['email'], $_POST['motdepasse']);
  if ($client) {
    // Si l'adresse e-mail est présente et que le mot de passe est correct, une session est initialisée
    sessionClient($client);
  }
  else {
    // On définit un message qui indique que les informations de connexion ne sont pas correctes
    $message = "<p>Identifiant ou mot de passe incorrect, veuillez réessayer.</p>";
  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<?php htmlHead('Se connecter – Avocaba'); ?>

<body>
  <!-- TODO : éviter d'imbriquer plusieurs éléments lorsque ce n'est pas nécessaire : ici, on a juste besoin de
              conserver l'élément <form> ; on peut supprimer ce qui est autour -->
  <main class="connexion">
    <div id="connexion_formulaire">
      <form class="connexion_connecter" action="" method="POST">
        <button class="nav__btn-retour" onclick="history.back();" title="Revenir à la page précédente">Retour</button>
        <h1 id="connexion__titre">Connexion</h1>

        <label for="email">Adresse email</label><br>
        <input type="email" size="40" name="email" id="email"
               pattern="^[a-zA-Z1-9-.]+@[a-zA-Z1-9-]+\.[a-zA-Z]{2,6}$"
               value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"
               required><br>
        <label for="motdepasse">Mot de passe</label><br>
        <input type="password" size="40" name="motdepasse" id="motdepasse" minlength="8" maxlength="16" pattern="([0-9a-zA-Z._#-]){8,16}" required><br>
        <input type="checkbox" name="sesouvenirdemoi" id="check">
        <label for="check">Se souvenir de moi</label><br><br>

        <!-- TODO : Supprimer le <span> autour de la balise <a>
                    Associer l'élément <a> à une classe partagée par tous les boutons du même type présents dans les
                    pages d'authentification (elle est à définir dans une/les feuille(s) de style).
                    Déplacer le CSS de l'attribut "style" dans le(s) fichier(s) CSS correspondant(s) -->
        <span id="connexion_bouton_mdp"><a style="text-decoration:none" href="reset-password.php" title="Cliquez ici pour réinitialiser votre mot de passe">J'ai oublié mon mot de passe</a></span><br><br>

        <!-- TODO : remplacer l'identifiant par une classe partagée par tous les boutons submit des pages d'authentification -->
        <input type="submit" id="connexion__bouton__connecter" name="se_connecter" value="Se connecter" title="Cliquez ici pour vous connecter"><br>

        <?php if (isset($message)) echo "<strong>$message</strong>";?>

        <hr>
        <p>Vous n'avez pas encore de compte ?</p>
        <!-- TODO : Même chose que pour le bouton "J'ai oublié mon mot de passe" -->
        <span id="connexion_bouton_inscription"><a style="text-decoration:none" href="signup.php" title="Cliquez ici pour créer votre compte">Créer un compte</a></span>
      </form>
    </div>
  </main>
</body>

</html>
