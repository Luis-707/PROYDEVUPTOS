// =============================
// Validaciones básicas
// =============================
function validarnumero(numero){
  var regex = /^[0-9]+$/;
  return regex.test(numero);
}

// =============================
// Validar formulario de evaluación
// =============================
function validar_form_editar_evaluacion(opc) {
  const formulario = document.getElementById('formulario_planilla_editar');
  const Data = new FormData(formulario);
  let isValid = true;

  for (let [key, valor] of Data.entries()) {
    if (!valor) {
      console.warn(`⚠️ El campo ${key} está vacío`);
    }

    switch (key) {
      case 'puntaje_final':
        if (!validarnumero(valor)) {
          alert("El puntaje final solo debe contener números");
          isValid = false;
        }
        break;
      case 'id_rango':
        if (!validarnumero(valor)) {
          alert("Opción de rango inválida.");
          isValid = false;
        }
        break;
      default:
        // sin validación específica
        break;
    }
  }

  if (isValid && opc === 1){ 
    ActualizarEvaluacionCompleta();
  }
}

function capturarResultadosTabla(idTabla, campoId) {
  const resultados = [];
  const tbody = document.querySelector(`#${idTabla} tbody`);
  if (!tbody) return resultados;

  tbody.querySelectorAll("tr").forEach(tr => {
    const id = tr.getAttribute("data-id");
    const peso = parseFloat(tr.children[1].textContent) || 0;
    const rango = parseInt(tr.children[2].querySelector("select").value) || 0;
    const pesoXRango = parseFloat(tr.children[3].textContent) || 0;

    resultados.push({
      [campoId]: id,
      peso: peso,
      rango: rango,
      pesoXRango: pesoXRango
    });
  });

  return resultados;
}

async function ActualizarEvaluacionCompleta() {
  actualizarTotalGeneralEditar(); // recalcular totales antes de enviar

  const cedula = sessionStorage.getItem("cedula_planilla");
  const idEvalAdmin = sessionStorage.getItem("id_eval_admin");
  const evaluadoId = document.getElementById("evaluado_id")?.value;
  const evaluadorId = document.getElementById("evaluador_id")?.value;

  if (!cedula || !idEvalAdmin || !evaluadoId || !evaluadorId) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Faltan datos de identificación para actualizar la evaluación' });
    return;
  }

  let datos = capturarValoresFormulario('formulario_planilla_editar');

  const objetivos = capturarResultadosTabla("tabla-objetivos-editar", "id_odi");
  const competencias = capturarResultadosTabla("tabla-competencias-editar", "id_competencia");

  datos.append("objetivos", JSON.stringify(objetivos));
  datos.append("competencias", JSON.stringify(competencias));

  datos.append("cedula_usuario", cedula);
  datos.append("evaluado_id", evaluadoId);
  datos.append("evaluador_id", evaluadorId);
  datos.append("id_eval_admin", idEvalAdmin);

  try {
    const resp = await microApi("controlador/?a_evaluacion", datos);
    console.log("Respuesta actualización:", resp);

    if (!resp.success) {
      Swal.fire({
        icon: 'error',
        title: 'Error al actualizar',
        text: resp.message || "No se pudo actualizar la evaluación"
      });
    } else {
      Swal.fire({
        icon: 'success',
        title: 'Evaluación actualizada',
        text: "Los cambios se guardaron correctamente"
      });
    }
  } catch (err) {
    console.error("Error en ActualizarEvaluacionCompleta:", err);
    Swal.fire({
      icon: 'error',
      title: 'Error inesperado',
      text: 'Ocurrió un error al actualizar la evaluación'
    });
  }
}

function valorFormEvaluacion(evaluado='', evaluador='', RangoActuacion='', puntaje='') {
  document.getElementById('evaluado_id').value = evaluado;
  document.getElementById('evaluador_id').value = evaluador;
  document.getElementById('id_rango').value = RangoActuacion;
  document.getElementById('puntaje_final').value = puntaje;
}

//=============================================================//
// Cargar datos personales

