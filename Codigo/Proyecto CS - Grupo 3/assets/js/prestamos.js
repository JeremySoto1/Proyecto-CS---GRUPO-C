document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("mostrarFormulario")
    .addEventListener("click", function () {
      const form = document.getElementById("form_prestamo");
      form.style.display = form.style.display === "none" ? "block" : "none";
      document.getElementById("prestamoModal").style.display = "block";
      limpiarFormulario();
    });

  // Cerrar modal si hacen clic fuera de él
  window.onclick = function (event) {
    const modal = document.getElementById("prestamoModal");
    if (event.target == modal) {
      cerrarModal();
    }
  };
});

function cerrarModal() {
  document.getElementById("prestamoModal").style.display = "none";
}

function limpiarFormulario() {
  document.getElementById("form_prestamo").reset();
  document.getElementById("prestamoID").value = "";
  const boton = document.querySelector("#form_prestamo button[type='submit']");
  boton.name = "guardar";
  boton.textContent = "Guardar Prestamo";
}

function buscarLector() {
  const cedula = document.getElementById("cedula").value;

  fetch(`../Controlador/prestamos_controlador.php?cedula=${cedula}`)
    .then((response) => response.json())
    .then((data) => {
      if (data) {
        document.getElementById("lectorID").value = data.id;
        document.getElementById("lectorNombre").textContent = data.nombre;
        document.getElementById("lectorEmail").textContent = data.email;
      } else {
        alert("Lector no encontrado");
      }
    })
    .catch((error) => console.error("Error:", error));
}

function buscarLibros() {
  const busqueda = document.getElementById("busquedaLibro").value;
  // Implementar la búsqueda de libros
  console.log("Buscar libros:", busqueda);
}
