var modoEdicion = false; // Por defecto, el modo es para agregar un nuevo empleado

document.addEventListener("DOMContentLoaded", function() {
    var selectUserButtons = document.querySelectorAll(".select-user");
    selectUserButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            modoEdicion = true;
            var dni = this.getAttribute("data-dni");
            var name = this.getAttribute("data-name");
            var email = this.getAttribute("data-email");
            var telefono = this.getAttribute("data-telefono");
            var rol = this.getAttribute("data-rol");
            var horas = this.getAttribute("data-horas");

            document.getElementById("dni").value = dni;
            document.getElementById("name").value = name;
            document.getElementById("email").value = email;
            document.getElementById("telefono").value = telefono;
            document.getElementById("rol").value = rol;
            document.getElementById("horas").value = horas;

            document.getElementById("dni").readOnly = true; // No se debe permitir cambiar el DNI al editar
            document.getElementById("name").readOnly = false;
            document.getElementById("email").readOnly = false;
            document.getElementById("telefono").readOnly = false;
            document.getElementById("rol").readOnly = false;
            document.getElementById("horas").readOnly = false;
            $('#editarModal').modal('show');
        });
    });

    document.getElementById("agregarEmpleadoBtn").addEventListener("click", function() {
        modoEdicion = false;
        document.getElementById("editarEmpleadoForm").reset();
        document.getElementById("dni").readOnly = false;
        document.getElementById("name").readOnly = false;
        document.getElementById("email").readOnly = false;
        document.getElementById("telefono").readOnly = false;
        document.getElementById("rol").readOnly = false;
        document.getElementById("horas").readOnly = false;
    });

    $('#editarModal').on('show.bs.modal', function(event) {
        if (!modoEdicion) {
            document.getElementById("dni").readOnly = false;
            document.getElementById("name").readOnly = false;
            document.getElementById("email").readOnly = false;
            document.getElementById("telefono").readOnly =false ;
            document.getElementById("rol").readOnly = false;
            document.getElementById("horas").readOnly = false;
        }
    });
});

function eliminarEmpleado(dni) {
    if (confirm('¿Estás seguro de que deseas eliminar este empleado?')) {
        window.location.href = "empleados.php?eliminar=" + dni;
    }
}

// Selecciona todos los botones "Eliminar"
const botonesEliminar = document.querySelectorAll('.eliminar-empleado');

// Agrega un evento click a cada botón "Eliminar"
botonesEliminar.forEach(boton => {
    boton.addEventListener('click', function() {
        // Obtiene el DNI del empleado asociado al botón
        const dniEmpleado = boton.dataset.dni;
        
        // Envía una solicitud al servidor para eliminar al empleado
        eliminarEmpleado(dniEmpleado);
    });
});