async function cargarPlanillaEditar() {
  const cedula = sessionStorage.getItem("cedula_planilla");
  const idEvalAdmin = sessionStorage.getItem("id_eval_admin");
  const evaluadoIdSession = sessionStorage.getItem("evaluado_id");

  if (!cedula || !idEvalAdmin || !evaluadoIdSession) {
    alert("No se seleccionó evaluado o evaluación");
    return;
  }

  const formData = new FormData();
  formData.append("evaluado_id", evaluadoIdSession);
  formData.append("id_eval_admin", idEvalAdmin);

  const resp = await microApi('controlador/?planilla_editar', formData);

  if (!resp?.success) {
    alert(resp?.message || "Error cargando planilla");
    return;
  }

  const registro = resp.data;
  console.log("📦 Registro recibido:", registro);

  // Evaluado
  document.getElementById("evaluado_fullname").textContent = registro.evaluado_nombre || "N/D";
  document.getElementById("evaluado_cedula").textContent = registro.evaluado_cedula || "N/D";
  document.getElementById("evaluado_cargo").textContent = registro.evaluado_cargo || "Sin cargo";
  document.getElementById("evaluado_ubicacion").textContent = registro.evaluado_ubicacion || "N/D";
  document.getElementById("evaluado_id").value = registro.evaluado_id ?? evaluadoIdSession;

  // Evaluador
  document.getElementById("evaluador_fullname").textContent = registro.evaluador_nombre || "N/D";
  document.getElementById("evaluador_cedula").textContent = registro.evaluador_cedula || "N/D";
  document.getElementById("evaluador_cargo").textContent = registro.evaluador_cargo || "Sin cargo";
  document.getElementById("evaluador_ubicacion").textContent = registro.evaluador_ubicacion || "N/D";
  document.getElementById("evaluador_id").value = registro.evaluador_id;

  // Supervisor
  document.getElementById("supervisor_fullname").textContent = registro.supervisor_nombre || "N/D";
  document.getElementById("supervisor_cedula").textContent = registro.supervisor_cedula || "N/D";
  document.getElementById("supervisor_cargo").textContent = registro.supervisor_cargo || "Sin cargo";
}

//=============================================================//

async function cargarPeriodoEvaluacion() {
  const idEvalAdmin = sessionStorage.getItem("id_eval_admin");

  if (!idEvalAdmin) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'No se encontró id_eval_admin para cargar el período' });
    return;
  }

  const formData = new FormData();
  formData.append("id_eval_admin", idEvalAdmin);

  const resp = await microApi('controlador/?l_periodo', formData);
  console.log("Respuesta periodo:", resp);

  if (!resp.success || !resp.data) {
    Swal.fire({ icon: 'error', title: 'Error', text: resp.message || "No se pudo cargar el periodo" });
    return;
  }

  const periodo = resp.data;
  document.getElementById("fecha-inicio").value = periodo.fecha_inicio || "";
  document.getElementById("fecha-cierre").value = periodo.fecha_cierre || "";
  document.getElementById("periodo-evaluacion").value = periodo.periodo_evaluado || "";
}

// =============================
// Rangos de actuación
// =============================
let rangosActuacionEditar = [];

// =============================
// Cargar rangos de actuación
// =============================
async function cargarRangosActuacionEditar() {
  const resp = await microApi('controlador/?l_rangos');
  rangosActuacionEditar = Array.isArray(resp[0]) ? resp[0] : resp;
  console.log("✅ Rangos cargados (editar):", rangosActuacionEditar);
}

// =============================
// Totales y cálculo de puntaje
// =============================
function actualizarTotalGeneralEditar() {
  const totalObjEl = document.getElementById("total-objetivos-editar");
  const totalCompEl = document.getElementById("total-competencias-editar");
  const puntajeEl  = document.getElementById("puntaje-total-editar");
  const rangoEl    = document.getElementById("rango-actuacion-editar");

  if (!totalObjEl || !totalCompEl || !puntajeEl || !rangoEl) return;

  const totalObj = parseFloat(totalObjEl.textContent) || 0;
  const totalComp = parseFloat(totalCompEl.textContent) || 0;
  const totalGeneral = totalObj + totalComp;

  puntajeEl.textContent = totalGeneral;
  document.getElementById("puntaje_final").value = totalGeneral;

  let rangoTexto = "No definido";
  let rangoId = "";

  for (const r of rangosActuacionEditar) {
    const min = parseInt(r.puntaje_minimo ?? r.minimo ?? 0);
    const max = parseInt(r.puntaje_maximo ?? r.maximo ?? 0);
    const texto = r.rango_actuacion ?? r.descripcion ?? "Sin nombre";
    const id = r.id_rango ?? r.idRango ?? r.id ?? "";

    if (totalGeneral >= min && totalGeneral <= max) {
      rangoTexto = texto;
      rangoId = id;
      break;
    }
  }

  rangoEl.textContent = rangoTexto;
  document.getElementById("id_rango").value = rangoId;
}

