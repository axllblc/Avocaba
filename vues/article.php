<?php

/* 📄 Description d'un produit */

error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/fournisseur.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/magasin.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/articles.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/panier.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/ville.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/traitements/misc.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_head.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/html_qte-article.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/footer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/error.php';

initialiserPanier();



// ********************
// * Script principal *
// ********************

// Récupération des informations liées à l'article
if ( isset($_GET['IdArticle']) ) {
  // $a est l'article qui sera affiché
  $a = rechercherArticle($_GET['IdArticle'], "idArticle");
  if ($a == null) {
    // Si l'article n'existe pas, on retourne au magasin
    header('Location: /avocaba/vues/magasin.php');
  }
  $a = $a[0]; // Il n'y a qu'un seul article

  // $f est le producteur/fournisseur de l'article
  try {
    $f = new Fournisseur($a['SiretProducteur']);
  } catch (Exception $e) {
    error(500);
  }

  // $v est la ville du fournisseur
  $v = ville($f->getIdVille());

  // $dispo indique si l'article est peut être ajouté au panier du client
  $dispo = false;

  // Photo de profil du fournisseur
  @$photoProfil = $f->getPhotoProfil();
  // Adresse du fournisseur
  @$adresse = $f->getAdresse();

  if(isset($_SESSION['Depot']['IdDepot'])){
    // On récupère le nom du magasin $magasin
    $magasin = rechercherMagasin($_SESSION['Depot']['IdDepot'], true)[0]['Nom'];

    // On vérifie si l'article est disponible dans le dépôt
    if( count(rechercherArticle($_GET['IdArticle'], "idArticle", $_SESSION['Depot']['IdDepot']))>0 ){
      $dispo = true;
    }
  }
} else {
  // Si aucun article renseigné, on redirige vers le magasin d'où provient le client
  header('Location: /avocaba/vues/magasin.php');
  exit;
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

    <?php if(isset($magasin)) echo '
    <a href="/avocaba/vues/magasin.php" title="Accueil du magasin ' . $magasin . '">' . $magasin . '</a>
    <div class="nav__sep"><!--Séparateur--></div>
    '; ?>

    <a href="<?= '/avocaba/vues/recherche.php?rayon=' . $a['IdRayon'] ?>" title="Rayon <?= $a['NomRayon'] ?>">
      <?= $a['NomRayon'] ?>
    </a>
  </nav>

  <main class="details-produit">
    <!-- Partie supérieure -->
    <div class="details-produit__haut">
      <div class="details-produit__infos">

        <div class="details-produit__groupe-nom">
          <h1 class="details-produit__nom"><?= $a['Nom'] ?></h1>
          <div class="details-produit__contenance"><?= $a['Unite'] ?></div>
          <a class="details-produit__nom-prod"
             href="<?= '/avocaba/vues/fournisseur.php?siret=' . $f->getSiret() ?>">
            <?= $f->getNom() ?>
          </a>
          <?= !$dispo ? '<div>Cet article n\'est pas disponible dans votre magasin.</div>' : '' ?>
        </div>


        <div class="details-produit__prix">
          <div class="prix-net" title="Prix du produit"><?= $a['Prix'] ?>&nbsp;€</div>
          <div class="prix-s" title="Prix relatif (au kg ou au L)"><?= $a['PrixRelatif'] ?>&nbsp;€/kg ou L</div>
        </div>

        <div class="details-produit__btn-panier">
          <?php if ($dispo)
            htmlQteArticle($a['IdArticle'], getQteArticle($a['IdArticle']), $a['Nom']);
          ?>
        </div>

        <label class="details-produit__btn-favori">
          <span class="material-icons">star_border</span>
          <button>Ajouter aux favoris</button>
        </label>
      </div>

      <div class="details-produit__carrousel">
        <!--Images relatives au produit-->
        <img src="<?= !empty($a['PhotoVignette']) ? $a['PhotoVignette'] : '/avocaba/img/article-placeholder.png' ?>"
             alt="<?= $a['Nom'] ?>"/>
      </div>
    </div>
    <!-- Fin de la partie supérieure -->

    <div class="details-produit__description">
      <h2>Description du produit</h2>
      <p><?= lineBreakChange($a['Description']) ?></p>
    </div>

    <h2>Le producteur</h2>
    <div class="details-produit__producteur">
      <img class="details-produit__img-producteur"
           src="<?= !empty($photoProfil) ? $photoProfil : '/avocaba/img/farmer.png' ?>"
           alt="Image du producteur">
      <div class="details-produit__infos-producteur">
        <h3><?= $f->getNom() ?></h3>
        <address>
          <?= ( !empty($adresse) ? $adresse . ', ' : '' ) . $v['CodePos'] . ' ' . $v['Nom'] ?>
        </address>
        <p><?= lineBreakChange($f->getDescription(), true) ?></p>
        <a href="<?= '/avocaba/vues/fournisseur.php?siret=' . $f->getSiret() ?>">Lire la suite</a>
      </div>
    </div>

    <div class="details-produit__produits-similaires">
      <h2>Découvrez aussi</h2>
      <p>Les produits similaires apparaîtront ici</p>
    </div>

    <a class="details-produit__rayon" href="<?= '/avocaba/vues/recherche.php?rayon=' . $a['IdRayon'] ?>">
      Explorer le rayon <?= $a['NomRayon'] ?>
    </a>
  </main>

  <?php footer(); ?>

  <script src="/avocaba/js/panier.js"></script>
</body>
</html>
