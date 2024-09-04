// On déclare les éléments dont on va avoir besoin dans nos traitements
const actionsCommande = document.querySelectorAll(`a.action`);

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
      //Si tout s'est bien passé
      if(data.succes === true) {
        // On recharge la page
        window.location.reload();
      }
      // On affiche le message de retour
      afficheModal(data.message,data.succes);
  })
  .catch((error) => {
      console.error(`Error:`, error);
  });
}