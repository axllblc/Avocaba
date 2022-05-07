<?php

/* Section des données personnelles l'espace client */

// TODO: Finir l'aspect dynamique

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/modifier-client.php';



/********************
 * Script principal *
 ********************/

if ( isset($_POST['aRemplir']) ) {
  $edit = true;
} elseif ( isset($_POST['rempli']) ) {
  $edit = false;
  $result = modifierClient(
      $_SESSION['Client']['IdClient'],
      $_SESSION['Client']['Email'],
      $_POST['motdepasseActuel'],
      $_POST['nom'],
      $_POST['prenom'],
      $_POST['email'],
      $_POST['motdepasse1'],
      $_POST['motdepasse2']
  );
  if ($result) {
    // Si la modification a eu lieu, on met à jour les informations de session
    $_SESSION['Client']['Email'] = $_POST['email'];
    $_SESSION['Client']['Nom'] = $_POST['nom'];
    $_SESSION['Client']['Prenom'] = $_POST['prenom'];

    header("Location: account.php?btClient=infos");
  }
  else {
    $message = "Erreur dans la saisie, veuillez réessayer";
  }
}
else{
  $edit = false;
}



/***********
 * Contenu *
 ***********/
?>

<div class="client__affichage-infos">
  <h2>Vos informations personnelles</h2>
  <form class="client__data" action="?btClient=infos" method="POST">
    <label for="prenom">
      Prénom
      <input <?php if(!$edit) echo 'disabled="disabled"'; ?> type="text" name="prenom" id="prenom" minlength="2" size="40" pattern="[a-zA-Z -]{2,30}" value="<?php if(isset($_SESSION['Client']['Prenom'])) echo $_SESSION['Client']['Prenom']; ?>">
    </label>
    <label for="nom">
      Nom
      <input <?php if(!$edit) echo 'disabled="disabled"'; ?> type="text" name="nom" id="nom" minlength="2" size="40" pattern="[a-zA-Z -]{2,30}" value="<?php if(isset($_SESSION['Client']['Nom'])) echo $_SESSION['Client']['Nom']; ?>">
    </label>
    <label for="email">
      Adresse email
      <input <?php if(!$edit) echo 'disabled="disabled"'; ?> type="text" size="50" name="email" id="email"  pattern="^[a-zA-Z1-9-\.]+@[a-zA-Z1-9-]+\.[a-zA-Z]{2,6}$" value="<?php if(isset($_SESSION['Client']['Email'])) echo $_SESSION['Client']['Email']; ?>">
    </label>
    <label for="motdepasse1">
      <?php echo $edit ? 'Nouveau mot de passe' : 'Mot de passe'; ?>
      <input <?php if(!$edit) echo 'disabled="disabled"'; ?> type="password" size="30"  minlength="8" maxlength="16" name="motdepasse1" id="motdepasse1"  pattern="([0-9a-zA-Z._#-]){8,16}" value="<?php if(!$edit) echo "*********"; ?>">
    </label>

    <?php if($edit) echo '
    <label for="motdepasse2">
      Nouveau mot de passe
      <input type="password" size="30" minlength="8" maxlength="16" name="motdepasse2" id="motdepasse2"  pattern="([0-9a-zA-Z._#-]){8,16}" value="">
    </label>
    <label for="motdepasseActuel">
      <strong>Pour modifier les informations, veuillez saisir le mot de passe actuel</strong>
      <input type="password" size="30" minlength="8" maxlength="16" name="motdepasseActuel" id="motdepasseActuel"  pattern="([0-9a-zA-Z._#-]){8,16}" value="" required>
    </label>
    ';
    if(isset($message)) echo "<p><strong>$message</strong></p>";?>

    <input type="submit" value="Modifier mes informations" name=" <?php if($edit) echo 'rempli'; else echo 'aRemplir'; ?>">

  </form>
</div>
