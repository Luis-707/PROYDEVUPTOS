<?php
include_once "Sesion.php";
?>

<style>
.section-title {
    background-color: #f8f9fa;
    font-weight: 600;
}
.small-text {
    font-size: .85rem;
}
.table-eval th, .table-eval td {
    vertical-align: middle;
}
.table-success {
    background-color: #d4edda !important;
}
</style>

<div class="container my-4">

    <div class="card shadow-sm mb-4">
        <div class="card-header text-center">
            <h5 class="mb-0">OFICINA DE GESTIÓN HUMANA</h5>
            <p class="mb-0 small-text">Resultados de Evaluación de Desempeño Laboral - Nivel Obrero</p>
        </div>

        <div class="text-start mb-3">
        <button class="btn btn-outline-primary btn-sm"
            onclick="mostrarVista('resultados_obreros'); listarEvaluadosResultadosObreros();">
            ← Volver al listado
        </button>
        </div>

        <div class="card-body small-text">

            <!-- ============================= -->
            <!-- SECCIÓN A: DATOS DEL EVALUADO -->
            <!-- ============================= -->
            <h6 class="section-title p-2 mb-2">Datos del Evaluado</h6>

            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label mb-0">Apellidos y Nombres</label>
                    <input type="text" class="form-control" id="evaluado_fullname" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0">Cédula</label>
                    <input type="text" class="form-control" id="evaluado_cedula" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0">Cargo</label>
                    <input type="text" class="form-control" id="evaluado_cargo" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label mb-0">Fecha de Ingreso</label>
                    <input type="text" class="form-control" id="evaluado_fecha_ingreso" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label mb-0">Años en el puesto</label>
                    <input type="text" class="form-control" id="evaluado_anios_puesto" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label mb-0">Ubicación Administrativa</label>
                    <input type="text" class="form-control" id="evaluado_ubicacion" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label mb-0">Área Ocupacional</label>
                    <input type="text" class="form-control" id="evaluado_area_ocupacional" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label mb-0">Ubicación Física</label>
                    <input type="text" class="form-control" id="evaluado_ubicacion_fisica" readonly>
                </div>
            </div>

            <!-- ============================= -->
            <!-- SECCIÓN B: DATOS DEL EVALUADOR -->
            <!-- ============================= -->
            <h6 class="section-title p-2 mb-2">Datos del Evaluador</h6>

            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label mb-0">Apellidos y Nombres</label>
                    <input type="text" class="form-control" id="evaluador_fullname" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0">Cédula</label>
                    <input type="text" class="form-control" id="evaluador_cedula" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0">Ubicación Administrativa</label>
                    <input type="text" class="form-control" id="evaluador_ubicacion" readonly>
                </div>
            </div>

            <!-- ============================= -->
            <!-- SECCIÓN C: FACTORES -->
            <!-- ============================= -->
            <h6 class="section-title p-2 mb-2">Factores Evaluados</h6>

            <div class="table-responsive mb-3 small-text">
                <table class="table table-bordered table-sm table-eval" id="tabla-factores-readonly">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px;">Código</th>
                            <th>Descripción</th>
                            <th>Peso</th>
                            <th>Puntaje</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <!-- ============================= -->
            <!-- SECCIÓN D: RESULTADO FINAL -->
            <!-- ============================= -->
            <h6 class="section-title p-2 mb-2">Resultado Final</h6>

            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <label class="form-label mb-0">Puntaje Final</label>
                    <input type="text" class="form-control" id="puntaje-total" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0">Rango de Calificación</label>
                    <input type="text" class="form-control" id="rango-calificacion" readonly>
                </div>
            </div>

            <!-- ============================= -->
            <!-- SECCIÓN E: COMENTARIOS -->
            <!-- ============================= -->
            <h6 class="section-title p-2 mb-2">Comentarios</h6>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Comentario del Supervisor</th>
                        <td id="comentario_supervisor_text"></td>
                    </tr>
                    <tr>
                        <th>Comentario del Evaluado</th>
                        <td id="comentario_evaluado_text"></td>
                    </tr>
                    <tr>
                        <th>Conformidad del Evaluado</th>
                        <td id="conformidad_text"></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script src="views/js/planilla_resultados_obrero.js"></script>
