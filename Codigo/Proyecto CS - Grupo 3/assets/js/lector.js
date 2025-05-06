document.addEventListener("DOMContentLoaded", function () {
  // Mostrar/ocultar formulario
  document
    .getElementById("mostrarFormulario")
    .addEventListener("click", function () {
      const form = document.getElementById("formularioLector");
      form.style.display = form.style.display === "none" ? "block" : "none";
      limpiarFormulario();
    });
});

function editarLector(lectorID) {
  const fila = document.getElementById(`fila-${lectorID}`);

  // Llenar formulario con datos
  document.getElementById("lectorID").value = lectorID;
  document.getElementById("nombre").value =
    fila.querySelector(".nombre").innerText;
  document.getElementById("apellido").value =
    fila.querySelector(".apellido").innerText;
  document.getElementById("cedula").value =
    fila.querySelector(".cedula").innerText;
  document.getElementById("email").value =
    fila.querySelector(".email").innerText;
  document.getElementById("telefono").value =
    fila.querySelector(".telefono").innerText;
  // Nota: La dirección no está en la tabla, deberás obtenerla de otra manera si es necesario

  // Mostrar formulario
  document.getElementById("formularioLector").style.display = "block";

  // Cambiar botón a modificar
  const boton = document.querySelector("#form_lector button[type='submit']");
  boton.name = "modificar";
  boton.textContent = "Modificar Lector";
}

function limpiarFormulario() {
  document.getElementById("form_lector").reset();
  document.getElementById("lectorID").value = "";

  // Restaurar botón a guardar
  const boton = document.querySelector("#form_lector button[type='submit']");
  if (boton) {
    boton.name = "guardar";
    boton.textContent = "Guardar Lector";
  }
}

function cerrarFormulario() {
  document.getElementById("formularioLector").style.display = "none";
  limpiarFormulario();
}

function toggleInactivos() {
  const tabla = document.getElementById("tablaInactivos");
  tabla.style.display = tabla.style.display === "none" ? "block" : "none";
}
