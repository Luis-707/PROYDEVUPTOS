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
function validar_form_evaluacion(opc) {
  const formulario = document.getElementById('formulario_planilla');
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
      case 'fecha_inicio':
      case 'fecha_cierre':
      case 'periodo_evaluado':
        // Estos campos son gestionados automáticamente
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
      
      guardarEvaluacionCompleta();
    }
  }


}

// =============================
// Validación de campos obligatorios
// =============================


async function BuscarEvaluacion(){
  let datosEval = capturarValoresFormulario('formulario_planilla');
  var resp = await microApi('controlador/?b_evaluacion',datosEval);
  return resp;
}

function capturarResultadosTabla(idTabla, campoId) {
  const resultados = [];
  const tbody = document.querySelector(`#${idTabla} tbody`);
  if (!tbody) return resultados;

  tbody.querySelectorAll("tr").forEach(tr => {
    const id = tr.getAttribute("data-id"); // 👈 necesitas setear este atributo al renderizar
    const select = tr.querySelector("select");
    const rango = parseInt(select.value) || 0;
    const pesoXRango = parseFloat(tr.children[3].textContent) || 0;

    resultados.push({
      [campoId]: id,
      rango: rango,
      pesoXRango: pesoXRango
    });
  });

  return resultados;
}

// =============================
// Validación de campos obligatorios
// =============================
/*function validarCamposObligatorios(fd) {
  const obligatorios = [
    "id_usuario",
    "id_evaluado",
    "id_rango",
    "puntaje_final",
    "fecha_inicio",
    "fecha_cierre",
    "periodo_evaluado"
  ];

  console.group("🔎 Verificación de campos obligatorios");
  obligatorios.forEach(campo => {
    const valor = fd.get(campo);
    if (!valor) {
      console.warn(`⚠️ El campo "${campo}" está vacío o no se está enviando`);
    } else {
      console.log(`✅ ${campo} => ${valor}`);
    }
  });
  console.groupEnd();
}*/
// =============================
// Guardar Evaluación
// =============================
async function guardarEvaluacionCompleta() {
  actualizarTotalGeneral(); // recalcular totales

  let datos = capturarValoresFormulario('formulario_planilla');

  datos.append("evaluado_id", document.getElementById("evaluado_id").value);
datos.append("evaluador_id", document.getElementById("evaluador_id").value);
datos.append("id_eval_admin", sessionStorage.getItem("id_eval_admin"));

  // Capturar objetivos y competencias
  const objetivos = capturarResultadosTabla("tabla-objetivos", "id_odi");
  const competencias = capturarResultadosTabla("tabla-competencias", "id_competencia");

  datos.append("objetivos", JSON.stringify(objetivos));
  datos.append("competencias", JSON.stringify(competencias));

  try {
    const resp = await microApi("controlador/?g_evaluacion", datos);
    console.log("Respuesta evaluación completa:", resp);

    if (!resp.success) {
      Swal.fire({
        icon: 'warning',
        title: 'Registro duplicado',
        text: resp.message || "Ya existen objetivos o competencias para esta evaluación"
      });
    } else {
      Swal.fire({
        icon: 'success',
        title: 'Evaluación de Administrativo',
        text: "✅ Evaluación guardada con éxito"
      });

 
    }
  } catch (err) {
    console.error("Error en guardarEvaluacionCompleta:", err);
    Swal.fire({
      icon: 'error',
      title: 'Error inesperado',
      text: 'Ocurrió un error al guardar la evaluación'
    });
  }
}

// =============================
// Resetear formulario
// =============================
function valorFormEvaluacion(evaluado='', evaluador='', RangoActuacion='', puntaje='', periodo_evaluado='') {
  document.getElementById('id_evaluado').value = evaluado;
  document.getElementById('id_usuario_evaluador').value = evaluador;
  document.getElementById('periodo-evaluacion').value = periodo_evaluado;
  document.getElementById('id_rango').value = RangoActuacion;
  document.getElementById('puntaje_final').value = puntaje;
}

