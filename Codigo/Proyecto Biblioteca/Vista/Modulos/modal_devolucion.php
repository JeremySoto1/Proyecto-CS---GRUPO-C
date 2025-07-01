<!-- Modal Devolución -->
<div id="devolucionModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalDevolucion()">&times;</span>
        <form id="form_devolucion" action="../../Controlador/Modulos/devolucion_controlador.php" method="POST">
            <input type="hidden" id="prestamoID_devolucion" name="prestamoID">
            <input type="hidden" id="existenciaID_devolucion" name="existenciaID">
            
            <h2>Registrar Devolución</h2>
            
            <div class="form-group">
                <label for="estado_existencia">Estado del Libro:</label>
                <select id="estado_existencia" name="estado_existencia" required onchange="mostrarMotivo()">
                    <option value="">Seleccione...</option>
                    <option value="1">Dañado</option>
                    <option value="2">Buena</option>
                    <option value="3">Excelente</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="disponibilidad">Disponibilidad:</label>
                <select id="disponibilidad" name="disponibilidad" required>
                    <option value="">Seleccione...</option>
                    <option value="1">Disponible</option>
                    <option value="2">No disponible</option>
                </select>
            </div>
            
            <div id="motivoContainer" style="display:none;">
                <label for="motivo_multa">Motivo del daño:</label>
                <textarea id="motivo_multa" name="motivo_multa" rows="3"></textarea>
            </div>
            
            <button type="submit">Registrar Devolución</button>
            <button type="button" onclick="cerrarModalDevolucion()">Cancelar</button>
        </form>
    </div>
</div>