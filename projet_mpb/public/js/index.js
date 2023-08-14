'use strict'

import Form from './Form.js';



// const search = document.querySelector("#search");





//document.addEventListener('DOMContentLoaded', () => {
    
    
// Cacher la notification d'erreur ou de succès après 3 secondes d'affichage
setTimeout(function () {
    document.querySelector('.notif').innerHTML = ''
}, 3000);

document.addEventListener('DOMContentLoaded', () => {
// Validation formulaires


const form = new Form();
const contacts = JSON.parse(localStorage.getItem('valid-contact')) || [];
const inputs = document.querySelectorAll('input');

console.log('inputs:' + inputs);
inputs.forEach.call(inputs, input => {
    input.addEventListener('keydown', form.removeError);
});

//document.querySelector('form').addEventListener('submit', validateForm);

document.querySelector('form').addEventListener('submit', function(e) {
//function validateForm(e) {
    //form.isValid = e.form.isValid;
    
    const inputs = this.querySelectorAll('input');
    console.log('inputs:' + inputs);
    if (form.validate(inputs)) {
        const contact = form.contact;
        contacts.push(contact);
        form.post(contacts);
        // document.querySelector('form').reset();
    } else {
        form.createError();
    }
    if(!form.isValid) {
        e.preventDefault();
    }
//}
});

});

// document.querySelector('form').removeEventListener('submit', validateForm);
document.addEventListener('DOMContentLoaded', () => {
    
// Barre de recherche
document.querySelector("#search").addEventListener('keyup', searchbar);
/*
* searchbar function
*/

//let input = document.querySelector("#search");
//input.addEventListener('keyup', () => { // Ecoute d'évènement au keyup
function searchbar() {
    // Récupérer le texte tapé dans l'input par l'utilisateur
    let textFind = document.querySelector('#search').value;

    // Faire un objet de type request
    let myRequest = new Request('src/ajaxSearchbar.php', {
        method: 'POST',
        body: JSON.stringify({ textToFind: textFind })
    })
    // On attend la réponse du fichier ajaxSearchbar.php

    fetch(myRequest)
        // Récupère les données
        .then(res => res.text())

        // Exploite les données
        .then(res => {
            document.getElementById("result").innerHTML = res; // On met searchProducts.phtml dans la div -> id=result
            // ou
            //location.reload(); // Pour une réactualisation de la page
        })
}

});








