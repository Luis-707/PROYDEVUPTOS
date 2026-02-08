// =============================
// Formatear fecha (DD/MM/YYYY)
// =============================
function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr);
    if (isNaN(fecha.getTime())) return "";
    const dia = String(fecha.getDate()).padStart(2, '0');
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const año = fecha.getFullYear();
    return `${dia}/${mes}/${año}`;
}

// =============================
// Obtener fecha de ingreso desde JSON (async)
// =============================
async function obtenerFechaIngresoPorCedula(cedula) {
    try {
        const resp = await microApi('views/js/datos_empleado.json');

        if (!Array.isArray(resp) || resp.length === 0 || !resp[0].data) {
            console.error("JSON con formato inesperado");
            return "";
        }

        const datos = resp[0].data;
        const cedulaBusqueda = cedula.toLowerCase();

        const empleado = datos.find(emp =>
            emp.pin && emp.pin.toLowerCase() === cedulaBusqueda
        );

        if (!empleado || !empleado.create) {
            console.warn("Empleado no encontrado o sin fecha 'create'");
            return "";
        }

        const soloFecha = empleado.create.split(" ")[0];
        return formatearFecha(soloFecha);

    } catch (error) {
        console.error("Error obteniendo fecha de ingreso:", error);
        return "";
    }
}

// =============================
// Cargar planilla obrera readonly
// =============================
async function cargarPlanillaObreroReadonly() {

    const cedula = sessionStorage.getItem("cedula_planilla_obrero");
    const idEval = sessionStorage.getItem("id_eval_obreros");

    if (!cedula) {
        alert("No se seleccionó evaluado");
        return;
    }

    const formData = new FormData();
    formData.append("cedula_usuario", cedula);
    formData.append("id_eval_obreros", idEval);

    const resp = await microApi('controlador/?planilla_comentarios_obrero', formData);

    // Obtener fecha de ingreso desde JSON
resp.data.evaluacion.fecha_ingreso = await obtenerFechaIngresoPorCedula(cedula);


    if (!resp?.success) {
        alert(resp?.message || "Error cargando planilla obrera");
        return;
    }

    console.log("DEBUG evaluacion obrero:", resp.data.evaluacion);
    console.log("DEBUG factores completos:", resp.data.factores);
    console.log("DEBUG criterios completos:", resp.data.criterios);
    console.log("DEBUG criterios seleccionados:", resp.data.seleccionados);

    renderizarPlanillaObreroReadonly(resp.data);
}

