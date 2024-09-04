// On déclare les éléments dont on va avoir besoin dans nos traitements
const divInputChevalet = document.getElementById(`div_c_num_chevalet`);
const inputService = document.getElementById(`c_type_service`);

// On ajoute un eventListener au changement sur le type de service
inputService.addEventListener(`change`,(e)=>{
  // Dans le cas où on est A emporter
  if(inputService.value === "AE") {
    // On masque l'input du numéro de chevalet
    divInputChevalet.classList.add(`display-none`);
  }
  else {
    // Sinon on l'affiche
    divInputChevalet.classList.remove(`display-none`);
  }
});