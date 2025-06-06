document.addEventListener("DOMContentLoaded", () => {
  const formBuscar = document.getElementById("form-buscar");
  const cedulaInput = document.getElementById("cedula");
  const tablaBody = document.querySelector("#tablaMultas tbody");
  const estadoBloqueo = document.getElementById("estadoBloqueo");
  const estadoMultas = document.getElementById("estadoMultas");
  const cancelarBtn = document.getElementById("cancelarDeuda");
  let lectorIDGlobal = null;

  formBuscar.addEventListener("submit", async (e) => {
    e.preventDefault();
    const cedula = cedulaInput.value.trim();

    if (cedula === "") return;

    const formData = new FormData();
    formData.append("accion", "buscarMulta");
    formData.append("cedula", cedula);

    const response = await fetch(
      "../../../Modulos/Control/Controlador/control_controlador.php",
      {
        method: "POST",
        body: formData,
      }
    );

    const texto = await response.text();
    //console.log("Respuesta sin parsear:", texto); // Para ver el error real
    const data = JSON.parse(texto);

    if (!data.lector) {
      alert("No se encontró el lector con esa cédula.");
      return;
    }

    // Mostrar info del lector
    lectorIDGlobal = data.lector.lectorID;
    document.getElementById("nombre").textContent = data.lector.nombre;
    document.getElementById("apellido").textContent = data.lector.apellido;
    document.getElementById("cedulaMostrar").textContent = data.lector.cedula;
    document.getElementById("email").textContent = data.lector.email;
    document.getElementById("telefono").textContent = data.lector.telefono;
    document.getElementById("direccion").textContent = data.lector.direccion;
    estadoBloqueo.innerHTML = `Estado de Bloqueo: <strong>${data.lector.estadoLector}</strong>`;

    // Mostrar multas
    tablaBody.innerHTML = "";
    let todasPagadas = true;
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

    estadoMultas.innerHTML = `Estado de Multas: <strong>${
      todasPagadas ? "Todas Pagadas" : "Con Deuda"
    }</strong>`;
    document
      .getElementById("desbloquearBtn")
      ?.toggleAttribute("disabled", !todasPagadas);
  });

  // Cancelar la primera multa no pagada (como ejemplo)
  cancelarBtn.addEventListener("click", async () => {
    const fila = Array.from(tablaBody.rows).find(
      (row) => row.cells[4].textContent === "No pagado"
    );
    if (!fila) {
      alert("No hay multas pendientes.");
      return;
    }

    const multaID = fila.cells[0].textContent;

    const formData = new FormData();
    formData.append("accion", "cancelarMulta");
    formData.append("multaID", multaID);

    const response = await fetch(
      "../../../Modulos/Control/Controlador/control_controlador.php",
      {
        method: "POST",
        body: formData,
      }
    );

    const result = await response.json();
    console.log(result);

    if (result.filasAfectadas.filasAfectadas > 0) {
      alert("Multa cancelada con éxito.");
      formBuscar.dispatchEvent(new Event("submit")); // Recargar
    } else {
      alert("No se pudo cancelar la multa.");
    }
  });

  // Botón de bloquear
  const bloquearBtn = document.createElement("button");
  bloquearBtn.textContent = "Bloquear Lector";
  bloquearBtn.id = "bloquearBtn";
  document.querySelector(".estado-lector").appendChild(bloquearBtn);

  bloquearBtn.addEventListener("click", async () => {
    if (!lectorIDGlobal) return;

    const formData = new FormData();
    formData.append("accion", "bloquearLector");
    formData.append("lectorID", lectorIDGlobal);

    const response = await fetch(
      "../../../Modulos/Control/Controlador/control_controlador.php",
      {
        method: "POST",
        body: formData,
      }
    );

    alert("Lector bloqueado.");
    formBuscar.dispatchEvent(new Event("submit"));
  });

  // Botón de desbloquear
  const desbloquearBtn = document.createElement("button");
  desbloquearBtn.textContent = "Desbloquear Lector";
  desbloquearBtn.id = "desbloquearBtn";
  desbloquearBtn.disabled = true;
  document.querySelector(".estado-lector").appendChild(desbloquearBtn);

  desbloquearBtn.addEventListener("click", async () => {
    if (!lectorIDGlobal) return;

    const formData = new FormData();
    formData.append("accion", "desbloquearLector");
    formData.append("lectorID", lectorIDGlobal);

    const response = await fetch(
      "../../../Modulos/Control/Controlador/control_controlador.php",
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
