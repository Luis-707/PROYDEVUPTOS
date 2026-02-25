// =============================
// Variables globales
// =============================
let factoresObrero = [];
let rangosCalificacion = [];
let seleccionPrevias = [];
let salirDeVista = false;

// =============================
// Validaciones
// =============================
function validarnumero(n) {
    return /^[0-9]+$/.test(n);
}

function validar_form_editar_obrero(opc) {
    if (salirDeVista) return;

    const form = document.getElementById("formulario_planilla_obrero_editar");
    const data = new FormData(form);
    let ok = true;

    for (let [k, v] of data.entries()) {
        if (!v) console.warn("Campo vacío:", k);

        if (k === "puntaje_total" && !validarnumero(v)) ok = false;
        if (k === "rango_id" && !validarnumero(v)) ok = false;
    }

    if (ok && opc === 1) actualizarEvaluacionObrero();
}

// =============================
// Cargar datos del evaluado/evaluador
// =============================
async function cargarPlanillaObreroEditar() {

    salirDeVista = false;

    const cedula = sessionStorage.getItem("cedula_planilla_obrero");
    const idEval = sessionStorage.getItem("id_eval_obreros");

    const fd = new FormData();
    fd.append("cedula_usuario", cedula);
    fd.append("id_eval_obreros", idEval);

    const resp = await microApi("controlador/?planilla_editar_obreros", fd);

    if (!resp?.success) {
        alert(resp.message);
        return;
    }

    const r = resp.data;

    document.getElementById("evaluado_nombre").value = r.nombre_completo;
    document.getElementById("evaluado_ci").value = r.cedula_usuario;
    document.getElementById("evaluado_cargo").value = r.cargo_evaluado;
    document.getElementById("fecha_ingreso").value = r.fecha_ingreso;
    document.getElementById("ubicacion_admin").value = r.ubicacion_administrativa;
    document.getElementById("ubicacion_fisica").value = r.ubicacion_fisica;
    document.getElementById("periodo").value = r.periodo_evaluacion;
    document.getElementById("area_ocupacional").value = r.area_ocupacional;

    document.getElementById("evaluador_nombre").value = r.nombre_completo_evaluador;
    document.getElementById("evaluador_cargo").value = r.cargo_evaluador;
    document.getElementById("evaluador_ubicacion").value = r.ubicacion_evaluador;

    document.getElementById("evaluado_id").value = r.evaluado_id;
    document.getElementById("id_eval_obreros").value = r.id_eval_obreros;
    document.getElementById("tiempo_puesto").value = r.tiempo_puesto;
}

// =============================
// Cargar factores y criterios
// =============================
async function cargarFactoresObreroEditar() {

    const resp = await microApi("controlador/?l_factores_criterios_obreros");
    if (!resp?.success) return;

    factoresObrero = resp.data;

    // Cargar rangos
    const respR = await microApi("controlador/?l_rangos_calificacion");
    rangosCalificacion = respR.data;

    // Cargar criterios seleccionados previamente
    await cargarSeleccionadosObrero();

    renderFactoresObreroEditar();
    recalculateScoresEditar();
}

// =============================
// Cargar criterios seleccionados previamente
// =============================
async function cargarSeleccionadosObrero() {

    const idEval = sessionStorage.getItem("id_eval_obreros");

    const fd = new FormData();
    fd.append("id_eval_obreros", idEval);

    const resp = await microApi("controlador/?l_seleccionados_obreros", fd);

    if (resp?.success) {
        seleccionPrevias = resp.data;
    }
}

