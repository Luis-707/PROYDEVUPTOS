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
</style>

<div class="container my-4">

    <div class="card shadow-sm mb-4">
        <div class="card-header text-center">
            <h5 class="mb-0">OFICINA DE GESTIÓN HUMANA</h5>
            <p class="mb-0 small-text">Comentarios de Evaluación de Desempeño Laboral - Nivel Obrero</p>
        </div>

        <div class="card-body small-text">

            <!-- ============================= -->
            <!-- SECCIÓN A: DATOS DEL EVALUADO -->
            <!-- ============================= -->
            <h6 class="section-title p-2 mb-2">Datos del Evaluado</h6>

            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label mb-0">Apellidos y Nombres</label>
                    <input type="text" class="form-control" id="evaluado_fullname" name="evaluado_fullname" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0">Cédula</label>
                    <input type="text" class="form-control" id="evaluado_cedula" name="evaluado_cedula" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0">Cargo</label>
                    <input type="text" class="form-control" id="evaluado_cargo" name="evaluado_cargo" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label mb-0">Años en el puesto</label>
                    <input type="text" class="form-control" id="evaluado_anios_puesto" name="evaluado_anios_puesto" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label mb-0">Ubicación Administrativa</label>
                    <input type="text" class="form-control" id="evaluado_ubicacion" name="evaluado_ubicacion" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0">Área Ocupacional</label>
                    <input type="text" class="form-control" id="evaluado_area_ocupacional" name="evaluado_area_ocupacional" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0">Ubicación Física</label>
                    <input type="text" class="form-control" id="evaluado_ubicacion_fisica" name="evaluado_ubicacion_fisica" readonly>
                </div>
            </div>

            <!-- ============================= -->
            <!-- SECCIÓN B: DATOS DEL EVALUADOR -->
            <!-- ============================= -->
            <h6 class="section-title p-2 mb-2">Datos del Evaluador</h6>

            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label mb-0">Apellidos y Nombres</label>
                    <input type="text" class="form-control" id="evaluador_fullname" name="evaluador_fullname" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0">Cédula</label>
                    <input type="text" class="form-control" id="evaluador_cedula" name="evaluador_cedula" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-0">Ubicación Administrativa</label>
                    <input type="text" class="form-control" id="evaluador_ubicacion" name="evaluador_ubicacion" readonly>
                </div>
            </div>

            <!-- ============================= -->
            <!-- SECCIÓN C: FACTORES Y CRITERIOS -->
            <!-- ============================= -->
            <h6 class="section-title p-2 mb-2">Factores Evaluados</h6>

            <div class="table-responsive mb-3">
                <table class="table table-bordered table-sm table-eval" id="tabla-factores-readonly">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Factor</th>
                            <th>Criterio</th>
                            <th>Valor</th>
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
                    <input type="text" class="form-control" id="puntaje-total" name="puntaje_total" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-0">Rango de Calificación</label>
                    <input type="text" class="form-control" id="rango-calificacion" name="rango_calificacion" readonly>
                </div>
            </div>

            <!-- ============================= -->
            <!-- SECCIÓN E: COMENTARIOS -->
            <!-- ============================= -->
            <h6 class="section-title p-2 mb-2">Comentarios</h6>

            <!-- Comentario Evaluado -->
            <div class="mb-4" id="contenedor-evaluado">
                <label class="form-label">Comentario del Evaluado</label>
                <form id="form_comentario_evaluado_obrero" onsubmit="event.preventDefault(); Validar_form_comentario_evaluado_obrero(1);">
                    <textarea id="comentario_evaluado" name="comentario_evaluado" class="form-control mb-3" rows="4"></textarea>

                    <label class="form-label">¿Está de acuerdo con la evaluación?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="conformidad" id="conformidad_si" value="si">
                        <label class="form-check-label" for="conformidad_si">Sí</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="conformidad" id="conformidad_no" value="no">
                        <label class="form-check-label" for="conformidad_no">No</label>
                    </div>

                    <input type="hidden" id="id_eval_obrero_eval" name="id_eval_obreros">
                    <button type="submit" class="btn btn-primary">Guardar Comentario</button>
                </form>
            </div>

            <!-- Comentario Supervisor -->
            <div id="contenedor-supervisor">
                <label class="form-label">Comentario del Supervisor</label>
                <form id="form_comentario_supervisor_obrero" onsubmit="event.preventDefault(); Validar_form_comentario_supervisor_obrero(1);">
                    <textarea id="comentario_supervisor" name="comentario_supervisor" class="form-control mb-3" rows="4"></textarea>

                    <input type="hidden" id="id_eval_obrero_sup" name="id_eval_obreros">
                    <button type="submit" class="btn btn-primary">Guardar Comentario</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="views/js/planilla_comentarios_obrero.js"></script>