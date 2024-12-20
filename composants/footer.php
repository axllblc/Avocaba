<?php

/* ⬇️ Pied de page */

error_reporting(0);

/**
 * Afficher le pied-de-page du site
 * @return void
 */
function footer (): void { ?>
  <footer class="pied-de-page">
    <div class="pied-de-page__avocaba">Avocaba</div>
    <div class="pied-de-page__a-propos">
      <div class="pied-de-page__section-header">À propos</div>
      <ul class="pied-de-page__liste">
        <li class="pied-de-page__item">
          <a href="#">Nos engagements</a>
        </li>
        <li class="pied-de-page__item">
          <a href="#">Nos dépôts</a>
        </li>
        <li class="pied-de-page__item">
          <a href="#">Nos fournisseurs</a>
        </li>
        <li class="pied-de-page__item">
          <a href="#">Plan du site</a>
        </li>
      </ul>
    </div>
    <div class="pied-de-page__services">
      <div class="pied-de-page__section-header">Services</div>
      <ul class="pied-de-page__liste">
        <li class="pied-de-page__item">
          <a href="/avocaba/vues/espace-client/account.php">Espace Client</a>
        </li>
        <li class="pied-de-page__item">
          <a href="#">Espace Pro</a>
        </li>
        <li class="pied-de-page__item">
          <a href="#">Nous contacter</a>
        </li>
      </ul>
    </div>
    <div class="pied-de-page__newsletter">
      <div class="pied-de-page__section-header">Notre newsletter</div>
      <p>Inscrivez-vous à notre newsletter pour recevoir l’actualité des produits de votre région et diverses offres.</p>
      <?php // TODO modifier l'URL de la page de traitement ?>
      <form class="pied-de-page__newsletter-form" action="#">
        <input type="email" name="email_newsletter" id="email-newsletter" placeholder="Email" autocomplete="email"
               title="Entrez votre adresse e-mail pour vous abonner à la newsletter">
        <input type="submit" value="Je m'abonne">
      </form>
      <p>Vous pouvez vous désabonner de la newsletter à tout moment. Consultez notre <a href="#">politique de confidentialité</a> pour en savoir plus sur notre utilisation des données personnelles.</p>
    </div>
    <ul class="pied-de-page__juridique">
      <li class="pied-de-page__item">
        <a href="#">Mentions légales</a>
      </li>
      <li class="pied-de-page__item">
        <a href="#">Conditions d'utilisation</a>
      </li>
      <li class="pied-de-page__item">
        <a href="#">Politique de confidentialité</a>
      </li>
      <li class="pied-de-page__item">
        <a href="#">Conditions générales de vente</a>
      </li>
      <li class="pied-de-page__item">
        © <?= getdate()['year'] ?>, Avocaba
      </li>
    </ul>
  </footer>
<?php }
