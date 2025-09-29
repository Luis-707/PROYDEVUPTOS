let rangosActuacion = [];

// =============================
// Cargar datos de evaluado/evaluador/supervisor
// =============================
async function cargarPlanilla() {
  const cedula = sessionStorage.getItem("cedula_planilla");
  if (!cedula) {
    alert("No se seleccionó evaluado");
    return;
  }

  const empleadosResp = await microApi('views/js/datos_empleado.json');
  const empleados = Array.isArray(empleadosResp)
    ? (empleadosResp[0]?.data || empleadosResp[0] || [])
    : (empleadosResp?.data || []);

  const formData = new FormData();
  formData.append("cedula_usuario", cedula);

  const resp = await microApi('controlador/?planilla', formData);

  if (!resp?.success) {
    alert(resp?.message || "Error cargando planilla");
    return;
  }

  const registro = resp.data;

  // Evaluado
  const empEval = empleados.find(emp => emp.pin === registro.cedula_usuario || emp.pin_str === registro.cedula_usuario);
  document.getElementById("evaluado_fullname").textContent = empEval?.fullname || "N/D";
  document.getElementById("evaluado_cedula").textContent = registro.cedula_usuario || "N/D";
  document.getElementById("evaluado_cargo").textContent = registro.cargo_evaluado || "Sin cargo";
  document.getElementById("evaluado_ubicacion").textContent = empEval?.additional || "N/D";

  // Evaluador
  const empEv = empleados.find(emp => emp.pin === registro.cedula_evaluador || emp.pin_str === registro.cedula_evaluador);
  document.getElementById("evaluador_fullname").textContent = empEv?.fullname || "N/D";
  document.getElementById("evaluador_cedula").textContent = registro.cedula_evaluador || "N/D";
  document.getElementById("evaluador_cargo").textContent = registro.cargo_evaluador || "Sin cargo";
  document.getElementById("evaluador_ubicacion").textContent = empEv?.additional || "N/D";

  // Supervisor
  const empSup = empleados.find(emp => emp.pin === registro.cedula_supervisor || emp.pin_str === registro.cedula_supervisor);
  document.getElementById("supervisor_fullname").textContent = empSup?.fullname || "N/D";
  document.getElementById("supervisor_cedula").textContent = registro.cedula_supervisor || "N/D";
  document.getElementById("supervisor_cargo").textContent = registro.cargo_supervisor || "Sin cargo";
}

// =============================
// Cargar tablas dinámicas
// =============================
async function cargarTablasPlanilla() {
  // Objetivos
  const objResp = await microApi('controlador/?l_objetivos');
  console.log("👉 Respuesta l_objetivos:", objResp);
  const objetivos = Array.isArray(objResp[0]) ? objResp[0] : objResp;
  renderTablaDinamica(objetivos, "tabla-objetivos", "total-objetivos", "peso_objetivo", "nombre_objetivo");

  // Competencias
  const compResp = await microApi('controlador/?l_competencias');
  console.log("👉 Respuesta l_competencias:", compResp);
  const competencias = Array.isArray(compResp[0]) ? compResp[0] : compResp;
  renderTablaDinamica(competencias, "tabla-competencias", "total-competencias", "peso_competencia", "nombre_competencia");

  // Rangos de actuación
  await cargarRangosActuacion();

  // Inicializar cálculo
  actualizarTotalGeneral();
}

