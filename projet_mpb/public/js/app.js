document.addEventListener('DOMContentLoaded', function () {
  // Cacher la notif après 3 secondes d'affichage
  setTimeout(function () {
    document.querySelector('.notif').innerHTML = ''
  }, 3000)
})

  /* function validate() {
    document.getElementById("delete-button").window.confirm(`Êtes vous sûr de vouloir bloquer ce produite ?!`);
  } */

  let input = document.querySelector("#search");

  input.addEventListener('keyup', () => { // Ecoute d'évènement au keyup

      // Récupérer le texte tapé dans l'input par l'utilisateur
      let textFind = document.querySelector('#search').value;

      // Faire un objet de type request
      let myRequest = new Request('src/ajaxSearchbar.php', {
          method  : 'POST',
          body    : JSON.stringify({ textToFind : textFind })
      })
          // On attend la réponse du fichier ajaxSearchbar.php

      fetch(myRequest)
          // Récupère les données
          .then(res => res.text())

          // Exploite les données
          .then(res => {
              document.getElementById("result").innerHTML = res; // On met articles.phtml dans la div -> id=target
              // ou
              //location.reload(); // Pour une réactualisation de la page
          })
  })

  /* let fileInput = document.querySelector("#file");

  input.addEventListener('keyup', () => { // Ecoute d'évènement au keyup

      // Récupérer le texte tapé dans l'input par l'utilisateur
      let textFind = document.querySelector('#search').value;

      // Faire un objet de type request
      let myRequest = new Request('src/ajaxSearchbar.php', {
          method  : 'POST',
          body    : JSON.stringify({ textToFind : textFind })
      })
          // On attend la réponse du fichier ajaxSearchbar.php
          // Portez-vous à la ligne 229 pour suivre la logique du code et vous reviendrez ici pour lire la suite du code JS

      fetch(myRequest)
          // Récupère les données
          .then(res => res.text())

          // Exploite les données
          .then(res => {
              document.getElementById("result").innerHTML = res; // On met articles.phtml dans la div -> id=target
              // ou
              //location.reload(); // Pour une réactualisation de la page
          })
  })
 */