// =============================
// Render tabla con selección previa
// =============================
function renderFactoresObreroEditar() {

    const tbody = document.querySelector(".table-eval tbody");
    tbody.innerHTML = "";

    factoresObrero.forEach(f => {

        const trHeader = document.createElement("tr");
        trHeader.className = "factor-title";
        trHeader.innerHTML = `
            <td colspan="5">
                <strong>${f.nombre_factor}</strong> (${f.valor_factor}%) -
                <span class="fw-normal">Puntaje factor: </span>
                <span class="factor-score" id="score-${f.factor_id}">0</span>
            </td>
        `;
        tbody.appendChild(trHeader);

        f.criterios.forEach((c, index) => {

            const tr = document.createElement("tr");

            const checked = seleccionPrevias.some(s => s.criterio_id == c.criterio_id)
                ? "checked"
                : "";

            if (index === 0) {
                tr.innerHTML = `
                    <td><strong>${c.codigo_criterio}</strong></td>
                    <td>${c.descripcion_criterio}</td>
                    <td rowspan="${f.criterios.length}" class="factor-weight text-center align-middle">
                        ${f.valor_factor}%
                    </td>
                    <td class="col-puntaje text-center">${c.valor_criterio}</td>
                    <td class="text-center">
                        <input type="checkbox" class="form-check-input factor-option"
                            data-factor="${f.factor_id}"
                            data-criterio="${c.criterio_id}"
                            data-score="${c.valor_criterio}"
                            ${checked}>
                    </td>
                `;
            } else {
                tr.innerHTML = `
                    <td><strong>${c.codigo_criterio}</strong></td>
                    <td>${c.descripcion_criterio}</td>
                    <td class="col-puntaje text-center">${c.valor_criterio}</td>
                    <td class="text-center">
                        <input type="checkbox" class="form-check-input factor-option"
                            data-factor="${f.factor_id}"
                            data-criterio="${c.criterio_id}"
                            data-score="${c.valor_criterio}"
                            ${checked}>
                    </td>
                `;
            }

            tbody.appendChild(tr);
        });
    });

    document.querySelectorAll(".factor-option").forEach(opt => {
        opt.addEventListener("change", () => {
            const factor = opt.dataset.factor;
            document.querySelectorAll(`.factor-option[data-factor="${factor}"]`)
                .forEach(o => { if (o !== opt) o.checked = false; });
            recalculateScoresEditar();
        });
    });
}

// =============================
// Recalcular puntajes
// =============================
function recalculateScoresEditar() {

    const factorScores = {};

    document.querySelectorAll(".factor-option").forEach(opt => {
        const factor = opt.dataset.factor;
        const score = Number(opt.dataset.score);

        if (!factorScores[factor]) factorScores[factor] = 0;
        if (opt.checked) factorScores[factor] = score;
    });

    document.querySelectorAll(".factor-score").forEach(span => {
        const id = span.id.replace("score-", "");
        span.textContent = factorScores[id] || 0;
    });

    let total = 0;
    Object.values(factorScores).forEach(v => total += v);

    document.getElementById("total-score").value = total;

    let rangoId = 0;
    let rangoNombre = "Sin calificación";

    rangosCalificacion.forEach(r => {
        if (total >= r.puntaje_min && total <= r.puntaje_max) {
            rangoId = r.rango_id;
            rangoNombre = r.nombre_rango;
        }
    });

    document.getElementById("rango_id").value = rangoId;
    document.getElementById("label-eval").textContent = rangoNombre;
}

// =============================
// Actualizar evaluación
// =============================
async function actualizarEvaluacionObrero() {

    const form = document.getElementById("formulario_planilla_obrero_editar");
    const datos = new FormData(form);

    const seleccion = [];
    document.querySelectorAll(".factor-option:checked").forEach(opt => {
        seleccion.push({
            criterio_id: parseInt(opt.dataset.criterio),
            puntaje_obtenido: parseInt(opt.dataset.score)
        });
    });

    datos.append("seleccion", JSON.stringify(seleccion));

    const resp = await microApi("controlador/?a_evaluacion_obreros", datos);

    if (!resp?.success) {
        Swal.fire({ icon: "error", title: "Error", text: resp.message });
        return;
    }

    Swal.fire({
        icon: "success",
        title: "Evaluación actualizada",
        text: "La evaluación del obrero fue actualizada correctamente"
    });
}