// =============================
// Renderizar datos
// =============================
function renderizarPlanillaObreroReadonly(data) {

    const rel = data.relaciones;
    const evalData = data.evaluacion;

    const factores = data.factores;          
    const criterios = data.criterios;        
    const seleccionados = data.seleccionados;

    // =============================
    // MAPA DE CRITERIOS SELECCIONADOS
    // =============================
    const seleccionadosMap = {};
    seleccionados.forEach(s => {
        seleccionadosMap[s.criterio_id] = s.puntaje_obtenido;
    });

    // =============================
    // DATOS DEL EVALUADO
    // =============================
    document.getElementById("evaluado_fullname").value = rel.nombre_completo_evaluado || "N/D";
    document.getElementById("evaluado_cedula").value = rel.cedula_usuario || "N/D";
    document.getElementById("evaluado_cargo").value = rel.cargo_evaluado || "N/D";
    document.getElementById("evaluado_fecha_ingreso").value = evalData.fecha_ingreso || "N/D";
    document.getElementById("evaluado_ubicacion").value = rel.ubicacion_evaluado || "N/D";
    document.getElementById("evaluado_area_ocupacional").value = rel.nombre_ao || "N/D";
    document.getElementById("evaluado_ubicacion_fisica").value = rel.nombre_uf || "N/D";
    document.getElementById("evaluado_anios_puesto").value = evalData.tiempo_puesto || "N/D";

    // =============================
    // DATOS DEL EVALUADOR
    // =============================
    document.getElementById("evaluador_fullname").value = rel.nombre_completo_evaluador || "N/D";
    document.getElementById("evaluador_cedula").value = rel.cedula_evaluador || "N/D";
    document.getElementById("evaluador_ubicacion").value = rel.ubicacion_evaluador || "N/D";

// =============================
// TABLA DE FACTORES Y CRITERIOS (CORREGIDA)
// =============================
const tbody = document.querySelector("#tabla-factores-readonly tbody");
tbody.innerHTML = "";

factores.forEach(factor => {

    // =============================
    // ENCABEZADO DEL FACTOR (SEPARACIÓN VISUAL)
    // =============================
    tbody.innerHTML += `
        <tr class="factor-title" style="border-top: 3px solid #adb5bd;">
            <td colspan="4">
                <strong>${factor.nombre_factor}</strong> (${factor.valor_factor}%)
                - <span class="fw-normal">Puntaje factor:</span>
                <span class="factor-score">${factor.puntaje_factor ?? 0}</span>
            </td>
        </tr>
    `;

    // =============================
    // CRITERIOS DEL FACTOR
    // =============================
    let criteriosFactor = criterios.filter(c => c.factor_id == factor.factor_id);

    criteriosFactor.sort((a, b) => a.codigo_criterio.localeCompare(b.codigo_criterio));

    criteriosFactor.forEach((c, index) => {

        const seleccionado = seleccionadosMap[c.criterio_id] !== undefined;

        // Icono ✔️ visible
        const icono = seleccionado
            ? `<span style="font-size:1.2rem; color:#198754; font-weight:bold;">✔️</span>`
            : "";

        tbody.innerHTML += `
            <tr>
                <td><strong>${c.codigo_criterio}</strong> ${icono}</td>
                <td>${c.descripcion_criterio}</td>

                ${
                    index === 0
                    ? `<td rowspan="${criteriosFactor.length}" 
                          class="factor-weight text-center align-middle" 
                          style="background:#f8f9fa;">
                            ${factor.valor_factor}%
                       </td>`
                    : ``
                }

                <td class="col-puntaje text-center">
                    ${seleccionadosMap[c.criterio_id] ?? "-"}
                </td>
            </tr>
        `;
    });
});
    // =============================
    // RESULTADO FINAL
    // =============================
    document.getElementById("puntaje-total").value = evalData.puntaje_total || 0;
    document.getElementById("rango-calificacion").value = evalData.nombre_rango || "N/D";

    // =============================
    // COMENTARIOS
    // =============================
    document.getElementById("comentario_evaluado").value = evalData.comentario_evaluado || "";
    document.getElementById("comentario_supervisor").value = evalData.comentario_supervisor || "";

    // =============================
    // CONFORMIDAD
    // =============================
    if (evalData.conformidad === "si") {
        document.getElementById("conformidad_si").checked = true;
    } else if (evalData.conformidad === "no") {
        document.getElementById("conformidad_no").checked = true;
    }

    document.getElementById("id_eval_obrero_eval").value = evalData.id_eval_obreros;
    document.getElementById("id_eval_obrero_sup").value = evalData.id_eval_obreros;

    habilitarCamposPorRolObrero();
}

// =============================
// Permisos por rol
// =============================
function habilitarCamposPorRolObrero() {

    const rol = (window.rolUsuario || "").trim().toLowerCase();

    document.getElementById("form_comentario_evaluado_obrero").style.display = "none";
    document.getElementById("form_comentario_supervisor_obrero").style.display = "none";

    if (rol === "evaluado") {
        document.getElementById("form_comentario_evaluado_obrero").style.display = "block";
        document.getElementById("comentario_evaluado").removeAttribute("readonly");
    }

    if (rol === "supervisor del evaluador") {
        document.getElementById("form_comentario_supervisor_obrero").style.display = "block";
        document.getElementById("comentario_supervisor").removeAttribute("readonly");
    }
}

// =============================
// Inicializar
// =============================
cargarPlanillaObreroReadonly();
