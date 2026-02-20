async function cargarPlanillaResultadosObrero() {

    const cedula = sessionStorage.getItem("cedula_planilla_obrero");
    const idEvalObrero = sessionStorage.getItem("id_eval_obreros");

    const fd = new FormData();
    fd.append("cedula_usuario", cedula);
    fd.append("id_eval_obreros", idEvalObrero);

    const resp = await microApi("controlador/?planilla_resultados_obreros", fd);

    if (!resp?.success) {
        alert(resp.message || "Error cargando planilla obrera");
        return;
    }

    renderizarPlanillaResultadosObrero(resp.data);
}

function renderizarPlanillaResultadosObrero(data) {

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
    document.getElementById("evaluado_area_ocupacional").value = rel.area_ocupacional;
    document.getElementById("evaluado_ubicacion_fisica").value = rel.ubicacion_fisica;

    // =============================
    // DATOS DEL EVALUADOR
    // =============================
    document.getElementById("evaluador_fullname").value = rel.nombre_evaluador;
    document.getElementById("evaluador_cedula").value = rel.cedula_evaluador;
    document.getElementById("evaluador_ubicacion").value = rel.ubicacion_evaluador;

    // =============================
    // TABLA FACTORES + CRITERIOS
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
    // COMENTARIOS READONLY
    // =============================
    document.getElementById("comentario_supervisor_text").textContent =
        evalData.comentario_supervisor || "Sin comentario";

    document.getElementById("comentario_evaluado_text").textContent =
        evalData.comentario_evaluado || "Sin comentario";

    document.getElementById("conformidad_text").textContent =
        evalData.conformidad ? evalData.conformidad.toUpperCase() : "N/D";
}


cargarPlanillaResultadosObrero();