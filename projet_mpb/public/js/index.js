'use strict'

import Form from './Form.js';
    
// Hide notification after 3 seconds of display
setTimeout(function () {
    document.querySelector('.notif').innerHTML = ''
}, 3000);

document.addEventListener('DOMContentLoaded', () => {
// form validation
const form = new Form();
const contacts = JSON.parse(localStorage.getItem('valid-contact')) || [];
const inputs = document.querySelectorAll('input');

inputs.forEach.call(inputs, input => {
    input.addEventListener('keydown', form.removeError);
});

document.querySelector('form').addEventListener('submit', function(e) {
    
    const inputs = this.querySelectorAll('input');
    if (form.validate(inputs)) {
        const contact = form.contact;
        contacts.push(contact);
        form.post(contacts);
    } else {
        form.createError();
    }
    if(!form.isValid) {
        e.preventDefault();
    }
});
});

document.addEventListener('DOMContentLoaded', () => {
// SearchBar
document.querySelector("#search").addEventListener('keyup', searchbar);
/*
* searchbar function
*/
function searchbar() {
    // Retrieving text typed in the input by the user
    let textFind = document.querySelector('#search').value;

    let myRequest = new Request('src/ajaxSearchbar.php', {
        method: 'POST',
        body: JSON.stringify({ textToFind: textFind })
    })
    // waiting for the response from ajaxSearchbar.php

    fetch(myRequest)
        // data recovery
        .then(res => res.text())

        // data exploitation
        .then(res => {
            document.getElementById("result").innerHTML = res; // put searchProducts.phtml in the div -> id=result
        })
}

});