function actualizarTotalesEditar(idTabla, idTotal) {
  const tbody = document.querySelector(`#${idTabla} tbody`);
  if (!tbody) return;

  let total = 0;
  tbody.querySelectorAll("tr").forEach(tr => {
    const val = parseFloat(tr.children[3].textContent) || 0;
    total += val;
  });

  const totalEl = document.getElementById(idTotal);
  if (totalEl) totalEl.textContent = total;

  actualizarTotalGeneralEditar();
}

// =============================
// Tablas dinámicas (editar)
// =============================
function renderTablaDinamicaEditar(datos, idTabla, idTotal, campoPeso, campoNombre, campoId, campoRango) {
  if (!Array.isArray(datos)) return;

  const tbody = document.querySelector(`#${idTabla} tbody`);
  if (!tbody) return;
  tbody.innerHTML = "";

  datos.forEach(item => {
    const tr = document.createElement("tr");
    const idValor = item[campoId];
    tr.setAttribute("data-id", idValor);

    const tdNombre = document.createElement("td");
    tdNombre.textContent = item[campoNombre];
    tr.appendChild(tdNombre);

    const tdPeso = document.createElement("td");
    tdPeso.textContent = item[campoPeso];
    tr.appendChild(tdPeso);

    const tdRango = document.createElement("td");
    const select = document.createElement("select");
    for (let i = 1; i <= 5; i++) {
      const opt = document.createElement("option");
      opt.value = i;
      opt.textContent = i;
      if (i == item[campoRango]) opt.selected = true;
      select.appendChild(opt);
    }
    tdRango.appendChild(select);
    tr.appendChild(tdRango);

    const tdPxR = document.createElement("td");
    tdPxR.textContent = parseFloat(item[campoPeso]) * parseInt(select.value);
    tr.appendChild(tdPxR);

    select.addEventListener("change", () => {
      tdPxR.textContent = parseFloat(item[campoPeso]) * parseInt(select.value);
      actualizarTotalesEditar(idTabla, idTotal);
    });

    tbody.appendChild(tr);
  });

  actualizarTotalesEditar(idTabla, idTotal);
}

// =============================
// Cargar tablas en modo edición
// =============================
async function cargarTablasPlanillaEditar() {
  const cedula = sessionStorage.getItem("cedula_planilla");
  const idEvalAdmin = sessionStorage.getItem("id_eval_admin");
  const evaluadoId = sessionStorage.getItem("evaluado_id");

  if (!cedula || !idEvalAdmin || !evaluadoId) {
    alert("No se seleccionó evaluado o evaluación");
    return;
  }

  const formData = new FormData();
  formData.append("cedula_usuario", cedula);
  formData.append("id_eval_admin", idEvalAdmin);
  formData.append("evaluado_id", evaluadoId);

  // Objetivos
  const objResp = await microApi('controlador/?l_objetivos_editar', formData);
  console.log("Respuesta objetivos (editar):", objResp);

  if (!objResp.success) {
    alert(objResp.message || "Error cargando objetivos");
    return;
  }

  const objetivos = Array.isArray(objResp.data[0]) ? objResp.data[0] : objResp.data;
  renderTablaDinamicaEditar(
    objetivos,
    "tabla-objetivos-editar",
    "total-objetivos-editar",
    "peso_objetivo",
    "nombre_objetivo",
    "id_odi",
    "rango_obj"
  );

  // Competencias
  const compResp = await microApi('controlador/?l_competencias_editar', formData);
  console.log("Respuesta competencias (editar):", compResp);

  if (!compResp.success) {
    alert(compResp.message || "Error cargando competencias");
    return;
  }

  const competencias = Array.isArray(compResp.data[0]) ? compResp.data[0] : compResp.data;
  renderTablaDinamicaEditar(
    competencias,
    "tabla-competencias-editar",
    "total-competencias-editar",
    "peso_competencia",
    "nombre_competencia",
    "id_competencia",
    "rango_comp"
  );

  await cargarRangosActuacionEditar();
  actualizarTotalGeneralEditar();
}