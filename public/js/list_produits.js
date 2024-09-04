// On déclare les éléments dont on va avoir besoin dans nos traitements
const actionSupprimerProduit = document.querySelectorAll(`.action .btn_supprimer`);

// On ajoute le listener sur les boutons supprimer
actionSupprimerProduit.forEach(suppr => {
  // On ajoute un listener au click
  suppr.addEventListener(`click`,(e)=>{
    // On arrête le fonctionnement par défaut
    e.preventDefault();
    // Appel de l'URL avec fetch en méthode GET
    fetch(e.target.href)
    .then(response  => response.json())
    .then(data  => {
        if(data.succes === true) {
          // Si tout s'est bien passé, on recharge directement la page
          location.reload(true); 
        }
        else {
          // Sinon on affiche le retour dans une modal
          afficheModal(data.message,data.succes);
        }
        //console.log(`Success:`, data );
    })
    .catch((error) => {
        console.error(`Error:`, error);
    });
  });
});

