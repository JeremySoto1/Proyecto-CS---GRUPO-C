document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("mostrarFormulario")
    .addEventListener("click", function () {
      document.getElementById("prestamoModal").style.display = "block";
      limpiarFormulario();
    });

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
  document.getElementById("lectorID").value = "";
  document.getElementById("lectorNombre").textContent = "-";
  document.getElementById("lectorEmail").textContent = "-";
  document.getElementById("cedula").value = "";
}

function buscarLector() {
  const cedula = document.getElementById("cedula").value.trim();
  if (!cedula) {
    alert("Por favor ingrese una cédula válida");
    return;
  }
  fetch(
    `../Controlador/prestamo_formulario_controlador.php?cedula=${encodeURIComponent(
      cedula
    )}`
  )
    .then((response) => {
      if (!response.ok) throw new Error("Error del servidor");
      return response.json();
    })
    .then((data) => {
      if (data && data.id) {
        document.getElementById("lectorID").value = data.id;
        document.getElementById("lectorNombre").textContent = data.nombre;
        document.getElementById("lectorEmail").textContent = data.email;
      } else {
        alert("Lector no encontrado.");
        resetLectorFields();
      }
    })
    .catch((error) => {
      alert("Error: " + error.message);
      resetLectorFields();
    });
}

function resetLectorFields() {
  document.getElementById("lectorID").value = "";
  document.getElementById("lectorNombre").textContent = "-";
  document.getElementById("lectorEmail").textContent = "-";
  document.getElementById("cedula").focus();
}

document
  .getElementById("form_prestamo")
  .addEventListener("submit", function (e) {
    const checkboxes = document.querySelectorAll(".libro-checkbox:checked");
    if (checkboxes.length === 0) {
      e.preventDefault();
      alert("Debe seleccionar al menos un libro");
    }
  });

function buscarLibros() {
  const busqueda = document.getElementById("busquedaLibro").value.trim();
  fetch(
    `../Controlador/prestamo_formulario_controlador.php?buscarLibro=${encodeURIComponent(
      busqueda
    )}`
  )
    .then((res) => res.json())
    .then((existencias) => {
      const tbody = document.getElementById("tablaExistencias");
      tbody.innerHTML = "";
      existencias.forEach((ex) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
                    <td>${ex.existenciaID}</td>
                    <td>${ex.TituloLibro}</td>
                    <td>${ex.Seccion}</td>
                    <td>${ex.Pasillo}</td>
                    <td>${ex.Estanteria}</td>
                    <td>${ex.Nivel}</td>
                    <td>${ex.EstadoExistencia}</td>
                    <td>${ex.Disponibilidad}</td>
                    <td><input type="checkbox" name="existencias[]" value="${ex.existenciaID}" class="libro-checkbox"></td>
                `;
        tbody.appendChild(tr);
      });
    });
}

// Agrega estas funciones a tu archivo JS existente
function abrirModalDevolucion(prestamoID, existenciaID) {
  document.getElementById("prestamoID_devolucion").value = prestamoID;
  document.getElementById("existenciaID_devolucion").value = existenciaID;
  document.getElementById("devolucionModal").style.display = "block";
}

function cerrarModalDevolucion() {
  document.getElementById("devolucionModal").style.display = "none";
  document.getElementById("form_devolucion").reset();
  document.getElementById("motivoContainer").style.display = "none";
}

function mostrarMotivo() {
  const estado = document.getElementById("estado_existencia").value;
  const motivoContainer = document.getElementById("motivoContainer");

  if (estado === "1") {
    motivoContainer.style.display = "block";
  } else {
    motivoContainer.style.display = "none";
  }
}

// Función para abrir el modal con los datos correctos
function abrirModalDevolucion(prestamoID, existenciaID) {
  if (!prestamoID || !existenciaID) {
    console.error("IDs no válidos:", { prestamoID, existenciaID });
    alert(
      "Error: No se pudo obtener la información necesaria para la devolución"
    );
    return;
  }

  document.getElementById("prestamoID_devolucion").value = prestamoID;
  document.getElementById("existenciaID_devolucion").value = existenciaID;
  document.getElementById("devolucionModal").style.display = "block";
}

// Evento para los botones de devolución
document.querySelectorAll(".btn-devolver").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.preventDefault();
    const prestamoID = this.getAttribute("data-prestamo");
    const existenciaID = this.getAttribute("data-existencia");

    console.log("Datos a enviar:", { prestamoID, existenciaID });

    if (!prestamoID || !existenciaID) {
      alert("Error: Datos incompletos para procesar la devolución");
      return;
    }

    abrirModalDevolucion(prestamoID, existenciaID);
  });
});

// Evento para el formulario de devolución
// Modifica el evento submit del formulario
document
  .getElementById("form_devolucion")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const prestamoID = formData.get("prestamoID");

    console.log("Datos a enviar:", {
      prestamoID,
      estado_existencia: formData.get("estado_existencia"),
      disponibilidad: formData.get("disponibilidad"),
      motivo_multa: formData.get("motivo_multa"),
    });

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = "Procesando...";

    fetch(this.action, {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => {
        if (!response.ok)
          return response.json().then((err) => {
            throw err;
          });
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          alert(data.message);
          cerrarModalDevolucion();
          document.querySelector(`tr[id="fila-${prestamoID}"]`)?.remove();
        } else {
          throw new Error(data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Error: " + error.message);
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      });
  });
