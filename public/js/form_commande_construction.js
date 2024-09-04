/**
 * On déclare les éléments dont on va avoir besoin dans nos traitements
 */
// Elements du DOM
const inputCategorie = document.getElementById(`ref_categorie`);
const divListProduit = document.getElementById(`list_produits`);
const divContainerModal = document.getElementById(`container_modal_choix`);
const divModalChoix = document.getElementById(`modal_choix`);
const buttonFinaliser = document.getElementById(`btn_finaliser`);
const buttonRetour = document.getElementById(`btn_retour`);

// Variables à portée globale sur le fichier
let contenuCommande = (sessionStorage.getItem("commande"+idCommande)===null)?[]:JSON.parse(sessionStorage.getItem("commande"+idCommande));

// On ajoute un event listener de retour
buttonRetour.addEventListener(`click`,(e)=>{
  // On retourne à la page précédente dans l'historique du navigateur
  history.back();
});

// On ajoute un listener au changement de catégorie dans la liste déroulante
inputCategorie.addEventListener(`change`,(e)=>{
  // On met à jour la valeur de la catégorie
  idCategorie = parseInt(inputCategorie.value);
  //On charge les produits
  loadProduits();
});

// On ajoute un listener sur le bouton finaliser avant d'envoyer la commande
buttonFinaliser.addEventListener(`click`,(e)=>{
  // On arrête le fonctionnement par défaut
  e.preventDefault();

  // On contrôle que les produits ne sont pas vides
  if(contenuCommande.length > 0) {
    // On envoie une requête POST avec fetch
    fetch(e.target.href, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({produits: contenuCommande, idcommande: idCommande})
    })
    .then(response => response.json())
    .then(data => {
      // Si tout s'est bien passé
      if(data.succes === true) {
        // On supprime la commande du sessionstorage
        sessionStorage.removeItem("commande"+idCommande);
        // On redirige vers l'accueil
        window.location = "afficher_accueil.php";
      }
      //console.log(`Success:`, data );
    })
    .catch((error) => {
      console.error(`Error:`, error);
    });
  }
});

/**
 * Charge la liste des produits correspondant à la catégorie en cours
 */
function loadProduits(){
  // Appel de l'URL avec fetch en méthode GET
  fetch(`lister_produits_json.php?idcategorie=`+idCategorie)
  .then(response  => response.json())
  .then(data  => {
      //On ajoute les éléments au formulaire si tout s'est bien passé
      if(data.succes === true) {
        // On vide notre liste actuelle de produit
        divListProduit.innerHTML = ``;
        // On parcourt les articles
        data.arrayProduits.forEach(produit => {
          // Pour chaque produit on crée un élément article
          let articleProduit = document.createElement(`article`);
          // On lui donne les classes CSS nécessaires
          articleProduit.className = `large-4-12 flex gap direction-column justify-between`;
          // On ajoute le contenu HTML
          articleProduit.innerHTML += `
            <div class="large-12-12">
              <img class="responsive" src="${produit.p_ref_piecejointe_image.object.pj_chemin.value+produit.p_ref_piecejointe_image.object.pj_nom_fichier.value}" alt="${produit.p_libelle.value}">
            </div>
            ${produit.p_libelle.value}<br>
            ${produit.p_prix.value} €
          `;
          // Au click sur l'article on affichera une popin pour l'ajouter
          articleProduit.addEventListener(`click`,(e)=>{
            // Si l'idCategorie est le menu (1) sinon produit
            if(idCategorie === 1) {
              loadChoix("menu",produit);
            }
            else {
              loadChoix("produit",produit);
            }
          });
          // On ajoute l'article à la liste des produits
          divListProduit.appendChild(articleProduit);
        });
      }
      //console.log(`Success:`, data );
  })
  .catch((error) => {
      console.error(`Error:`, error);
  });
}

/**
 * Affichage de la popin pour ajouter le produit
 * @param {string} type Type menu ou produit
 * @param {object} produit Objet du produit menu
 */
function loadChoix(type,produit) {
  // On construit l'URL que l'on va appeler selon le type
  let url;
  if(type === "menu") { url = `afficher_form_menu.php?idmenu=`+produit.id; }
  else { url = `afficher_form_commande_produit.php?idproduit=`+produit.id}

  // Appel de l'URL avec fetch en méthode GET
  fetch(url)
  .then(response  => response.json())
  .then(data  => {
      //On ajoute les éléments au formulaire si tout s'est bien passé
      if(data.succes === true) {
        // On affiche la modal en enlevant le display-none
        divContainerModal.classList.remove(`display-none`);
        // On y place le fragment HTML qu'on a récupéré
        divModalChoix.innerHTML = data.fragment;

        // On récupère le formulaire et le bouton annuler
        let form = divModalChoix.querySelector(`form`);
        let buttonAnnuler = divModalChoix.querySelector(`#btn-cancel`);

        // Au clic sur Annuler on fermera la modal
        buttonAnnuler.addEventListener(`click`,fermetureModalChoix);
        // A la soumission du formulaire
        form.addEventListener(`submit`,(e)=>{
          // On arrête le fonctionnement par défaut
          e.preventDefault();
          // On ajoute le produit selon si c'est un menu ou non
          if(type === "menu") { ajouterProduit("menu",produit,form); }
          else { ajouterProduit("produit",produit,form); }
        });
      }
      //console.log(`Success:`, data );
  })
  .catch((error) => {
      console.error(`Error:`, error);
  });
}

/**
 * Ajoute un produit dans la commande
 * @param {string} type Produit ou menu
 * @param {object} produit Objet du produit à ajouter
 * @param {object} form Formulaire avec les données
 */
