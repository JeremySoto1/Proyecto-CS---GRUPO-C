document.addEventListener("DOMContentLoaded", function () {
  // Mostrar/ocultar formulario
  document
    .getElementById("mostrarFormulario")
    .addEventListener("click", function () {
      const form = document.getElementById("formularioLibro");
      form.style.display = form.style.display === "none" ? "block" : "none";
      limpiarFormulario();
    });
});

function editarLibro(libroID) {
  const fila = document.getElementById(`fila-${libroID}`);

  // Llenar formulario con datos del libro
  document.getElementById("libroID").value = libroID;
  document.getElementById("title").value =
    fila.querySelector(".titulo").innerText;
  document.getElementById("author").value =
    fila.querySelector(".autor").innerText;
  document.getElementById("year").value = fila.querySelector(".anio").innerText;
  document.getElementById("pages_no").value =
    fila.querySelector(".paginas").innerText;
  document.getElementById("gender").value =
    fila.querySelector(".gender").dataset.id;

  // Mostrar formulario
  const formulario = document.getElementById("formularioLibro");
  formulario.style.display = "block";

  // Cambiar botón a modificar
  const boton = document.querySelector("#form_libro button[type='submit']");
  boton.name = "modificar";
  boton.textContent = "Modificar Libro";
}

function limpiarFormulario() {
  document.getElementById("form_libro").reset();
  document.getElementById("libroID").value = "";

  // Restaurar botón a guardar
  const boton = document.querySelector("#form_libro button[type='submit']");
  boton.name = "guardar";
  boton.textContent = "Guardar Libro";
}

function toggleDeshabilitados() {
  const tabla = document.getElementById("tablaDeshabilitados");
  tabla.style.display = tabla.style.display === "none" ? "block" : "none";
}
