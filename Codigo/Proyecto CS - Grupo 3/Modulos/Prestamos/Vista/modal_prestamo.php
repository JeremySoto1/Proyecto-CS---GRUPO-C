<div id="prestamoModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <form id="form_prestamo" action="../Controlador/prestamo_formulario_controlador.php" method="POST">
            <input type="hidden" name="guardar" value="1">
            <input type="hidden" name="lectorID" id="lectorID">
            
            <h2>Información del Lector</h2>
            <fieldset>
                <label>Cédula:</label>
                <input type="text" id="cedula" required>
                <button type="button" onclick="buscarLector()">Buscar Lector</button>
                
                <p>Nombre: <span id="lectorNombre">-</span></p>
                <p>Email: <span id="lectorEmail">-</span></p>
            </fieldset>
            
            <h2>Seleccione Existencia del Libro</h2>
            <div class="search-container">
                <input type="text" id="busquedaLibro" placeholder="Buscar por título o ID de libro">
                <button type="button" onclick="buscarLibros()">Buscar</button>
            </div>
                                    
            <div id="resultadosLibros">
                <table>
                    <thead>
                        <tr>
                            <th>ExistenciaID</th>
                            <th>Título</th>
                            <th>Sección</th>
                            <th>Pasillo</th>
                            <th>Estantería</th>
                            <th>Nivel</th>
                            <th>Estado</th>
                            <th>Disponibilidad</th>
                            <th>Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody id="tablaExistencias">
                        <!-- Aquí se cargarán los resultados -->
                    </tbody>
                </table>
            </div>

            <h2>Fechas</h2>
            <label>Fecha Préstamo:</label>
            <input type="date" name="fecha_prestamo" required>

            <label>Fecha Límite:</label>
            <input type="date" name="fecha_limite" required>

            <button type="submit">Guardar Préstamo</button>
            <button type="button" onclick="cerrarModal()">Cancelar</button>
        </form>
    </div>
</div>
