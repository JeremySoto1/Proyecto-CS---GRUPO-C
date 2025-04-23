<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="assets/CSS/styless.css" />
  </head>
  <body>
    <main>
      <div class="contenedor__all">
        <div class="caja__tracera">
          <div class="caja__trasera-login">
            <h3>¿Ya tiene una cuenta?</h3>
            <p>Inicia Sesión para ingresar al sistema</p>
            <button class="button_divs" id="btn_login">Iniciar Sesión</button>
          </div>
          <div class="caja__trasera-recovery_password">
            <h3>¿Se olvidó de la contraseña?</h3>
            <p>Ingrese el correo y el usuario para recuperar la contraseña</p>
            <button class="button_divs" id="btn_recovery">
              Recuperar Contraseña
            </button>
          </div>
        </div>
        <!--Formulario de login y recuperar contraseña-->
        <div class="contenedor_login">
          <!--Login-->
          <form action="" class="form__login">
            <h2 class="h2_login">Iniciar Sesión</h2>
            <input type="text" placeholder="Nombre de Usuario" name = "unername"/>
            <input type="password" placeholder="Contraseña"  name = "password"/>
            <button class="btn_lg">Iniciar Sesión</button>
          </form>
        </div>
        <!--Recuperar Contraseña-->
        <div class="contenedor_recovery_password">
          <form action="" class="form__recovery">
            <h2 class="h2_recovery">Recuperar Contraseña</h2>
            <input type="text" placeholder="Correo Electrónico" name = "email"/>
            <input type="text" placeholder="Nombre de Usuario" name = "userName"/>
            <button class="btn_rc">Recuperar</button>
          </form>
        </div>
      </div>
    </main>
    <script src="assets/js/script.js"></script>
  </body>
</html>
