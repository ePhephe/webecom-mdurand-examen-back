// On déclare les éléments dont on va avoir besoin dans nos traitements
const btnAnnuler = document.getElementById(`btn-cancel`);

// On ajouter un event listener pour rediriger vers la liste des produits si on annule
btnAnnuler.addEventListener(`click`,(e)=>{
  history.back();
});
