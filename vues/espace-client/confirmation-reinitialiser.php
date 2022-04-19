<?php
//Page de confirmation de l'envoie d'un email pour réinitialiser le mot de passe
error_reporting(E_ALL);

require_once '../../composants/html_head.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <?php htmlHead('Avocaba : Se connecter'); ?>
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
</html>
