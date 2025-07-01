<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Sistema Biblioteca</title>
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
                    <button class="button_divs" id="btn_recovery">Recuperar Contraseña</button>
                </div>
            </div>
            
            <!--Formulario de login-->
            <div class="contenedor_login">
                <form action="Controlador/Session/validar_login.php" method="POST" class="form__login">
                    <h2 class="h2_login">Iniciar Sesión</h2>
                    <?php if(isset($_GET['error'])): ?>
                        <div class="error-message 
                            <?php 
                                if(isset($_GET['code'])) {
                                    switch(intval($_GET['code'])) {
                                        case 0: echo 'error-usuario'; break;
                                        case 1: echo 'error-contrasenia'; break;
                                        case 2: echo 'error-bloqueo'; break;
                                        default: echo 'error-generico';
                                    }
                                } else {
                                    echo 'error-generico';
                                }
                            ?>
                        ">
                            <?= htmlspecialchars($_GET['error']) ?>
                            <?php if(isset($_GET['code']) && intval($_GET['code']) === 0): ?>
                                
                            <?php elseif(isset($_GET['code']) && intval($_GET['code']) === 1): ?>
                                <br><a href="#" class="recovery-link" onclick="mostrarRecuperacion()">¿Olvidaste tu contraseña?</a>
                            <?php elseif(isset($_GET['code']) && intval($_GET['code']) === 2): ?>
                                <br><a href="contacto.php" class="recovery-link">Contactar al administrador</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <input type="text" placeholder="Nombre de Usuario" name="username" required>
                    <input type="password" placeholder="Contraseña" name="password" required>
                    <button type="submit" class="btn_lg">Iniciar Sesión</button>
                </form>
            </div>
            
            <!--Recuperar Contraseña-->
            <div class="contenedor_recovery_password">
                <form action="recuperar_contrasena.php" method="POST" class="form__recovery">
                    <h2 class="h2_recovery">Recuperar Contraseña</h2>
                    <input type="email" placeholder="Correo Electrónico" name="email" required>
                    <input type="text" placeholder="Nombre de Usuario" name="userName" required>
                    <input type="password" placeholder="Nueva Contraseña" name="password" required>
                    <button type="submit" class="btn_rc">Recuperar</button>
                </form>
            </div>
        </div>
    </main>
    <script src="assets/js/script.js"></script>
    <script>
        // Función para mostrar el formulario de recuperación
        function mostrarRecuperacion() {
            document.querySelector('.contenedor_login').style.display = 'none';
            document.querySelector('.contenedor_recovery_password').style.display = 'block';
            document.querySelector('.caja__trasera-login').style.display = 'none';
            document.querySelector('.caja__trasera-recovery_password').style.display = 'block';
        }
        
        // Mostrar formulario de recuperación si viene de un error de contraseña
        <?php if(isset($_GET['code']) && intval($_GET['code']) === 1): ?>
            document.addEventListener('DOMContentLoaded', function() {
                mostrarRecuperacion();
            });
        <?php endif; ?>
    </script>
</body>
</html>