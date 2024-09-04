// On déclare les éléments dont on va avoir besoin dans nos traitements
const actionsCommande = document.querySelectorAll(`#container-commandes a.action`);

// On met en place les listeners sur les actions
actionsCommande.forEach(action => {
  action.addEventListener(`click`,(e)=>{
    e.preventDefault();
    actionCommande(e);
  });
});

/**
 * Lance l'action à effectuer en API Fetch
 * @param {event} e Evenement déclencheur
 */
function actionCommande(e) {
  // Appel de l'URL avec fetch en méthode GET
  fetch(e.target.href)
  .then(response  => response.json())
  .then(data  => {
      //On ajoute les éléments au formulaire si tout s'est bien passé
      if(data.succes === true) {
        // On met à jour la ligne de la commande
        let ligneCommande = majCommande(data);
        // On remet en place les listeners sur les actions
        let actions = ligneCommande.querySelectorAll(`a.action`);
        actions.forEach(action => {
          action.addEventListener(`click`,(e)=>{
            e.preventDefault();
            actionCommande(e);
          });
        });
      }
      // On affiche le message de retour
      afficheModal(data.message,data.succes);
      //console.log(`Success:`, data );
  })
  .catch((error) => {
      console.error(`Error:`, error);
  });
}

/**
 * Met à jour la ligne de la commande
 * @param {object} data Données retournées par l'API Fetch
 * @returns Element HTML
 */
function majCommande(data) {
  // On récupère la ligne de la commande sélectionnée
  let ligneCommande = document.querySelector(`[data-id-commande="${data.commande.id}"]`);
  // On construit le template HTML
  let templateHTML = `
            <tr data-id-commande="${data.commande.id}">
                <td>${data.commande.c_num_cde.value}</td>
                <td>${data.commande.c_ref_cde.value}</td>
                <td>
                  ${data.commande.c_type_service.list_libelle}
                </td>
                <td>${data.commande.c_statut.list_libelle}</td>
                <td>${data.commande.c_datetime_livraison.affichage}</td>
                <td>${data.commande.total.toFixed(2)} €</td>
                <td>
                    <a href="afficher_detail_commande.php?idcommande=${data.commande.id}"><img src="public/images/icons/info.png" alt ="Icone de détail"></a>
          `;
  // On affiche les bons boutons en fonction des permissions 
  if (data.permission_creer === true && data.commande.c_statut.value === "C") {
    templateHTML += `<a href="afficher_commande_construction.php?idcommande=${data.commande.id}"><img src="public/images/icons/add-cart.png" alt ="Icone de construction de la commande"></a>`;
  }
  if (data.permission_preparer === true && data.commande.c_statut.value === "AP") {
    templateHTML += `<a class="action" href="preparer_commande.php?idcommande=${data.commande.id}"><img src="public/images/icons/ready.png" alt ="Icone de passage au statut Prête"></a>`;
  }
  if (data.permission_livrer === true && data.commande.c_statut.value === "P") {
    templateHTML += `<a class="action" href="livrer_commande.php?idcommande=${data.commande.id}"><img src="public/images/icons/delivery.png" alt ="Icone de passage au statut Livrée"></a>`;
  }
  if (data.permission_supprimer === true && data.commande.c_statut.value != "S") {
    templateHTML += `<a class="action" href="supprimer_commande.php?idcommande=${data.commande.id}"><img src="public/images/icons/trash.png" alt ="Icone de suppression"></a>`;
  }
  // On termine le template
  ligneCommande.innerHTML = templateHTML + `
                </td>
            </tr>
          `;

  return ligneCommande;
}