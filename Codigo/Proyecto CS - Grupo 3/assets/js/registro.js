document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registroForm");

  form.addEventListener("submit", (e) => {
    const user = document.getElementById("unername").value;
    const email = document.getElementById("email").value;

    // Solo un ejemplo de validación simple:
    if (user.length < 4) {
      alert("El nombre de usuario debe tener al menos 4 caracteres.");
      e.preventDefault();
    }

    if (!email.includes("@")) {
      alert("El correo electrónico no es válido.");
      e.preventDefault();
    }
  });
});
