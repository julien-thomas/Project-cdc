'use strict'

import FormAdmin from './FormAdmin.js';

/* document.addEventListener('DOMContentLoaded', function () {
    document.querySelector("#delete-product").addEventListener('click', deleteProduct);
    function deleteProduct() {
        console.log('test');
        return window.confirm(`Êtes vous sûr de vouloir supprimer ce produit ?!`);
    }

}); */



setTimeout(function () {
    document.querySelector('.notif').innerHTML = ''
}, 3000);

document.addEventListener('DOMContentLoaded', () => {
    // Validation formulaires
    
    
    const form = new FormAdmin();
    const contacts = JSON.parse(localStorage.getItem('valid-contact')) || [];
    const inputs = document.querySelectorAll('input');
    
    document.querySelector('form').addEventListener('submit', validateForm);
    
    console.log('inputs:' + inputs);
    inputs.forEach.call(inputs, input => {
        input.addEventListener('keydown', form.removeError);
    });
    
    function validateForm(e) {
        
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
    }
    
    });




document.addEventListener('DOMContentLoaded', function () {
    
    document.querySelector("#block-opinion").addEventListener('click', validateBlock);
    function validateBlock() {
        console.log('test');
        window.confirm(`Êtes vous sûr de vouloir bloquer cet avis ?!`)
    }
}); 