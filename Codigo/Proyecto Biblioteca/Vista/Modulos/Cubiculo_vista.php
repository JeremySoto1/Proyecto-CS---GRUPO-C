<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cubiculos</title>
    <link rel="stylesheet" href="../../assets/CSS/cubiculo.css?v=2">
    <link rel="stylesheet" href="../../assets/CSS/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
  </head>
  <body>
  
  <?php include '../Helpers/templates/sidebar.php';; ?>
  
  <div class="main-content">
    <div class="header">
            <h1>Gestión de Cubiculos</h1>
            <div class="user-info">
                <span><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></span>
                <a href="../../index.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>

        <div class="content">
          <div id="alert-container"></div>
    <div class="cubiculos-container">
      <button calss="btn-agregar" onclick="mostrarFormularioCubiculo()">Agregar</button>

         <!-- Formulario de Registro de Cubículo -->
      <div id="form-cubiculo" style="display: none">
        <h3>Nuevo Cubículo</h3>
        <form onsubmit="guardarCubiculo(event)">
          <input type="text" name="nombre" placeholder="Nombre" required />
          <input
            type="text"
            name="equipamento"
            placeholder="Equipamiento"
            required
          />
          <input
            type="number"
            name="capacidad"
            placeholder="Capacidad"
            min="1"
            required
          />
          <button type="submit">Guardar</button>
          <button >Cancelar</button>
        </form>
      </div>

     <!-- Lista de cubículos -->
      <div id="cubiculos-lista"></div>

      <!-- Ventana emergente para alquiler -->
      <div id="modal-alquiler" class="modal" style="display: none">
        <div class="modal-content">
          <h3>Nuevo Alquiler</h3>
          <div>
            <p><strong>Nombre:</strong> <span id="alq-nombre"></span></p>
            <p>
              <strong>Equipamiento:</strong> <span id="alq-equipamento"></span>
            </p>
            <p><strong>Capacidad:</strong> <span id="alq-capacidad"></span></p>
          </div>
          <div>
            <label for="cedula-buscar">Buscar lector por cédula:</label>
            <input type="text" id="cedula-buscar" />
            <button onclick="buscarLector()">Buscar</button>
            <div id="lector-encontrado"></div>
          </div>
          <button onclick="guardarAlquiler()">Realizar alquiler</button>
          <button onclick="cerrarModal()">Cancelar</button>
        </div>
      </div>
    </div>
        </div>
  </div>
  
  </div>
    <script src="../../assets/js/cubiculos.js"></script>
     <script src="../../assets/js/sidebar.js"></script> 
      <script>
        // Función para mostrar alertas
        function mostrarAlerta(mensaje, tipo = 'danger') {
            const alertContainer = document.getElementById('alert-container');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${tipo}`;
            alertDiv.textContent = mensaje;
            alertContainer.appendChild(alertDiv);
            
            // Eliminar la alerta después de 3 segundos
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
    </script>
  </body> 
</html>
