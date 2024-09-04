// On déclare les éléments dont on va avoir besoin dans nos traitements
const formProduit = document.getElementById(`form_produit`);
const btnAnnuler = document.getElementById(`btn-cancel`);
const divInputCategorie = document.getElementById(`div_p_ref_categorie`);
const inputCategorie = document.getElementById(`p_ref_categorie`);

// On ajouter un event listener pour rediriger vers la liste des produits si on annule
btnAnnuler.addEventListener(`click`,(e)=>{
  // On retourne à la page précédente dans l'historique du navigateur
  history.back();
});

// On ajoute un eventlistener sur la validation du formulaire
formProduit.addEventListener(`submit`,(e)=>{
  // On arrête le fonctionnement par défaut
  e.preventDefault();

  // On récupère le contenu du formulaire
  const formData = new FormData(formProduit);
  // On envoie une requête POST avec fetch
  fetch(formProduit.action, {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if(data.succes === true && data.action === "create") {
      // Si c'est un succès et qu'on est en création, on réinitialise le formulaire
      formProduit.reset();
    }
    // On affiche le message de retour
    afficheModal(data.message,data.succes);
    //console.log(`Success:`, data );
  })
  .catch((error) => {
    console.error(`Error:`, error);
  });

});

// On ajoute un eventListener au changement sur la catégorie
inputCategorie.addEventListener(`change`,(e)=>{
  // Dans le cas où on est sur la catégorie menu
  if(parseInt(inputCategorie.value) === 1) {
    // On charge l'ajout des produits au menu
    loadProduitMenu();
  }
  else {
    // Sinon on regarde si on a une div de choix du menu et on la vide
    let divChoixMenu = document.getElementById(`div_choix_menu`);
    if(divChoixMenu) {
      divChoixMenu.innerHTML = ``;
    }
  }
});

/**
 * Charge les catégories de produits pour ajouter aux possibilités du menu
 */
function loadProduitMenu() {
  // On va appeler le controller de listing des catégories
  // On construit l'URL en fonction de si on a un produit ou non
  let url = `lister_categories_menu.php`;
  if(idProduit != 0) {
    url += `?idproduit=`+idProduit;
  }

  // Appel de l'URL avec fetch en méthode GET
  fetch(url)
  .then(response  => response.json())
  .then(data  => {
      //On ajoute les éléments au formulaire si tout s'est bien passé
      if(data.succes === true) {
        // On récupère la div de choix du menu
        let divChoixMenu = document.getElementById(`div_choix_menu`);

        // Si elle n'existe pas, on la créé
        if(divChoixMenu === null) {
          divChoixMenu = document.createElement(`div`);
          // On met un id et une classe
          divChoixMenu.id = `div_choix_menu`;
          divChoixMenu.className = `div_input_form`;
        }

        // On créé jusqu'à 5 possibilités dans le menu (Ex. Accompagnement, boisson, sauce, encas, dessert)
        for (let index = 0; index < 5; index++) {
          divChoixMenu.innerHTML += `
            <div id="choix_menu_${index}">
              ${index+1}. <input type="text" name="m_libelle[]" id="m_libelle_${index}" placeholder="Libellé du choix pour le menu (ex. accompagnement, boisson...">
              `+selectCategories(data.arrayCategorie,index)+`
            </div>
          `;
        }
        // On ajoute l'élément après le choix de la catégorie
        divInputCategorie.insertAdjacentElement('afterend', divChoixMenu);

        // Si on a un produit, on va mettre à jour les valeurs
        if(idProduit != 0) {
          loadProduitMenuModif(data.arrayMenu);
        }
      }
      //console.log(`Success:`, data );
  })
  .catch((error) => {
      console.error(`Error:`, error);
  });
}

/**
 * Charge les possibilités de menu déjà saisies dans le cas d'une modfication
 * @param {arrayMenu} arrayCategorie Liste des catégories
 */
function loadProduitMenuModif(arrayMenu) {
  // On parcourt notre tableau
  arrayMenu.forEach((composition,index) => {
    // On récupère les éléments de la catégories possible et du libellé
    const inputCategorieChoix = document.getElementById(`m_ref_categorie_possible_`+index);
    const inputLibelleChoix = document.getElementById(`m_libelle_`+index);

    // On charge les valeurs connues
    inputCategorieChoix.value = composition.m_ref_categorie_possible.value;
    inputLibelleChoix.value = composition.m_libelle.value;
  });
}

/**
 * Crée la liste déroulante de choix pour les catégories
 * @param {arrayCategorie} arrayCategorie Liste des catégories
 * @param {integer} numChoix Numéro du choix dans le menu
 * @returns Template HTML
 */
function selectCategories(arrayCategorie,numChoix){
  // On initialise le template html
  let templateHTML = `<select name="m_ref_categorie_possible[]" id="m_ref_categorie_possible_${numChoix}">
    <option value="">Choisir la catégorie de produit possible pour ce choix</option>
  `;

  // On parcourt les catégories pour créé notre liste déroulante
  arrayCategorie.forEach(categorie => {
    templateHTML += `
        <option value="${categorie.id}">${categorie.ca_libelle.value}</option>
    `;
  });

  return templateHTML + `</select>`;
}

/**
 * Charge les possibilités du menu selon la catégorie du produit
 */
function loadContent(){
  // Si on a un produit, on est en modification et si la catégorie est menu
  if(idProduit != 0 && parseInt(inputCategorie.value) === 1) {
    // Dans ce cas on charge le menu existant
    loadProduitMenu();
  }
}

// Lorsque la page est chargé
document.addEventListener('DOMContentLoaded', loadContent);