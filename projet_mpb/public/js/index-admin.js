'use strict'

document.addEventListener('DOMContentLoaded', function () {
    document.querySelector("#delete-product").addEventListener('click', deleteProduct);
    function deleteProduct() {
        console.log('test');
        return window.confirm(`Êtes vous sûr de vouloir supprimer ce produit ?!`);
    }

});



setTimeout(function () {
    document.querySelector('.notif').innerHTML = ''
}, 3000);






document.addEventListener('DOMContentLoaded', function () {
    
    document.querySelector("#block-opinion").addEventListener('click', validateBlock);
    function validateBlock() {
        console.log('test');
        return window.confirm(`Êtes vous sûr de vouloir bloquer cet avis ?!`);
    }
});