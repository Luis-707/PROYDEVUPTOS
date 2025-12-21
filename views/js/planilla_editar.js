// =============================
// Validaciones básicas
// =============================
function validarCadena(cadena){
  var regex = /^[aA-zZàèìòùÁÉÍÓÚ\s]+$/;
  return regex.test(cadena);
}

function validarnumero(numero){
  var regex = /^[0-9]+$/;
  return regex.test(numero);
}

function validarcaracter(cadena){
  var regex = /^[0-9aA-zZàèìòùÁÉÍÓÚ_.-]+$/;
  return regex.test(cadena);
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
        console.log(`Campo ${key} no requiere validación específica.`);

        /*if (!isValid) {
      break; // Salir del switch si ya es inválido
        }*/
    }
  }

  

  if (isValid) {
    if (opc === 1){ 
      
      ActualizarEvaluacionCompleta();
    }
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
  if (!cedula) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'No se seleccionó evaluado' });
    return;
  }
  const idEvalAdmin = sessionStorage.getItem("id_eval_admin");
  const idEvaluado = document.getElementById("id_evaluado")?.value;
  const idUsuario = document.getElementById("id_usuario")?.value;

  // Capturar valores del formulario
  let datos = capturarValoresFormulario('formulario_planilla_editar');

  // Capturar objetivos editados
  const objetivos = capturarResultadosTabla("tabla-objetivos-editar", "id_odi");
  // Capturar competencias editadas
  const competencias = capturarResultadosTabla("tabla-competencias-editar", "id_competencia");

  datos.append("objetivos", JSON.stringify(objetivos));
  datos.append("competencias", JSON.stringify(competencias));

  try {
    datos.append("cedula_usuario", cedula);
    datos.append("id_evaluado", idEvaluado);
    datos.append("id_usuario", idUsuario);
    datos.append("id_eval_admin", idEvalAdmin);
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
        text: "✅ Los cambios se guardaron correctamente"
      });

      // Actualizar hidden con id_eval_admin si lo devuelve
      /*if (resp.id_eval_admin) {
        document.getElementById("id_eval_admin").value = resp.id_eval_admin;
      }*/
    }
  } catch (err) {
    console.error("Error en actualizarEvaluacionCompleta:", err);
    Swal.fire({
      icon: 'error',
      title: 'Error inesperado',
      text: 'Ocurrió un error al actualizar la evaluación'
    });
  }
}

function valorFormEvaluacion(evaluado='', evaluador='', RangoActuacion='', puntaje='') {
  document.getElementById('id_evaluado').value = evaluado;
  document.getElementById('id_usuario_evaluador').value = evaluador;
 
  document.getElementById('id_rango').value = RangoActuacion;
  document.getElementById('puntaje_final').value = puntaje;
}

//=============================================================//
//Funcion para cargar datos personales

async function cargarPlanillaEditar() {
  const cedula = sessionStorage.getItem("cedula_planilla");
  if (!cedula) {
    alert("No se seleccionó evaluado");
    return;
  }

  const formData = new FormData();
  formData.append("cedula_usuario", cedula);

  const resp = await microApi('controlador/?planilla', formData);

  if (!resp?.success) {
    alert(resp?.message || "Error cargando planilla");
    return;
  }

  const registro = resp.data;
  console.log("📦 Registro recibido:", registro);

  // Evaluado
  document.getElementById("evaluado_fullname").textContent = registro.nombre_completo_evaluado || "N/D";
  document.getElementById("evaluado_cedula").textContent = registro.cedula_usuario || "N/D";
  document.getElementById("evaluado_cargo").textContent = registro.cargo_evaluado || "Sin cargo";
  document.getElementById("evaluado_ubicacion").textContent = registro.ubicacion_evaluado || "N/D";
  document.getElementById("id_evaluado").value = registro.id_evaluado;

  // Evaluador
  document.getElementById("evaluador_fullname").textContent = registro.nombre_completo_evaluador || "N/D";
  document.getElementById("evaluador_cedula").textContent = registro.cedula_evaluador || "N/D";
  document.getElementById("evaluador_cargo").textContent = registro.cargo_evaluador || "Sin cargo";
  document.getElementById("evaluador_ubicacion").textContent = registro.ubicacion_evaluador || "N/D";
  document.getElementById("id_usuario").value = registro.id_usuario_evaluador;

  // Supervisor
  document.getElementById("supervisor_fullname").textContent = registro.nombre_completo_supervisor || "N/D";
  document.getElementById("supervisor_cedula").textContent = registro.cedula_supervisor || "N/D";
  document.getElementById("supervisor_cargo").textContent = registro.cargo_supervisor || "Sin cargo";
}

//=============================================================//

async function cargarPeriodoEvaluacion() {
  const cedula = sessionStorage.getItem("cedula_planilla");
  const idEvaluado = document.getElementById("id_evaluado")?.value;
  const idUsuario = document.getElementById("id_usuario")?.value;
  const idEvalAdmin = sessionStorage.getItem("id_eval_admin");

  console.log("👉 Enviando al servicio l_periodo:", {
    cedula_usuario: cedula,
    id_evaluado: idEvaluado,
    id_usuario: idUsuario,
    id_eval_admin: idEvalAdmin
  });

  const formData = new FormData();
  formData.append("cedula_usuario", cedula);
  formData.append("id_evaluado", idEvaluado);
  formData.append("id_usuario", idUsuario);
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

  // Si quieres guardar el id_eval_admin en memoria para otros usos:
  /*if (resp.id_eval_admin) {
    sessionStorage.setItem("id_eval_admin", resp.id_eval_admin);
  }*/
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

    // Nombre
    const tdNombre = document.createElement("td");
    tdNombre.textContent = item[campoNombre];
    tr.appendChild(tdNombre);

    // Peso
    const tdPeso = document.createElement("td");
    tdPeso.textContent = item[campoPeso];
    tr.appendChild(tdPeso);

    // Rango (select con valor guardado)
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

    // Peso x Rango
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
  if (!cedula) {
    alert("No se seleccionó evaluado");
    return;
  }

  const formData = new FormData();
  formData.append("cedula_usuario", cedula);
  formData.append("id_eval_admin", idEvalAdmin);

  // 👉 Cargar objetivos guardados
  const objResp = await microApi('controlador/?l_objetivos', formData);
  console.log("Respuesta objetivos (editar):", objResp);

  if (!objResp.success) {
    alert(objResp.message || "Error cargando objetivos");
    return;
  }

  const objetivos = Array.isArray(objResp.data[0]) ? objResp.data[0] : objResp.data;
  renderTablaDinamicaEditar(objetivos, "tabla-objetivos-editar", "total-objetivos-editar", "peso_objetivo", "nombre_objetivo", "id_odi", "rango_obj");

  // 👉 Cargar competencias guardadas
  const compResp = await microApi('controlador/?l_competencias', formData);
  console.log("Respuesta competencias (editar):", compResp);

  const competencias = Array.isArray(compResp[0]) ? compResp[0] : compResp;
  renderTablaDinamicaEditar(competencias, "tabla-competencias-editar", "total-competencias-editar", "peso_competencia", "nombre_competencia", "id_competencia", "rango_comp");

  // 👉 Cargar rangos y actualizar totales
  await cargarRangosActuacionEditar();
  actualizarTotalGeneralEditar();
}