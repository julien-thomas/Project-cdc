'use strict'

import FormAdmin from './FormAdmin.js';

setTimeout(function () {
    document.querySelector('.notif').innerHTML = ''
}, 3000);

document.addEventListener('DOMContentLoaded', () => {
    // form validation
    const formAdmin = new FormAdmin();
    const contacts = JSON.parse(localStorage.getItem('valid-contact')) || [];
    const inputs = document.querySelectorAll('input');
    
    inputs.forEach.call(inputs, input => {
        input.addEventListener('keydown', formAdmin.removeError);
    });
        
    document.querySelector('form').addEventListener('submit', function(e) {
        
        const inputs = this.querySelectorAll('input');
        if (formAdmin.validate(inputs)) {
            const contact = formAdmin.contact;
            contacts.push(contact);
            formAdmin.post(contacts);
        } else {
            formAdmin.createError();
        }
        if(!formAdmin.isValid) {
            e.preventDefault();
        }
    });
    
    });
