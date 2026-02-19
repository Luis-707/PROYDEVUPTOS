<style>
    .section-title { background-color: #f8f9fa; font-weight: 600; }
    .factor-title { background-color: #e9ecef; font-weight: 600; }
    .small-text { font-size: .85rem; }
    .factor-weight { width: 90px; text-align: center; white-space: nowrap; }
    .col-puntaje { width: 90px; text-align: center; }
    .table-eval th, .table-eval td { vertical-align: middle; }
    .badge-eval { font-size: 0.9rem; }
    .factor-incompleto {
        background-color: #ffe5e5 !important;
        border-left: 4px solid #dc3545 !important;
    }
</style>

<div class="container my-4">
    <form id="formulario_planilla_obrero" onsubmit="event.preventDefault(); validar_form_evaluacion_obrero(1);">
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h5 class="mb-0">OFICINA DE GESTIÓN HUMANA</h5>
                <p class="mb-0 small-text">Instrumento de Evaluación de Desempeño Laboral - Nivel Obrero</p>
            </div>

            <!-- Campos ocultos -->
            <input type="hidden" id="evaluado_id" name="evaluado_id">
            <input type="hidden" id="rango_id" name="rango_id">
            <input type="hidden" id="id_eval_obreros" name="id_eval_obreros">
            <input type="hidden" id="tiempo_puesto" name="tiempo_puesto">


            <button class="btn btn-outline-primary btn-sm"
        onclick="mostrarVista('evaluacion_obreros'); listarEvaluacionesObreros();">
    ← Volver al listado
</button>


            <div class="card-body">

                <!-- Instrucciones -->
                <div class="mb-3">
                    <h6 class="section-title p-2 mb-2">Instrucciones</h6>
                    <ul class="small-text mb-0">
                        <li>La evaluación debe ser realizada por el supervisor inmediato.</li>
                        <li>Evalúe cada factor de manera independiente del resto.</li>
                        <li>Seleccione una sola alternativa por factor.</li>
                        <li>El sistema calculará automáticamente el puntaje total y la evaluación general.</li>
                    </ul>
                </div>

                <!-- Datos del Evaluado -->
                <h6 class="section-title p-2 mb-2">Datos del Evaluado</h6>
                <div class="row g-2 mb-3 small-text">
                    <div class="col-md-6">
                        <label class="form-label mb-0">Apellidos y Nombres</label>
                        <input type="text" class="form-control" id="evaluado_nombre" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0">Cédula de Identidad</label>
                        <input type="text" class="form-control" id="evaluado_ci" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0">Cargo</label>
                        <input type="text" class="form-control" id="evaluado_cargo" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0">Fecha de Ingreso</label>
                        <input type="text" class="form-control" id="fecha_ingreso" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0">Años en el puesto</label>
                        <input type="text" class="form-control" id="tiempo_puesto_visible" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0">Ubicación administrativa</label>
                        <input type="text" class="form-control" id="ubicacion_admin" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0">Ubicación física</label>
                        <input type="text" class="form-control" id="ubicacion_fisica" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0">Período a evaluar</label>
                        <input type="text" class="form-control" id="periodo" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-0">Área ocupacional</label>
                        <input type="text" class="form-control" id="area_ocupacional" readonly>
                    </div>
                </div>

                <!-- Datos del Evaluador -->
                <h6 class="section-title p-2 mb-2">Datos del Evaluador</h6>
                <div class="row g-2 mb-3 small-text">
                    <div class="col-md-6">
                        <label class="form-label mb-0">Apellidos y Nombres</label>
                        <input type="text" class="form-control" id="evaluador_nombre" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0">Cargo</label>
                        <input type="text" class="form-control" id="evaluador_cargo" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0">Ubicación administrativa</label>
                        <input type="text" class="form-control" id="evaluador_ubicacion" readonly>
                    </div>
                </div>

                <!-- Tabla de factores -->
                <h6 class="section-title p-2 mb-2">Factores Evaluados</h6>
                <div class="table-responsive mb-3 small-text">
                    <table class="table table-bordered table-sm table-eval">
                        <thead class="table-light">
                        <tr>
                            <th style="width:60px;">Código</th>
                            <th>Descripción</th>
                            <th class="factor-weight">Peso</th>
                            <th class="col-puntaje">Puntaje</th>
                            <th style="width:80px;">Selección</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Total y evaluación general -->
                <div class="row g-2 mb-3 small-text">
                    <div class="col-md-4">
                        <label class="form-label mb-0">Total puntaje obtenido</label>
                        <input type="number" class="form-control" id="total-score" name="puntaje_total" readonly>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label mb-1">Evaluación general del trabajador</label>
                        <div class="mt-1">
                            <span id="label-eval" class="badge badge-eval bg-secondary">Sin calificación</span>
                        </div>
                    </div>
                </div>

                <!-- Botón Guardar -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Guardar evaluación</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="views/js/planilla_obreros.js"></script>