// =============================
// Cargar datos de evaluado/evaluador/supervisor
// =============================
async function cargarPlanilla() {
  const evaluado_id = sessionStorage.getItem("evaluado_id");
  const idEvalAdmin = sessionStorage.getItem("id_eval_admin");

  if (!evaluado_id || !idEvalAdmin) {
    alert("No se seleccionó evaluación válida");
    return;
  }

  const formData = new FormData();
  formData.append("evaluado_id", evaluado_id);
  formData.append("id_eval_admin", idEvalAdmin);

  const resp = await microApi('controlador/?planilla', formData);

  if (!resp?.success) {
    alert(resp?.message || "Error cargando planilla");
    return;
  }

  const r = resp.data;

  // ============================
  // Evaluado
  // ============================
  document.getElementById("evaluado_fullname").textContent = r.evaluado_nombre || "N/D";
  document.getElementById("evaluado_cedula").textContent = r.evaluado_cedula || "N/D";
  document.getElementById("evaluado_cargo").textContent = r.evaluado_cargo || "N/D";
  document.getElementById("evaluado_ubicacion").textContent = r.evaluado_ubicacion || "N/D";
  document.getElementById("evaluado_id").value = r.evaluado_id;

  // ============================
  // Evaluador
  // ============================
  document.getElementById("evaluador_fullname").textContent = r.evaluador_nombre || "N/D";
  document.getElementById("evaluador_cedula").textContent = r.evaluador_cedula || "N/D";
  document.getElementById("evaluador_cargo").textContent = r.evaluador_cargo || "N/D";
  document.getElementById("evaluador_ubicacion").textContent = r.evaluador_ubicacion || "N/D";
  document.getElementById("evaluador_id").value = r.evaluador_id;

  // ============================
  // Supervisor
  // ============================
  document.getElementById("supervisor_fullname").textContent = r.supervisor_nombre || "N/D";
  document.getElementById("supervisor_cedula").textContent = r.supervisor_cedula || "N/D";
  document.getElementById("supervisor_cargo").textContent = r.supervisor_cargo || "N/D";
}


// =============================
// Periodo de Evaluación
// =============================


async function cargarPeriodoEval() {
  const evaluado_id = document.getElementById("evaluado_id").value;
  const evaluador_id = document.getElementById("evaluador_id").value;
  const idEvalAdmin = sessionStorage.getItem("id_eval_admin");

  const formData = new FormData();
  formData.append("evaluado_id", evaluado_id);
  formData.append("evaluador_id", evaluador_id);
  formData.append("id_eval_admin", idEvalAdmin);

  const resp = await microApi('controlador/?l_periodo', formData);

  if (!resp.success) {
    Swal.fire({ icon: 'error', title: 'Error', text: resp.message });
    return;
  }

  const p = resp.data;

  document.getElementById("fecha-inicio").value = p.fecha_inicio || "";
  document.getElementById("fecha-cierre").value = p.fecha_cierre || "";
  document.getElementById("periodo-evaluacion").value = p.periodo_evaluado || "";
}



// =============================
// Rangos de actuación
// =============================
let rangosActuacion = [];

// =============================
// Cargar rangos de actuación con depuración
// =============================
async function cargarRangosActuacion() {
  const resp = await microApi('controlador/?l_rangos');
  rangosActuacion = Array.isArray(resp[0]) ? resp[0] : resp;

  console.log("✅ Rangos cargados:", rangosActuacion);

  /*if (rangosActuacion.length > 0) {
    console.log("🔎 Claves detectadas en el primer objeto:", Object.keys(rangosActuacion[0]));
    console.log("🔎 Ejemplo de primer rango:", rangosActuacion[0]);
  } else {
    console.warn("⚠️ No se recibieron rangos desde el backend");
  }*/
}

