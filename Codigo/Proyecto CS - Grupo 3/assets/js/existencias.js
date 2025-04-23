function cerrarFormulario() {
  document.getElementById("formFlotante").style.display = "none";
  document.getElementById("formTitulo").innerText = "Nueva Existencia";
  document.getElementById("accion").value = "guardar";
  document.getElementById("existenciaID").value = "";
  document.getElementById("libroID").value = "";
  document.getElementById("ubicacionID").selectedIndex = 0;
  document.getElementById("estadoExistenciaID").selectedIndex = 0;
  document.getElementById("disponibilidadExistenciaID").selectedIndex = 0;
}

function mostrarFormulario() {
  cerrarFormulario(); // Limpia primero
  document.getElementById("formFlotante").style.display = "block";
}

function mostrarModificarExistencia(existenciaJSON) {
  const e = JSON.parse(existenciaJSON);

  document.getElementById("formFlotante").style.display = "block";
  document.getElementById("formTitulo").innerText = "Modificar Existencia";
  document.getElementById("accion").value = "modificar";

  document.getElementById("existenciaID").value = e.existenciaID;
  document.getElementById("libroID").value = e.libroID;
  document.getElementById("ubicacionID").value = e.ubicacionID;
  document.getElementById("estadoExistenciaID").value = e.estadoExistenciaID;
  document.getElementById("disponibilidadExistenciaID").value =
    e.disponibilidadExistenciaID;
}

function cerrarFormularioModificar() {
  document.getElementById("formModificar").style.display = "none";
}
