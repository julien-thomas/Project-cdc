document.addEventListener('DOMContentLoaded', function () {
  // Cacher la notif après 3 secondes d'affichage
  setTimeout(function () {
    document.querySelector('.notif').innerHTML = ''
  }, 3000)

  /* function validate() {
    document.getElementById("delete-button").window.confirm(`Êtes vous sûr de vouloir bloquer ce produite ?!`);
  } */
})