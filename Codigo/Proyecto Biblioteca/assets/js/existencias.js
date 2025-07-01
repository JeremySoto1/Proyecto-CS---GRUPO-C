// Función para mostrar el formulario de agregar
function mostrarFormulario() {
  document.getElementById("formFlotante").style.display = "flex";
  document.getElementById("formTitulo").textContent = "Nueva Existencia";
  document.querySelector("input[name='accion']").value = "guardar";
  document.getElementById("existenciaID").value = "";
  document.getElementById("libroID").value = "";
  document.getElementById("ubicacionID").selectedIndex = 0;
  document.getElementById("estadoExistenciaID").selectedIndex = 0;
  document.getElementById("disponibilidadExistenciaID").selectedIndex = 0;
}

// Función para cerrar el formulario de agregar
function cerrarFormulario() {
  document.getElementById("formFlotante").style.display = "none";
}

// Función para mostrar el formulario de modificar
function mostrarFormularioModificar(datos) {
  const formModificar = document.getElementById("formModificar");
  formModificar.classList.add("activo");

  // Llenar los campos con los datos
  document.getElementById("existenciaID_mod").value = datos.existenciaID;
  document.getElementById("libroID_mod").value = datos.libroID;
  document.getElementById("ubicacionID_mod").value = datos.ubicacionID;
  document.getElementById("estadoExistenciaID_mod").value =
    datos.estadoExistenciaID;
  document.getElementById("disponibilidadExistenciaID_mod").value =
    datos.disponibilidadExistenciaID;
}

// Función para cerrar el formulario de modificar
function cerrarFormularioModificar() {
  document.getElementById("formModificar").classList.remove("activo");
}

// Event listeners para cerrar formularios al hacer clic fuera
document.addEventListener("DOMContentLoaded", function () {
  // Cerrar al hacer clic fuera del formulario
  document
    .querySelectorAll(".form-flotante, .form-modificar")
    .forEach((form) => {
      form.addEventListener("click", function (e) {
        if (e.target === this) {
          if (this.id === "formFlotante") {
            cerrarFormulario();
          } else {
            cerrarFormularioModificar();
          }
        }
      });
    });

  // Prevenir el cierre al hacer clic dentro del formulario
  document
    .querySelectorAll(".formulario-agregar, .form-modificar form")
    .forEach((form) => {
      form.addEventListener("click", function (e) {
        e.stopPropagation();
      });
    });
});
