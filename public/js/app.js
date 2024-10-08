// On déclare les éléments dont on va avoir besoin dans nos traitements
let divModal = document.querySelector(`.modal`);
let divMessageModal = document.querySelector(`.modal div`);

/**
 * Affiche une erreur sur le champ dont l'id est passé en paramètre
 * @param {string} id - id unique de l'élément dans le DOM
 * @param {string} messageErreur - Message d'erreur à afficher
 */
function afficheErreur(id,messageErreur){
    //On récupère l'élément et son élément message d'erreur
    let element = document.getElementById(id);
    let message = document.getElementById(`error-` + id);

    //On enlève la classe d'erreur sur l'input
    element.classList.add(`input-error`);
    //On insère le message d'erreur et on l'affiche en lui retirant la classe display none
    message.innerHTML += messageErreur + `<br>`;
    message.classList.remove(`display-none`);

}
/**
 * Enlève les erreurs sur le champ dont l'id est passé en paramètre
 * @param {string} id - id unique de l'élément dans le DOM
 */
function enleveErreur(id){
    //On récupère l'élément et son élément message d'erreur
    let element = document.getElementById(id);
    let message = document.getElementById(`error-` + id);

    //On enlève la classe d'erreur sur l'input
    element.classList.remove(`input-error`);
    //On enlève le message d'erreur et on le masque en lui remettant la classe display none
    message.innerHTML = ``;
    message.classList.add(`display-none`);
}

/**
 * Masque le message de la modal
 */
function masqueModal(){
    //On masque les messages avec le display none (classe CSS d-none)
    divModal.classList.add(`display-none`);
    divMessageModal.innerHTML = ``;
}

/**
 * Affiche le message de la modal
 */
function afficheModal(message,succes){
    //Selon le succès ou l'échec, on place des classes CSS différentes
    if(succes===true) {
        divModal.classList.add(`succes`);
        divModal.classList.remove(`erreur`);
    }
    else {
        divModal.classList.add(`erreur`);
        divModal.classList.remove(`succes`);
    }
    
    //On affecte le message au contenu de la div et on enlève le display none
    divModal.classList.remove(`display-none`);
    divMessageModal.innerHTML = message;
    //On laisse 5sec avant de remasquer le message
    setTimeout(masqueModal,5000);
}

