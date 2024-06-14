// Espera a que el DOM se cargue completamente
document.addEventListener('DOMContentLoaded', function() {
    // Obtiene el botón que abre el modal
    var btnOpenModal = document.getElementById('addValeBtn');

    // Obtiene el modal
    var modal = document.getElementById('myModal');

    // Obtiene el elemento que cierra el modal
    var spanClose = document.getElementsByClassName('close')[0];

    // Añade un evento click al botón para abrir el modal
    btnOpenModal.addEventListener('click', function() {
        modal.style.display = "block";
    });

    // Añade un evento click al botón (span) que cierra el modal
    spanClose.addEventListener('click', function() {
        modal.style.display = "none";
    });

    // Añade un evento click fuera del modal para cerrarlo
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});
