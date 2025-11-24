// =============================
// Cargar planilla en modo solo lectura
// =============================
async function cargarPlanillaReadonly() {
  const cedula = sessionStorage.getItem("cedula_planilla");
  if (!cedula) {
    alert("No se seleccionó evaluado");
    return;
  }

  // Cargar JSON de empleados
  const empleadosResp = await microApi('views/js/datos_empleado.json');
  const empleados = Array.isArray(empleadosResp)
    ? (empleadosResp[0]?.data || empleadosResp[0] || [])
    : (empleadosResp?.data || []);

  // Llamar al servicio readonly
  const formData = new FormData();
  formData.append("cedula_usuario", cedula);
  const resp = await microApi('controlador/?planilla_comentarios', formData);

  if (!resp?.success) {
    alert(resp?.message || "Error cargando planilla readonly");
    return;
  }

  // Debug: ver qué llega del backend
  console.log("DEBUG evaluacion:", resp.data.evaluacion);
  console.log("DEBUG objetivos:", resp.data.objetivos);
  console.log("DEBUG competencias:", resp.data.competencias);

  renderizarPlanillaReadonly(resp.data, empleados);
}

// =============================
// Renderizar datos en la interfaz
// =============================
function renderizarPlanillaReadonly(data, empleados) {
  const evalData = data.evaluacion;

  // Evaluado
  const empEval = empleados.find(emp => emp.pin === evalData.cedula_evaluado || emp.pin_str === evalData.cedula_evaluado);
  document.getElementById("evaluado_fullname").textContent = empEval?.fullname || "N/D";
  document.getElementById("evaluado_cedula").textContent = evalData.cedula_evaluado || "N/D";
  document.getElementById("evaluado_cargo").textContent = evalData.cargo_evaluado || "Sin cargo";
  document.getElementById("evaluado_ubicacion").textContent = empEval?.additional || "N/D";

  // Evaluador
  const empEv = empleados.find(emp => emp.pin === evalData.cedula_evaluador || emp.pin_str === evalData.cedula_evaluador);
  document.getElementById("evaluador_fullname").textContent = empEv?.fullname || "N/D";
  document.getElementById("evaluador_cedula").textContent = evalData.cedula_evaluador || "N/D";
  document.getElementById("evaluador_cargo").textContent = evalData.cargo_evaluador || "Sin cargo";
  document.getElementById("evaluador_ubicacion").textContent = empEv?.additional || "N/D";

  // Supervisor
  const empSup = empleados.find(emp => emp.pin === evalData.cedula_supervisor || emp.pin_str === evalData.cedula_supervisor);
  document.getElementById("supervisor_fullname").textContent = empSup?.fullname || "N/D";
  document.getElementById("supervisor_cedula").textContent = evalData.cedula_supervisor || "N/D";
  document.getElementById("supervisor_cargo").textContent = evalData.cargo_supervisor || "Sin cargo";

  // Objetivos
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

  // Competencias
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

  // Resultado final
  document.getElementById("puntaje-total").textContent = evalData.puntaje_final || 0;
  document.getElementById("rango-actuacion").textContent = evalData.rango_actuacion || "N/D";

  // Comentarios y conformidad
  document.getElementById("comentario_supervisor").value = evalData.comentario_supervisor || "";
  document.getElementById("comentario_evaluado").value = evalData.comentario_evaluado || "";

  // Setear id_eval_admin en ambos formularios
  if (evalData.id_eval_admin) {
    document.getElementById("id_eval_admin_eval").value = evalData.id_eval_admin;
    document.getElementById("id_eval_admin_sup").value = evalData.id_eval_admin;
    console.log("DEBUG id_eval_admin seteado:", evalData.id_eval_admin);
  } else {
    console.warn("⚠️ No llegó id_eval_admin desde backend");
  }

  // Setear conformidad si viene del backend
if (evalData.conformidad) {
  if (evalData.conformidad.toLowerCase() === "si") {
    document.getElementById("conformidad_si").checked = true;
  } else if (evalData.conformidad.toLowerCase() === "no") {
    document.getElementById("conformidad_no").checked = true;
  }
}
  
  // Habilitar según rol
  habilitarCamposPorRol();
}

// =============================
// Permisos por rol
// =============================
function habilitarCamposPorRol() {
  const rol = (window.rolUsuario || "").trim().toLowerCase();

  // Ocultar ambos formularios por defecto
  document.getElementById("form_comentario_evaluado").style.display = "none";
  document.getElementById("form_comentario_supervisor").style.display = "none";

  if (rol === "evaluado") {
    document.getElementById("form_comentario_evaluado").style.display = "block";
    document.getElementById("comentario_evaluado").removeAttribute("readonly");
  } else if (rol === "supervisor del evaluador") {
    document.getElementById("form_comentario_supervisor").style.display = "block";
    document.getElementById("comentario_supervisor").removeAttribute("readonly");
  }
}
// =============================
// Inicialización
// =============================
cargarPlanillaReadonly();