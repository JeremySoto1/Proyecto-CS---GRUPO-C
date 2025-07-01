let cubiculoSeleccionado = null;

// Función para manejar errores de las peticiones fetch
function manejarErrorFetch(error, mensajeDefault = "Ocurrió un error") {
  console.error("Error:", error);
  let mensaje = mensajeDefault;

  if (error instanceof Error) {
    mensaje = error.message;
  } else if (typeof error === "string") {
    mensaje = error;
  } else if (error.error) {
    mensaje = error.error;
  }

  mostrarAlerta(mensaje, "danger");
  return { success: false, error: mensaje };
}

function mostrarFormularioCubiculo() {
  document.getElementById("form-cubiculo").style.display = "block";
}

function guardarCubiculo(event) {
  event.preventDefault();
  const form = event.target;
  const data = {
    nombre: form.nombre.value,
    equipamento: form.equipamento.value,
    capacidad: form.capacidad.value,
  };

  fetch("../../Controlador/Modulos/cubiculo_controlador.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ accion: "registrarCubiculo", ...data }),
  })
    .then((res) => res.json())
    .then((res) => {
      if (res.success) {
        mostrarAlerta("Cubículo registrado exitosamente", "success");
        cargarCubiculos();
        form.reset();
        form.parentElement.style.display = "none";
      } else {
        throw res.error || "Error al guardar cubículo";
      }
    })
    .catch((err) => manejarErrorFetch(err));
}

function cargarCubiculos() {
  fetch("../../Controlador/Modulos/cubiculo_controlador.php?accion=listar")
    .then((res) => res.json())
    .then((data) => {
      const cont = document.getElementById("cubiculos-lista");
      cont.innerHTML = "";
      data.forEach((cub) => {
        cont.innerHTML += `
                <div class="cubiculo">
                    <h4>${cub.cubiculoID} ${cub.nombre}</h4>
                    <img src="../../assets/images/cubiculos.png" />
                    <p><strong>Equipamento:</strong> ${cub.equipamento}</p>
                    <p><strong>Capacidad:</strong> ${cub.capacidad}</p>
                    <span class="estado ${
                      cub.disponibilidadID == 1 ? "verde" : "rojo"
                    }">
                        ${cub.disponibilidadID == 1 ? "Disponible" : "Ocupado"}
                    </span>
                    <div>
                        ${
                          cub.disponibilidadID == 1
                            ? `<button class="btn-alquiler" onclick="abrirModalAlquiler(${cub.cubiculoID}, '${cub.nombre}', '${cub.equipamento}', ${cub.capacidad})">Alquilar</button>`
                            : `<button class="btn-devolver" onclick="devolverCubiculo(${cub.cubiculoID})">Devolver</button>`
                        }
                    </div>
                </div>
            `;
      });
    })
    .catch((err) => manejarErrorFetch(err, "Error al cargar cubículos"));
}

function abrirModalAlquiler(id, nombre, equipamento, capacidad) {
  cubiculoSeleccionado = id;
  document.getElementById("alq-nombre").innerText = nombre;
  document.getElementById("alq-equipamento").innerText = equipamento;
  document.getElementById("alq-capacidad").innerText = capacidad;
  document.getElementById("modal-alquiler").style.display = "flex";
}

function cerrarModal() {
  document.getElementById("modal-alquiler").style.display = "none";
  document.getElementById("lector-encontrado").innerText = "";
  document.getElementById("lector-encontrado").removeAttribute("data-id");
  document.getElementById("cedula-buscar").value = "";
}

function buscarLector() {
  const cedula = document.getElementById("cedula-buscar").value;
  if (!cedula) {
    mostrarAlerta("Ingrese una cédula", "danger");
    return;
  }

  fetch(
    `../../Controlador/Modulos/cubiculo_controlador.php?accion=buscarLector&cedula=${cedula}`
  )
    .then((res) => res.json())
    .then((lector) => {
      if (lector.error) {
        throw lector.error;
      }

      if (lector && lector.lectorID) {
        document.getElementById("lector-encontrado").innerText =
          lector.nombre + " " + lector.apellido;
        document.getElementById("lector-encontrado").dataset.id =
          lector.lectorID;
      } else {
        throw "Lector no encontrado";
      }
    })
    .catch((err) => {
      document.getElementById("lector-encontrado").innerText = err;
      document.getElementById("lector-encontrado").removeAttribute("data-id");
      mostrarAlerta(err, "danger");
    });
}

function guardarAlquiler() {
  const lectorID = document.getElementById("lector-encontrado").dataset.id;
  if (!lectorID || !cubiculoSeleccionado) {
    mostrarAlerta("Faltan datos para realizar el alquiler", "danger");
    return;
  }

  fetch("../../Controlador/Modulos/cubiculo_controlador.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      accion: "alquilarCubiculo",
      lectorID,
      cubiculoID: cubiculoSeleccionado,
    }),
  })
    .then((res) => res.json())
    .then((res) => {
      if (res.success) {
        mostrarAlerta("Cubículo alquilado exitosamente", "success");
        cerrarModal();
        cargarCubiculos();
      } else {
        throw res.error || "No se pudo alquilar el cubículo";
      }
    })
    .catch((err) => manejarErrorFetch(err));
}

function devolverCubiculo(id) {
  fetch("../../Controlador/Modulos/cubiculo_controlador.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ accion: "devolverCubiculo", cubiculoID: id }),
  })
    .then((res) => res.json())
    .then((res) => {
      if (res.success) {
        mostrarAlerta("Cubículo devuelto exitosamente", "success");
        cargarCubiculos();
      } else {
        throw res.error || "Error al devolver cubículo";
      }
    })
    .catch((err) => manejarErrorFetch(err));
}

// Inicial
cargarCubiculos();
