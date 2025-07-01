document.getElementById("btn_recovery").addEventListener("click", recovery);
document.getElementById("btn_login").addEventListener("click", login);
window.addEventListener("resize", widthPage);

//Variables
var contenedor_login = document.querySelector(".contenedor_login");
var contenedor_recovery = document.querySelector(
  ".contenedor_recovery_password"
);
var form_login = document.querySelector(".form__login");
var form_recovery = document.querySelector(".form__recovery");
var caja_trasera_login = document.querySelector(".caja__trasera-login");
var caja_trasera_recovery = document.querySelector(
  ".caja__trasera-recovery_password"
);

let formularioActivo = "login"; // o "recovery"

function widthPage() {
  if (window.innerWidth > 850) {
    caja_trasera_login.style.display = "block";
    caja_trasera_recovery.style.display = "block";
  } else {
    caja_trasera_recovery.style.display = "block";
    caja_trasera_recovery.style.opacity = "1";
    caja_trasera_login.style.display = "none";

    if (formularioActivo === "login") {
      form_login.style.display = "block";
      form_recovery.style.display = "none";
    } else {
      form_login.style.display = "none";
      form_recovery.style.display = "block";
    }

    contenedor_login.style.left = "0px";
    contenedor_recovery.style.left = "0px";
  }
}

widthPage();

function login() {
  formularioActivo = "login";
  if (window.innerWidth > 850) {
    form_recovery.style.display = "none";
    contenedor_recovery.style.left = "10px";
    form_login.style.display = "block";
    caja_trasera_recovery.style.opacity = "1";
    caja_trasera_login.style.opacity = "0";
  } else {
    form_recovery.style.display = "none";
    contenedor_recovery.style.left = "0px";
    form_login.style.display = "block";
    caja_trasera_recovery.style.display = "none";
    caja_trasera_login.style.display = "block";
  }
}

function recovery() {
  formularioActivo = "recovery";
  if (window.innerWidth > 850) {
    form_recovery.style.display = "block";
    contenedor_recovery.style.left = "410px";
    form_login.style.display = "none";
    caja_trasera_recovery.style.opacity = "0";
    caja_trasera_login.style.opacity = "1";
  } else {
    form_recovery.style.display = "block";
    contenedor_recovery.style.left = "0px";
    form_login.style.display = "none";
    caja_trasera_recovery.style.display = "block";
    caja_trasera_login.style.opacity = "1";
  }
}