// =============================
// Totales y cálculo de puntaje
// =============================
function actualizarTotalGeneral() {
  const totalObjEl = document.getElementById("total-objetivos");
  const totalCompEl = document.getElementById("total-competencias");
  const puntajeEl  = document.getElementById("puntaje-total");
  const rangoEl    = document.getElementById("rango-actuacion");

  if (!totalObjEl || !totalCompEl || !puntajeEl || !rangoEl) return;

  const totalObj = parseFloat(totalObjEl.textContent) || 0;
  const totalComp = parseFloat(totalCompEl.textContent) || 0;
  const totalGeneral = totalObj + totalComp;

  puntajeEl.textContent = totalGeneral;
  document.getElementById("puntaje_final").value = totalGeneral;

  let rangoTexto = "No definido";
  let rangoId = "";

  for (const r of rangosActuacion) {
    // Normalizar claves posibles
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

  // 👇 Depuración
  /*console.log("🔎 Total:", totalGeneral, "=> Rango:", rangoTexto, "ID:", rangoId);*/
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
// Tablas dinámicas
// =============================
function renderTablaDinamica(datos, idTabla, idTotal, campoPeso, campoNombre, campoId) {
  if (!Array.isArray(datos)) return;

  const tbody = document.querySelector(`#${idTabla} tbody`);
  if (!tbody) return;
  tbody.innerHTML = "";

  datos.forEach(item => {
    const tr = document.createElement("tr");

    // Guardar identificador
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

    // Peso x Rango (celda primero)
    const tdPxR = document.createElement("td");
    tdPxR.textContent = parseFloat(item[campoPeso]) * 1;
    tr.appendChild(tdPxR);

    // Rango (select)
    const tdRango = document.createElement("td");
    const select = document.createElement("select");

    for (let i = 1; i <= 5; i++) {
      const opt = document.createElement("option");
      opt.value = i;
      opt.textContent = i;
      if (i === 1) opt.selected = true;
      select.appendChild(opt);
    }

    select.addEventListener("change", () => {
      const peso = parseFloat(item[campoPeso]);
      const rango = parseInt(select.value);
      tdPxR.textContent = peso * rango;
      actualizarTotales(idTabla, idTotal);

      // 👇 Depuración: mostrar fila capturada
      console.log(`Fila en ${idTabla}:`, {
        id: idValor,
        nombre: item[campoNombre],
        peso: peso,
        rango: rango,
        pesoXRango: peso * rango
      });
    });

    tdRango.appendChild(select);
    tr.insertBefore(tdRango, tdPxR);

    tbody.appendChild(tr);
  });

  actualizarTotales(idTabla, idTotal);
}



async function cargarTablasPlanilla() {
  const cedula = sessionStorage.getItem("cedula_planilla");
  const idEvalAdmin = sessionStorage.getItem("id_eval_admin");
  const evaluado_id = sessionStorage.getItem("evaluado_id");
  if (!cedula) {
    alert("No se seleccionó evaluado");
    return;
  }

  const formData = new FormData();
  formData.append("cedula_usuario", cedula);
  formData.append("id_eval_admin", idEvalAdmin);
  formData.append("evaluado_id", evaluado_id);

  // 👉 Cargar objetivos
  const objResp = await microApi('controlador/?l_objetivos', formData);
  console.log("Respuesta objetivos:", objResp);

  if (!objResp.success) {
    alert(objResp.message || "Error cargando objetivos");
    return;
  }

  const objetivos = Array.isArray(objResp.data[0]) ? objResp.data[0] : objResp.data;
  renderTablaDinamica(objetivos, "tabla-objetivos", "total-objetivos", "peso_objetivo", "nombre_objetivo", "id_odi","id_eval_admin");

  // 👉 Cargar competencias
  const compResp = await microApi('controlador/?l_competencias');
  console.log("Respuesta competencias:", compResp);

  const competencias = Array.isArray(compResp[0]) ? compResp[0] : compResp;
  renderTablaDinamica(competencias, "tabla-competencias", "total-competencias", "peso_competencia", "nombre_competencia", "id_competencia","id_eval_admin");

  // 👉 Cargar rangos y actualizar totales
  await cargarRangosActuacion();
  actualizarTotalGeneral();

  // 👇 Test después de renderizar
  //testCapturaDatos();
}
