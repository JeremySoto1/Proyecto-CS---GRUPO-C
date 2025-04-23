document.getElementById("form_libro").addEventListener("submit", function (e) {
  e.preventDefault(); // Evita que el formulario se envíe de forma tradicional

  const formData = new FormData(this); // Recoge todos los datos del formulario

  fetch(
    "http://localhost/Proyecto%20CS%20-%20Grupo%203/Modulos/Existencias/Controlador/book_controlador.php",
    {
      method: "POST",
      body: formData,
    }
  )
    .then((response) => response.json()) // Respuesta JSON desde PHP
    .then((data) => {
      // Muestra un mensaje en el contenedor
      const messageDiv = document.getElementById("message");
      if (data.success) {
        messageDiv.innerHTML = `✅ ${data.message}`;
      } else {
        messageDiv.innerHTML = `❌ ${data.message}`;
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
});