function ajouterProduit(type,produit,form){
  // On récupère les éléments concernant le produit en lui-même
  let quantiteProduit = (type==="menu") ? 1 : form.elements["cp_quantite"].value;
  let formatProduit = form.elements["cp_ref_format"].value;
  let selectedIndexFormat = form.elements["cp_ref_format"].selectedIndex;
  let impactPrixFormat = form.elements["cp_ref_format"].options[selectedIndexFormat].getAttribute("data-prix");
  let libelleFormat = form.elements["cp_ref_format"].options[selectedIndexFormat].text;

  // On contrôle la quantité
  const regexQte = /^(?:[1-9]|[1-2][0-9]|30)$/;
  // Si la quantité répond à la regex
  if(regexQte.test(quantiteProduit)) {
    // On construit un objet produitCommande avec les informations nécessaires
    let produitCommande = {
      id: produit.id, 
      libelle: produit.p_libelle.value,
      prix: parseFloat(produit.p_prix.value), 
      quantite: parseInt(quantiteProduit), 
      format: parseInt(formatProduit),
      prixFormat: parseFloat(impactPrixFormat),
      libelleFormat: libelleFormat
    };

    // Si c'est un menu, il faut ajouter les autres informations
    if(type==="menu"){
      // On récupère les produits concernés
      let listProduits = form.elements["cp_ref_produit[]"];
      // On ajoute un tableau de produits au produit initial
      produitCommande = {...produitCommande, produitsMenu: []};
      // On parcourts les produits qui accompagnent le menu
      listProduits.forEach(unProduit => {
        // On récupère le produit sélectrionner
        let selectedIndex = unProduit.selectedIndex;
        // On l'ajoute aux produits du menu
        produitCommande.produitsMenu.push({
          id: unProduit.value,
          libelle: unProduit.options[selectedIndex].text
        });
      });
    }

    // On ajoute le produit complet dans le contenu de la commande
    contenuCommande.push(produitCommande);
    // On stocke le nouveau contenu dans le sessionStorage
    sessionStorage.setItem("commande"+idCommande,JSON.stringify(contenuCommande))
    // On ferme la modal
    fermetureModalChoix();
    // On recharge les informations de la commande
    loadInfosCommande();
  }
  else {
    // On afficher une erreur
    enleveErreur(`cp_quantite`);
    afficheErreur(`cp_quantite`,`La quantité n'est pas correcte.`);
  }
}

/**
 * Supprime le produit du contenu de la commande
 * @param {integer} idproduit 
 */
function supprimerProduit(idproduit){
  // On filtre le contenu de la commande pour supprimer le produit avec l'ID donné
  contenuCommande = contenuCommande.filter(produit => produit.id !== idproduit);
  // On stocke le nouveau contenu
  sessionStorage.setItem("commande"+idCommande,JSON.stringify(contenuCommande));
  // On recharge les informations de la commande
  loadInfosCommande();
}

/**
 * Met à jour l'affichage des informations sur la commande
 */
function loadInfosCommande(){
  // On récupére les éléments qui affiche les informations
  let divProduitsCommande = document.getElementById(`produits_commande`);
  let divTotalCommande = document.getElementById(`total_commande`);

  // On remet à vide les produits
  divProduitsCommande.innerHTML = ``;

  // On parcourt le contenu de la commande
  let total = 0;
  // Pour chaque ligne de la commande
  contenuCommande.forEach(produit => {
    // On ajoute un div pour le produit
    divProduitsCommande.innerHTML += `
      <div>
        ${produit.quantite} x ${produit.libelle} (${produit.libelleFormat})
        <a data-idproduit="${produit.id}" id="btn_suppr_produit_${produit.id}" href=""><img src="public/images/icons/trash.png" alt="Icone de suppression"></a>
      </div>
    `;
    // Si on est dans un menu, on affiche les sous produits
    if("produitsMenu" in produit) {
      // On crée un liste pour la composition du menu
      divProduitsCommande.innerHTML += `<ul>`;
      produit.produitsMenu.forEach(eltMenu => {
        divProduitsCommande.innerHTML += `<li>${eltMenu.libelle}</li>`;
      });
      divProduitsCommande.innerHTML += `</ul>`;
    }
    divProduitsCommande.innerHTML += `</div>`;

    // On ajoute au total
    total += produit.quantite*(produit.prix+produit.prixFormat);
  });

  // On récupère les éléments des boutons supprimer à côté de chaque produit
  let arrayButtonSuppr = divProduitsCommande.querySelectorAll(`a`);
  // On les parcourt
  arrayButtonSuppr.forEach(button => {
    // On ajoute un listener au clic qui va lancer la suppression dans le contenu de la commande
    button.addEventListener(`click`,(e)=>{
      e.preventDefault();
      supprimerProduit(button.getAttribute(`data-idproduit`));
    });
  });

  // On ajoute le total de la commande
  divTotalCommande.innerText = total.toFixed(2) + ` €`;
}

/**
 * Fermeture de la modal de choix
 */
function fermetureModalChoix(){
  // On réinitialise son contenu
  divModalChoix.innerHTML = ``;
  // On la masque avec la classe display-none
  divContainerModal.classList.add(`display-none`);
}

/**
 * Au chargement de la page
 */
function loadContent(){
  // On met la bonne valeur sur l'input categorie
  inputCategorie.value = idCategorie;
  // On charge les produits
  loadProduits();
  // On charge les information de la commande
  loadInfosCommande();
}

// Lorsque la page est chargé
document.addEventListener('DOMContentLoaded', loadContent);