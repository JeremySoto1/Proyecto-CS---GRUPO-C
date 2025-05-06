document.addEventListener("DOMContentLoaded", function () {
  // Mostrar/ocultar formulario
  document
    .getElementById("mostrarFormulario")
    .addEventListener("click", function () {
      const form = document.getElementById("formularioBibliotecario");
      form.style.display = form.style.display === "none" ? "block" : "none";
      limpiarFormulario();
    });
});

function editarBibliotecario(bibliotecarioID) {
  const fila = document.getElementById(`fila-${bibliotecarioID}`);

  // Llenar formulario con datos
  document.getElementById("bibliotecarioID").value = bibliotecarioID;
  document.getElementById("nombre").value =
    fila.querySelector(".nombre").innerText;
  document.getElementById("apellido").value =
    fila.querySelector(".apellido").innerText;
  document.getElementById("email").value =
    fila.querySelector(".email").innerText;
  document.getElementById("usuario").value =
    fila.querySelector(".usuario").innerText;
  document.getElementById("contrasenia").value = "";

  // Mostrar formulario
  document.getElementById("formularioBibliotecario").style.display = "block";

  // Cambiar botón a modificar
  const boton = document.querySelector(
    "#form_bibliotecario button[type='submit']"
  );
  boton.name = "modificar";
  boton.textContent = "Modificar Bibliotecario";
}

function limpiarFormulario() {
  document.getElementById("form_bibliotecario").reset();
  document.getElementById("bibliotecarioID").value = "";

  // Restaurar botón a guardar
  const boton = document.querySelector(
    "#form_bibliotecario button[type='submit']"
  );
  if (boton) {
    boton.name = "guardar";
    boton.textContent = "Guardar Bibliotecario";
  }
}

function cerrarFormulario() {
  document.getElementById("formularioBibliotecario").style.display = "none";
  limpiarFormulario();
}