function renderTablaDinamica(datos, idTabla, idTotal, campoPeso, campoNombre) {
  if (!Array.isArray(datos)) {
    console.error("❌ Datos no es un array:", datos);
    return;
  }

  const tbody = document.querySelector(`#${idTabla} tbody`);
  if (!tbody) {
    console.warn(`⏳ No se encontró tbody para ${idTabla}`);
    return;
  }
  tbody.innerHTML = "";

  datos.forEach(item => {
    const tr = document.createElement("tr");

    // Nombre
    const tdNombre = document.createElement("td");
    tdNombre.textContent = item[campoNombre];
    tr.appendChild(tdNombre);

    // Peso
    const tdPeso = document.createElement("td");
    tdPeso.textContent = item[campoPeso];
    tr.appendChild(tdPeso);

    // Rango (select 1-5)
    const tdRango = document.createElement("td");
    const select = document.createElement("select");
    for (let i = 1; i <= 5; i++) {
      const opt = document.createElement("option");
      opt.value = i;
      opt.textContent = i;
      select.appendChild(opt);
    }
    select.addEventListener("change", () => {
      const peso = parseFloat(item[campoPeso]);
      const rango = parseInt(select.value);
      tdPxR.textContent = peso * rango;
      actualizarTotales(idTabla, idTotal);
    });
    tdRango.appendChild(select);
    tr.appendChild(tdRango);

    // Peso x Rango
    const tdPxR = document.createElement("td");
    tdPxR.textContent = "0";
    tr.appendChild(tdPxR);

    tbody.appendChild(tr);
  });

  actualizarTotales(idTabla, idTotal);
}

function actualizarTotales(idTabla, idTotal) {
  const tbody = document.querySelector(`#${idTabla} tbody`);
  if (!tbody) return;

  let total = 0;
  tbody.querySelectorAll("tr").forEach(tr => {
    const val = parseFloat(tr.children[3].textContent) || 0;
    total += val;
  });
  const totalEl = document.getElementById(idTotal);
  if (totalEl) totalEl.textContent = total;

  actualizarTotalGeneral();
}

// =============================
// Rangos de actuación
// =============================
async function cargarRangosActuacion() {
  const resp = await microApi('controlador/?l_rangos');
  console.log("👉 Respuesta rango_actuacion:", resp);
  rangosActuacion = Array.isArray(resp[0]) ? resp[0] : resp;
}

function actualizarTotalGeneral() {
  const totalObjEl = document.getElementById("total-objetivos");
  const totalCompEl = document.getElementById("total-competencias");
  const puntajeEl = document.getElementById("puntaje-total");
  const rangoEl = document.getElementById("rango-actuacion");

  if (!totalObjEl || !totalCompEl || !puntajeEl || !rangoEl) {
    console.warn("⏳ Elementos de resultado final aún no están en el DOM");
    return;
  }

  const totalObj = parseFloat(totalObjEl.textContent) || 0;
  const totalComp = parseFloat(totalCompEl.textContent) || 0;
  const totalGeneral = totalObj + totalComp;

  // Mostrar solo en Puntaje Final
  puntajeEl.textContent = totalGeneral;

  // Buscar rango correspondiente
  let rangoTexto = "No definido";
  for (const r of rangosActuacion) {
    const min = parseInt(r.puntaje_minimo);
    const max = parseInt(r.puntaje_maximo);
    if (totalGeneral >= min && totalGeneral <= max) {
      rangoTexto = r.rango_actuacion;
      break;
    }
  }
  rangoEl.textContent = rangoTexto;
}       



// =============================
// Periodo de Evaluación (corregido)
// =============================
function calcularPeriodo(fechaInicio, fechaCierre) {
  if (!fechaInicio || !fechaCierre) return null;

  // Extraer año y mes directamente del string YYYY-MM-DD
  const [yearStr, monthStr] = fechaInicio.split("-");
  const year = parseInt(yearStr);
  const mesInicio = parseInt(monthStr);

  let periodo = "";
  if (mesInicio >= 1 && mesInicio <= 6) {
    periodo = `Periodo I-${year}`;
  } else {
    periodo = `Periodo II-${year}`;
  }

  return periodo;
}

function actualizarPeriodo() {
  const fechaInicio = document.getElementById("fecha-inicio").value;
  const fechaCierre = document.getElementById("fecha-cierre").value;
  const periodoContainer = document.getElementById("periodo-container");
  const selectPeriodo = document.getElementById("periodo-evaluacion");

  if (fechaInicio && fechaCierre) {
    const periodo = calcularPeriodo(fechaInicio, fechaCierre);
    if (periodo) {
      periodoContainer.style.display = "block";
      selectPeriodo.innerHTML = `<option value="${periodo}">${periodo}</option>`;
    }
  } else {
    periodoContainer.style.display = "none";
    selectPeriodo.innerHTML = "";
  }
}

