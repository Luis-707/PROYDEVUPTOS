// =============================
// Obtener roles desde la nueva sesión
// =============================
function obtenerRolesSesion() {
  const rolesGuardados = sessionStorage.getItem("roles");
  if (!rolesGuardados) return [];
  try {
    return JSON.parse(rolesGuardados);
  } catch (e) {
    console.error("Error parseando roles de sesión:", e);
    return [];
  }
}

// =============================
// Cargar planilla en modo solo lectura
// =============================
async function cargarPlanillaReadonly() {
  const cedula = sessionStorage.getItem("cedula_planilla");
  if (!cedula) {
    alert("No se seleccionó evaluado");
    return;
  }

  const idEvalAdmin = sessionStorage.getItem("id_eval_admin");

  const formData = new FormData();
  formData.append("cedula_usuario", cedula);
  formData.append("id_eval_admin", idEvalAdmin || "");

  const resp = await microApi("controlador/?planilla_comentarios", formData);

  if (!resp?.success) {
    alert(resp?.message || "Error cargando planilla readonly");
    return;
  }

  console.log("DEBUG evaluacion:", resp.data.evaluacion);
  console.log("DEBUG objetivos:", resp.data.objetivos);
  console.log("DEBUG competencias:", resp.data.competencias);
  console.log("DEBUG relaciones:", resp.data.relaciones);

  renderizarPlanillaReadonly(resp.data);
}

// =============================
// Renderizar datos en la interfaz
// =============================
function renderizarPlanillaReadonly(data) {
  const r = data.relaciones;
  const e = data.evaluacion;

  // =============================
  // Evaluado
  // =============================
  document.getElementById("evaluado_fullname").textContent =
    r.nombre_evaluado || "N/D";

  document.getElementById("evaluado_cedula").textContent =
    r.cedula_evaluado || "N/D";

  document.getElementById("evaluado_cargo").textContent =
    r.cargo_evaluado || "Sin cargo";

  document.getElementById("evaluado_ubicacion").textContent =
    r.unidad_evaluado || "N/D";

  // =============================
  // Evaluador
  // =============================
  document.getElementById("evaluador_fullname").textContent =
    r.nombre_evaluador || "N/D";

  document.getElementById("evaluador_cedula").textContent =
    r.cedula_evaluador || "N/D";

  document.getElementById("evaluador_cargo").textContent =
    r.cargo_evaluador || "Sin cargo";

  document.getElementById("evaluador_ubicacion").textContent =
    r.unidad_evaluador || "N/D";

  // =============================
  // Supervisor
  // =============================
  document.getElementById("supervisor_fullname").textContent =
    r.nombre_supervisor || "N/D";

  document.getElementById("supervisor_cedula").textContent =
    r.cedula_supervisor || "N/D";

  document.getElementById("supervisor_cargo").textContent =
    r.cargo_supervisor || "Sin cargo";

  document.getElementById("supervisor_ubicacion").textContent =
    r.unidad_supervisor || "N/D";

  // =============================
  // Objetivos
  // =============================
  const tbodyObj = document.querySelector("#tabla-objetivos-readonly tbody");
  tbodyObj.innerHTML = "";

  data.objetivos.forEach(o => {
    tbodyObj.innerHTML += `
      <tr>
        <td>${o.nombre_objetivo ?? "-"}</td>
        <td>${o.peso_objetivo ?? "-"}</td>
        <td>${o.rango_obj ?? "-"}</td>
        <td>${o.pesoxrango_obj ?? "-"}</td>
      </tr>`;
  });

  // =============================
  // Competencias
  // =============================
  const tbodyComp = document.querySelector("#tabla-competencias-readonly tbody");
  tbodyComp.innerHTML = "";

  data.competencias.forEach(c => {
    tbodyComp.innerHTML += `
      <tr>
        <td>${c.nombre_competencia ?? "-"}</td>
        <td>${c.peso_competencia ?? "-"}</td>
        <td>${c.rango_comp ?? "-"}</td>
        <td>${c.pesoxrango_comp ?? "-"}</td>
      </tr>`;
  });

  // =============================
  // Resultado final
  // =============================
  document.getElementById("puntaje-total").textContent =
    e.puntaje_final || 0;

  document.getElementById("rango-actuacion").textContent =
    e.rango_actuacion || "N/D";

  // =============================
  // Comentarios
  // =============================
  document.getElementById("comentario_supervisor").value =
    e.comentario_supervisor || "";

  document.getElementById("comentario_evaluado").value =
    e.comentario_evaluado || "";

  // =============================
  // Setear id_eval_admin
  // =============================
  if (e.id_eval_admin) {
    document.getElementById("id_eval_admin_eval").value = e.id_eval_admin;
    document.getElementById("id_eval_admin_sup").value = e.id_eval_admin;
  }

  // =============================
  // Conformidad
  // =============================
  if (e.conformidad) {
    const conf = e.conformidad.toLowerCase();
    if (conf === "si") document.getElementById("conformidad_si").checked = true;
    if (conf === "no") document.getElementById("conformidad_no").checked = true;
  }

  // =============================
  // Permisos según rol
  // =============================
  habilitarCamposPorRol();
}

// =============================
// Permisos por rol
// =============================
function habilitarCamposPorRol() {
  const rolesSesion = obtenerRolesSesion(); // viene de sessionStorage "roles"

  // Ocultar ambos formularios por defecto
  document.getElementById("form_comentario_evaluado").style.display   = "none";
  document.getElementById("form_comentario_supervisor").style.display = "none";

  // Si el usuario es EVALUADO
  if (rolesSesion.includes("evaluado")) {
    document.getElementById("form_comentario_evaluado").style.display = "block";
    document.getElementById("comentario_evaluado").removeAttribute("readonly");
  }

  // Si el usuario es SUPERVISOR DEL EVALUADOR
  if (rolesSesion.includes("supervisor del evaluador")) {
    document.getElementById("form_comentario_supervisor").style.display = "block";
    document.getElementById("comentario_supervisor").removeAttribute("readonly");
  }
}

// =============================
// Inicialización
// =============================
cargarPlanillaReadonly();