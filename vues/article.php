<?php

/* 📄 Description d'un produit */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/fournisseur.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/magasin.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/articles.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/ville.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/misc.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';


/*************
 * Fonctions *
 *************/

/********************
 * Script principal *
 ********************/

// Récupération des informations liées à l'article
if (isset($_GET['IdArticle'])) {
  // $a est l'article qui sera affiché
  $a = rechercherArticle($_GET['IdArticle'], "idArticle");
  if($a == null){
    // Si l'article n'existe pas, on retourne au magasin
    header('Location: /avocaba/vues/magasin.php');
  }
  $a = $a[0]; // Il n'y a qu'un seul article
  // $f est le producteur/fournisseur de l'article
  $f = new Fournisseur($a['SiretProducteur']);

  // $v est la ville du fournisseur
  $v = ville($f->getIdVille());

  session_start();

  // On récupère le magasin $m
  $m = rechercherMagasin($_SESSION['Depot']['IdDepot'], true)[0];

  // On vérifie si l'article est disponible dans le magasin/dépot
  if( count(rechercherArticle($_GET['IdArticle'], "idArticle", $_SESSION['Depot']['IdDepot']))>0 ){
    $dispo = true;
  }
  else{
    $dispo = false;
  }
} else {
  // Si aucun article renseigné, on redirige vers le magasin d'où provient le client
  header('Location: /avocaba/vues/magasin.php');
}

?>

<!DOCTYPE html>

<html lang="fr">

<?php htmlHead($a['Nom'] . ' – Avocaba'); ?>

<body>

  <?php htmlHeader($a['Nom']); ?>
  <nav>
    <button class="nav__btn-retour" onclick="history.back();" title="Revenir à la page précédente">Retour</button>
    <div class="nav__sep"><!--Séparateur--></div>
    <a href="/avocaba/vues/magasin.php" title="Accueil du magasin <?php echo $m['Nom']; ?>"><?php echo $m['Nom']; ?></a>
    <div class="nav__sep"><!--Séparateur--></div>
    <a href="<?php echo '/avocaba/vues/recherche.php?rayon=' . $a['IdRayon']; ?>" title="Rayon <?php echo $a['NomRayon']; ?>"> <?php echo $a['NomRayon']; ?></a>
  </nav>
  <div class="messageInfo">
    <?php if (!$dispo) echo "L'article n'est pas disponible dans votre magasin."; ?>
  </div>

  <main class="details-produit">
    <!-- Partie supérieure -->
    <div class="details-produit__haut">
      <div class="details-produit__infos">

        <div class="details-produit__groupe-nom">
          <h1 class="details-produit__nom"><?php echo $a['Nom']; ?></h1>
          <div class="details-produit__contenance"><?php echo $a['Unite']; ?></div>
          <a class="details-produit__nom-prod"><?php echo $f->getNom(); ?></a>
        </div>


        <div class="details-produit__prix">
          <div class="prix-net"><?php echo $a['Prix']; ?> €</div>
          <div class="prix-s"><?php echo $a['PrixRelatif']; ?> €/kg ou L</div>
        </div>

        <div class="details-produit__btn-panier">
          <button>Ajouter au cabas</button>
          <!--Améliorations à venir-->
        </div>

        <label class="details-produit__btn-favori">
          <span class="material-icons">star_border</span>
          <button>Ajouter aux favoris</button>
        </label>
      </div>

      <div class="details-produit__carrousel">
        <!--Images relatives au produit-->
        <img src="<?php echo $a['PhotoVignette']; ?>" alt="<?php echo $a['Nom']; ?>"/>
      </div>
    </div>
    <!-- Fin de la partie supérieure -->

    <div class="details-produit__description">
      <h2>Description du produit</h2>
      <p><?php echo lineBreakChange($a['Description']); ?></p>
    </div>

    <h2>Le producteur</h2>
    <div class="details-produit__producteur">
      <img class="details-produit__img-producteur" src="<?php echo $f->getPhotoProfil(); ?>" alt="Image du producteur">
      <div class="details-produit__infos-producteur">
        <h3><?php echo $f->getNom();?></h3>
        <address><?php echo $f->getAdresse() . ", " . $v['CodePos'] . " " . $v['Nom']; ?></address>
        <p><?php echo lineBreakChange($f->getDescription(), true); ?></p>
        <a href="/avocaba/vues/fournisseur.php?siret=<?php echo $f->getSiret(); ?>">Lire la suite</a>
      </div>
    </div>

    <div class="details-produit__produits-similaires">
      <h2>Découvrez aussi</h2>
      <p>Les produits similaires apparaîtront ici</p>
    </div>

    <a class="details-produit__rayon" href="<?php echo '/avocaba/vues/recherche.php?rayon=' . $a['IdRayon']; ?>">Explorer le rayon <?php echo $a['NomRayon']; ?></a>
  </main>

  <?php footer(); ?>
</body>
</html>
