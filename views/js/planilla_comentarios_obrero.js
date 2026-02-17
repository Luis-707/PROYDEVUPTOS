// =============================
// Cargar planilla obrera readonly
// =============================
async function cargarPlanillaObreroReadonly() {

    const cedula = sessionStorage.getItem("cedula_planilla_obrero");
    const idEvalOb = sessionStorage.getItem("id_eval_obreros");

    const formData = new FormData();
    formData.append("cedula_usuario", cedula);
    formData.append("id_eval_obreros", idEvalOb);

    console.log("cedula_planilla_obrero:", sessionStorage.getItem("cedula_planilla_obrero"));
console.log("id_eval_obreros:", sessionStorage.getItem("id_eval_obreros"));

    const resp = await microApi("controlador/?planilla_comentarios_obrero", formData);

    if (!resp || resp.success !== true) {
        alert(resp?.message || "Error cargando planilla obrera");
        return;
    }

    console.log("Datos recibidos para planilla obrera readonly:", resp.data);
    console.log("Relaciones:", resp.data.relaciones);

    renderizarPlanillaObreroReadonly(resp.data);
}

// =============================
// Renderizar datos
// =============================
function renderizarPlanillaObreroReadonly(data) {

    const rel = data.relaciones;
    const evalData = data.evaluacion;

    // =============================
    // DATOS DEL EVALUADO
    // =============================
    document.getElementById("evaluado_fullname").value = rel.nombre_evaluado;
    document.getElementById("evaluado_cedula").value = rel.cedula_evaluado;
    document.getElementById("evaluado_cargo").value = rel.cargo_evaluado;

    document.getElementById("evaluado_fecha_ingreso").value = rel.fecha_ingreso;
    document.getElementById("evaluado_anios_puesto").value = rel.tiempo_puesto;

    document.getElementById("evaluado_ubicacion").value = rel.ubicacion_evaluado;

    // 🔹 AHORA área ocupacional viene de ORGANIZACIONES
    document.getElementById("evaluado_area_ocupacional").value = rel.area_ocupacional;

    document.getElementById("evaluado_ubicacion_fisica").value = rel.ubicacion_fisica;

    // =============================
    // DATOS DEL EVALUADOR
    // =============================
    document.getElementById("evaluador_fullname").value = rel.nombre_evaluador;
    document.getElementById("evaluador_cedula").value = rel.cedula_evaluador;
    document.getElementById("evaluador_ubicacion").value = rel.ubicacion_evaluador;

    // =============================
    // TABLA DE FACTORES
    // =============================
    const tbody = document.querySelector("#tabla-factores-readonly tbody");
    tbody.innerHTML = "";

    const factores = data.factores;
    const criterios = data.criterios;
    const seleccionados = data.seleccionados;

    const mapSel = {};
    seleccionados.forEach(s => mapSel[s.criterio_id] = s.puntaje_obtenido);

    factores.forEach(f => {

        tbody.innerHTML += `
            <tr class="table-light">
                <td colspan="4"><strong>${f.nombre_factor}</strong> (${f.valor_factor}%)</td>
            </tr>
        `;

        const crit = criterios.filter(c => c.factor_id == f.factor_id);

        crit.forEach(c => {
            tbody.innerHTML += `
                <tr>
                    <td>${c.codigo_criterio}</td>
                    <td>${c.descripcion_criterio}</td>
                    <td>${f.valor_factor}%</td>
                    <td>${mapSel[c.criterio_id] ?? "-"}</td>
                </tr>
            `;
        });
    });

    // =============================
    // RESULTADO FINAL
    // =============================
    document.getElementById("puntaje-total").value = evalData.puntaje_total;
    document.getElementById("rango-calificacion").value = evalData.nombre_rango;

    // =============================
    // COMENTARIOS
    // =============================
    document.getElementById("comentario_evaluado").value = evalData.comentario_evaluado || "";
    document.getElementById("comentario_supervisor").value = evalData.comentario_supervisor || "";

    if (evalData.conformidad === "si") document.getElementById("conformidad_si").checked = true;
    if (evalData.conformidad === "no") document.getElementById("conformidad_no").checked = true;

    document.getElementById("id_eval_obrero_eval").value = evalData.id_eval_obreros;
    document.getElementById("id_eval_obrero_sup").value = evalData.id_eval_obreros;

    habilitarCamposPorRolObrero();
}

// =============================
// NUEVA LÓGICA DE ROLES (igual a administrativos)
// =============================
function obtenerRolesSesion() {
    try {
        return JSON.parse(sessionStorage.getItem("roles")) || [];
    } catch {
        return [];
    }
}

function habilitarCamposPorRolObrero() {

    const rolesSesion = obtenerRolesSesion();

    // Ocultar ambos por defecto
    document.getElementById("form_comentario_evaluado_obrero").style.display = "none";
    document.getElementById("form_comentario_supervisor_obrero").style.display = "none";

    // Evaluado
    if (rolesSesion.includes("evaluado")) {
        document.getElementById("form_comentario_evaluado_obrero").style.display = "block";
        document.getElementById("comentario_evaluado").removeAttribute("readonly");
    }

    // Supervisor del evaluador
    if (rolesSesion.includes("supervisor del evaluador")) {
        document.getElementById("form_comentario_supervisor_obrero").style.display = "block";
        document.getElementById("comentario_supervisor").removeAttribute("readonly");
    }
}

// =============================
// Inicializar
// =============================
cargarPlanillaObreroReadonly();
