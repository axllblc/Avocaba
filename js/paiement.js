/* Aide à la saisie et vérification des informations de paiement saisies par l'utilisateur */

// Éléments de la page

// Champs du formulaire

const nomElt        = document.getElementById("nom");
const numeroElt     = document.getElementById("no");
const expirationElt = document.getElementById("cardExpiration");
const codeElt       = document.getElementById("cvv");


// Champ du nom du porteur

nomElt.addEventListener("input", () => {
  // Formatage : Le texte entré est mis en majuscules
  nomElt.value = nomElt.value.toUpperCase();
})


// Champ du numéro de carte

numeroElt.addEventListener("input", () => {
  // Formatage du numéro de carte : 4 blocs de 4 chiffres séparés par des espaces
  numeroElt.value = numeroElt.value
    .replace(/\D/g, "")                 // Suppression des caractères non autorisés (≠ chiffres)
    .slice(0, 16)                       // Suppression des caractères en trop
    .replace(/(\d{4})(?=\d)/g, "$1 ");  // Ajout d'espaces entre chaque bloc de 4 chiffres

  // Vérification du numéro de carte : il ne peut pas commencer par 0.
  if ( numeroElt.value[0] === "0" ) {
    // Un message est affiché
    numeroElt.setCustomValidity("Le numéro saisi est invalide : il ne doit pas commencer par 0.")
  } else {
    numeroElt.setCustomValidity("");    // Le message est supprimé
  }
})


// Champ de la date d'expiration

expirationElt.addEventListener("input", () => {
  // On empêche la saisie de caractères non autorisés (≠ chiffres) et on supprime les caractères en trop
  expirationElt.value = expirationElt.value.replace(/\D/g, "").slice(0,4);

  // Vérification de la date d'expiration

  const dateExpiration = expirationElt.value;

  if (dateExpiration.length === 4) {
    const mois = + dateExpiration.slice(0, 2);
    const annee = + dateExpiration.slice(2, 4);

    const date = new Date();
    const moisCourant = date.getMonth() + 1;
    const anneeCourante = + date.getFullYear().toString().slice(2, 4);

    // Un message est affiché lorsque la saisie est incorrecte.
    if ( mois === 0 || mois > 12 ) {
      // Si le mois est incorrect
      expirationElt.setCustomValidity("Le mois saisi est invalide. Il doit être compris entre 1 et 12.");
    } else if ( (annee < anneeCourante) || (annee === anneeCourante && mois < moisCourant) ) {
      // Si l'année est passée
      expirationElt.setCustomValidity("La date saisie est dépassée.");
    } else {
      // Si la date saisie est correcte
      expirationElt.setCustomValidity("");    // Suppression du message
    }

  } else {
    expirationElt.setCustomValidity("");        // Affichage du message par défaut du navigateur
  }
})


// Champ du code de sécurité

codeElt.addEventListener("input", () => {
  // On empêche la saisie de caractères non autorisés (≠ chiffres) et on supprime les caractères en trop
  codeElt.value = codeElt.value.replace(/\D/g, "").slice(0, 4);
})
