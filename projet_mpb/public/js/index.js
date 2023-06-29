'use strict'

document.addEventListener('DOMContentLoaded', function () {
    document.querySelector("#delete-product").addEventListener('click', deleteProduct);
    document.querySelector("#block-opinion").addEventListener('submit', validateBlock);
    
});

setTimeout(function () {
    document.querySelector('.notif').innerHTML = ''
}, 3000);

function validateBlock() {
    console.log('test');
    return window.confirm(`Êtes vous sûr de vouloir bloquer cet avis ?!`);
}

function deleteProduct() {
    console.log('test');
    return window.confirm(`Êtes vous sûr de vouloir supprimer ce produit ?!`);
}
