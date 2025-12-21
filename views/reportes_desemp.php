<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Reportes de Evaluador</h2>
        <button class="btn btn-danger btn-pdf" onclick="generarPDFReportes()">
            <i class="bi bi-file-earmark-pdf me-1"></i> 📄 Descargar PDF
        </button>
    </div>
        <table id="tabla-reportes" class="table table-bordered align-middle display" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Periodo</th>
                        <th>Año</th>
                        <th>Puntaje Final</th>
                        <th>Rango Actuación</th>
                    </tr>
                </thead>
            <tbody>
                    <!-- Las filas se llenan dinámicamente con JavaScript -->
            </tbody>
        </table>
 </div>