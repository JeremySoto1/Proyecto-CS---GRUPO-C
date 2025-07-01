document.addEventListener("DOMContentLoaded", () => {
  const formBuscar = document.getElementById("form-buscar");
  const cedulaInput = document.getElementById("cedula");
  const tablaBody = document.querySelector("#tablaMultas tbody");
  const estadoBloqueo = document.getElementById("estadoBloqueo");
  const estadoMultas = document.getElementById("estadoMultas");
  const cancelarBtn = document.getElementById("cancelarDeuda");
  let lectorIDGlobal = null;

  // Botones creados dinámicamente
  const bloquearBtn = document.createElement("button");
  bloquearBtn.textContent = "Bloquear Lector";
  bloquearBtn.id = "bloquearBtn";
  bloquearBtn.style.display = "none";
  document.querySelector(".estado-lector").appendChild(bloquearBtn);

  const desbloquearBtn = document.createElement("button");
  desbloquearBtn.textContent = "Desbloquear Lector";
  desbloquearBtn.id = "desbloquearBtn";
  desbloquearBtn.style.display = "none";
  document.querySelector(".estado-lector").appendChild(desbloquearBtn);

  formBuscar.addEventListener("submit", async (e) => {
    e.preventDefault();
    const cedula = cedulaInput.value.trim();

    if (cedula === "") {
      mostrarAlerta("Debe ingresar una cédula", "danger");
      return;
    }

    try {
      const formData = new FormData();
      formData.append("accion", "buscarMulta");
      formData.append("cedula", cedula);

      const response = await fetch(
        "/Proyecto%20Biblioteca/Controlador/Modulos/control_controlador.php",
        {
          method: "POST",
          body: formData,
        }
      );

      const data = await response.json();

      if (data.error) {
        mostrarAlerta(data.error, "danger");
        return;
      }

      if (data.info) {
        mostrarAlerta(data.info, "info");
      }

      // Mostrar info del lector
      lectorIDGlobal = data.lector.lectorID;
      document.getElementById("nombre").textContent = data.lector.nombre;
      document.getElementById("apellido").textContent = data.lector.apellido;
      document.getElementById("cedulaMostrar").textContent = data.lector.cedula;
      document.getElementById("email").textContent = data.lector.email;
      document.getElementById("telefono").textContent = data.lector.telefono;
      document.getElementById("direccion").textContent = data.lector.direccion;

      const estadoTexto = data.lector.estadoLector.trim().toLowerCase();
      estadoBloqueo.innerHTML = `Estado de Bloqueo: <strong>${
        estadoTexto.charAt(0).toUpperCase() + estadoTexto.slice(1)
      }</strong>`;
      console.log("Estado recibido del lector:", data.lector.estadoLector);

      // Mostrar multas
      tablaBody.innerHTML = "";
      let todasPagadas = true;

      if (data.multas && data.multas.length > 0) {
        data.multas.forEach((multa) => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${multa.multaID}</td>
            <td>${multa.monto}</td>
            <td>${multa.motivo}</td>
            <td>${multa.fecha_emision}</td>
            <td>${multa.estadoMulta}</td>
            <td>${multa.lectorID}</td>
            <td>${multa.prestamoID}</td>
          `;
          tablaBody.appendChild(tr);

          if (multa.estadoMulta === "No pagado") {
            todasPagadas = false;
          }
        });
      }

      estadoMultas.innerHTML = `Estado de Multas: <strong>${
        todasPagadas ? "Todas Pagadas" : "Con Deuda"
      }</strong>`;

      // Mostrar botón correcto según estado del lector
      if (estadoTexto === "bloqueado") {
        desbloquearBtn.style.display = "inline-block";
        desbloquearBtn.disabled = !todasPagadas;
        bloquearBtn.style.display = "none";
      } else {
        bloquearBtn.style.display = "inline-block";
        desbloquearBtn.style.display = "none";
      }
    } catch (error) {
      console.error("Error:", error);
      mostrarAlerta("Ocurrió un error al procesar la solicitud", "danger");
    }
  });

  // Cancelar la primera multa no pagada
  cancelarBtn.addEventListener("click", async () => {
    const fila = Array.from(tablaBody.rows).find(
      (row) => row.cells[4].textContent === "No pagado"
    );
    if (!fila) {
      mostrarAlerta("No hay multas pendientes.", "info");
      return;
    }

    try {
      const multaID = fila.cells[0].textContent;

      const formData = new FormData();
      formData.append("accion", "cancelarMulta");
      formData.append("multaID", multaID);

      const response = await fetch(
        "/Proyecto%20Biblioteca/Controlador/Modulos/control_controlador.php",
        {
          method: "POST",
          body: formData,
        }
      );

      const result = await response.json();

      if (result.error) {
        throw new Error(result.error);
      }

      if (result.filasAfectadas?.filasAfectadas > 0) {
        mostrarAlerta("Multa cancelada con éxito.", "success");
        formBuscar.dispatchEvent(new Event("submit")); // Recargar
      } else {
        mostrarAlerta("No se pudo cancelar la multa.", "danger");
      }
    } catch (error) {
      mostrarAlerta(error.message, "danger");
    }
  });

  // Botón de bloquear
  bloquearBtn.addEventListener("click", async () => {
    if (!lectorIDGlobal) return;

    const formData = new FormData();
    formData.append("accion", "bloquearLector");
    formData.append("lectorID", lectorIDGlobal);

    const response = await fetch(
      "/Proyecto%20Biblioteca/Controlador/Modulos/control_controlador.php",
      {
        method: "POST",
        body: formData,
      }
    );

    alert("Lector bloqueado.");
    formBuscar.dispatchEvent(new Event("submit"));
  });

  // Botón de desbloquear
  desbloquearBtn.addEventListener("click", async () => {
    if (!lectorIDGlobal) return;

    const formData = new FormData();
    formData.append("accion", "desbloquearLector");
    formData.append("lectorID", lectorIDGlobal);

    const response = await fetch(
      "/Proyecto%20Biblioteca/Controlador/Modulos/control_controlador.php",
      {
        method: "POST",
        body: formData,
      }
    );

    const result = await response.json();
    alert(result.mensaje);
    formBuscar.dispatchEvent(new Event("submit"));
  });
});
