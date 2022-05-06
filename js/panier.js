/* Gestion du panier (front-end) */

// Mettre à jour la quantité d'un article lorsque l'utilisateur saisit une quantité dans un des champs prévus à cet effet

let qteInputElts = document.getElementsByClassName('panier__input-qte');

for (let i = 0; i < qteInputElts.length; i++) {
  qteInputElts[i].addEventListener('change', (e) => {
    if ( e.target.value >= 0 && e.target.value <= 5 && e.target.value !== '' ) {
      // La valeur saisie est valide, l'utilisateur est redirigé
      document.location.href = '/avocaba/traitements/panier.main.php?actionPanier=set&id=' + e.target.dataset.id + '&qte=' + e.target.value;
    } else {
      // La saisie n'est pas valide, la quantité courante est restaurée
      e.target.value = e.target.dataset.qte;
    }
  })
}