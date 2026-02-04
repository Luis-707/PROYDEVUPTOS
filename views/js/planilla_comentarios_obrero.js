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

    if (!resp?.success) {
        alert(resp?.message || "Error cargando planilla obrera");
        return;
    }

    // Debug: ver qué llega del backend
    console.log("DEBUG evaluacion obrero:", resp.data.evaluacion);
    console.log("DEBUG factores obrero:", resp.data.factores);
    console.log("DEBUG relaciones obrero:", resp.data.relaciones);

    // Renderizar solo con los datos de la respuesta

    renderizarPlanillaObreroReadonly(resp.data);
}

// =============================
// Renderizar datos
// =============================
function renderizarPlanillaObreroReadonly(data) {

    const rel = data.relaciones;
    const evalData = data.evaluacion;
    const factores = data.factores;

    // Evaluado
    document.getElementById("evaluado_fullname").value = rel.nombre_completo_evaluado || "N/D";
    document.getElementById("evaluado_cedula").value = rel.cedula_usuario || "N/D";
    document.getElementById("evaluado_cargo").value = rel.cargo_evaluado || "N/D";
    document.getElementById("evaluado_ubicacion").value = rel.ubicacion_evaluado || "N/D";
    document.getElementById("evaluado_area_ocupacional").value = rel.nombre_ao || "N/D";
    document.getElementById("evaluado_ubicacion_fisica").value = rel.nombre_uf || "N/D";
    document.getElementById("evaluado_anios_puesto").value = evalData.tiempo_puesto || "N/D";

    // Evaluador
    document.getElementById("evaluador_fullname").value = rel.nombre_completo_evaluador || "N/D";
    document.getElementById("evaluador_cedula").value = rel.cedula_evaluador || "N/D";
    document.getElementById("evaluador_ubicacion").value = rel.ubicacion_evaluador || "N/D";

    // Supervisor
    /*document.getElementById("supervisor_fullname").textContent = rel.nombre_completo_supervisor || "N/D";
    document.getElementById("supervisor_cedula").textContent = rel.cedula_supervisor || "N/D";*/

    // Factores y criterios
    const tbody = document.querySelector("#tabla-factores-readonly tbody");
    tbody.innerHTML = "";

    factores.forEach(f => {
        tbody.innerHTML += `
            <tr>
                <td>${f.nombre_factor}</td>
                <td>${f.descripcion_criterio}</td>
                <td>${f.puntaje_obtenido}</td>
            </tr>
        `;
    });

    // Resultado final
    document.getElementById("puntaje-total").value = evalData.puntaje_total || 0;
    document.getElementById("rango-calificacion").value = evalData.nombre_rango || "N/D";

    // Comentarios
    document.getElementById("comentario_evaluado").value = evalData.Comentario_evaluado || "";
    document.getElementById("comentario_supervisor").value = evalData.Comentario_supervisor || "";

    // Conformidad
    if (evalData.conformidad === "si") {
        document.getElementById("conformidad_si").checked = true;
    } else if (evalData.conformidad === "no") {
        document.getElementById("conformidad_no").checked = true;
    }

    // IDs ocultos
    document.getElementById("id_eval_obrero_eval").value = evalData.id_eval_obreros;
    document.getElementById("id_eval_obrero_sup").value = evalData.id_eval_obreros;

    // Permisos por rol
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

