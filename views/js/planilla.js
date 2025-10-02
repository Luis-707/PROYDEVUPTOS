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
  }

  if (isValid) {
    if (opc === 1) guardarEvaluacion();
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

// =============================
// Validación de campos obligatorios
// =============================
function validarCamposObligatorios(fd) {
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
}
// =============================
// Guardar Evaluación
// =============================
async function guardarEvaluacion() {
  let datosEval = capturarValoresFormulario('formulario_planilla');

  const idUsuario = document.getElementById('id_usuario_evaluador').value;
  const idEvaluado = document.getElementById('id_evaluado').value;
  const idRango = document.getElementById('id_rango').value;
  const puntajeFinal = document.getElementById('puntaje_final').value 
    || document.getElementById('puntaje-total').textContent;

  datosEval.append('id_usuario', idUsuario);
  datosEval.append('id_evaluado', idEvaluado);
  datosEval.append('id_rango', idRango);
  datosEval.append('puntaje_final', puntajeFinal);

  // 👇 Validación en consola
  validarCamposObligatorios(datosEval);

  // 👇 Depuración: imprimir todo el FormData
  console.group("📦 Contenido completo del FormData");
  for (const [k, v] of datosEval.entries()) {
    console.log('${k} => ${v}');
  }
  console.groupEnd();
  try {
    const resp = await microApi('controlador/?g_evaluacion', datosEval);

    if (!resp.success) {
      alert(resp.message || "Error al guardar la evaluación");
    } else {
      alert("✅ Evaluación guardada con éxito");
      valorFormEvaluacion();
      
    }
  } catch (err) {
    console.error("Error en guardarEvaluacion:", err);
    alert("Ocurrió un error al guardar la evaluación");
  }
}

// =============================
// Resetear formulario
// =============================
function valorFormEvaluacion(evaluado='', evaluador='', Finicio='', Fcierre='', RangoActuacion='', puntaje='') {
  document.getElementById('id_evaluado').value = evaluado;
  document.getElementById('id_usuario_evaluador').value = evaluador;
  document.getElementById('fecha-inicio').value = Finicio;
  document.getElementById('fecha-cierre').value = Fcierre;
  document.getElementById('id_rango').value = RangoActuacion;
  document.getElementById('puntaje_final').value = puntaje;
}

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
  // 👇 Setear id_evaluado desde backend
  document.getElementById("id_evaluado").value = registro.id_evaluado;

  // Evaluador
  const empEv = empleados.find(emp => emp.pin === registro.cedula_evaluador || emp.pin_str === registro.cedula_evaluador);
  document.getElementById("evaluador_fullname").textContent = empEv?.fullname || "N/D";
  document.getElementById("evaluador_cedula").textContent = registro.cedula_evaluador || "N/D";
  document.getElementById("evaluador_cargo").textContent = registro.cargo_evaluador || "Sin cargo";
  document.getElementById("evaluador_ubicacion").textContent = empEv?.additional || "N/D";
  // 👇 Setear id_usuario evaluador desde backend
  document.getElementById("id_usuario_evaluador").value = registro.id_usuario_evaluador;

  // Supervisor
  const empSup = empleados.find(emp => emp.pin === registro.cedula_supervisor || emp.pin_str === registro.cedula_supervisor);
  document.getElementById("supervisor_fullname").textContent = empSup?.fullname || "N/D";
  document.getElementById("supervisor_cedula").textContent = registro.cedula_supervisor || "N/D";
  document.getElementById("supervisor_cargo").textContent = registro.cargo_supervisor || "Sin cargo";
}

// =============================
// Periodo de Evaluación
// =============================


function setPeriodoAutomatico() {
  const hoy = new Date();
  const year = hoy.getFullYear();
  const mes = hoy.getMonth() + 1;

  let periodo = "";
  let fechaInicio = "";
  let fechaCierre = "";

  if (mes >= 1 && mes <= 6) {
    periodo = `Periodo I-${year}`;
    fechaInicio = `${year}-01-01`;
    fechaCierre = `${year}-06-01`;
  } else {
    periodo = `Periodo II-${year}`;
    fechaInicio = `${year}-07-01`;
    fechaCierre = `${year}-12-01`;
  }

  // Setear en inputs
  document.getElementById("fecha-inicio").value = fechaInicio;
  document.getElementById("fecha-cierre").value = fechaCierre;
  const periodoInput = document.getElementById("periodo-evaluacion");
periodoInput.value = periodo;

  // 👇 Capturar id_evaluado oculto
  const idEvaluado = document.getElementById("id_evaluado").value;

  // Guardar en BD automáticamente
  const formData = new FormData();
  formData.append("id_evaluado", idEvaluado);
  formData.append("periodo_evaluado", periodo);
  formData.append("fecha_inicio", fechaInicio);
  formData.append("fecha_cierre", fechaCierre);

  microApi("controlador/?u_periodo", formData)
    .then(resp => {
      if (!resp.success) {
        console.error("Error al actualizar periodo:", resp.message);
      } else {
        console.log("✅ Periodo actualizado automáticamente:", periodo);
      }
    })
    .catch(err => console.error("Error en update periodo:", err));
}


// =============================
// Rangos de actuación
// =============================
let rangosActuacion = [];

async function cargarRangosActuacion() {
  const resp = await microApi('controlador/?l_rangos');
  rangosActuacion = Array.isArray(resp[0]) ? resp[0] : resp;
  console.log("✅ Rangos cargados:", rangosActuacion);
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
    const min = parseInt(r.puntaje_minimo);
    const max = parseInt(r.puntaje_maximo);
    if (totalGeneral >= min && totalGeneral <= max) {
      rangoTexto = r.rango_actuacion;
      rangoId = r.id_rango;
      break;
    }
  }

  rangoEl.textContent = rangoTexto;
  document.getElementById("id_rango").value = rangoId;
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
function renderTablaDinamica(datos, idTabla, idTotal, campoPeso, campoNombre) {
  if (!Array.isArray(datos)) return;

  const tbody = document.querySelector(`#${idTabla} tbody`);
  if (!tbody) return;
  tbody.innerHTML = "";

  datos.forEach(item => {
    const tr = document.createElement("tr");

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

    const tdPxR = document.createElement("td");
    tdPxR.textContent = "0";
    tr.appendChild(tdPxR);

    tbody.appendChild(tr);
  });
}

async function cargarTablasPlanilla() {
  const cedula = sessionStorage.getItem("cedula_planilla");
  if (!cedula) {
    alert("No se seleccionó evaluado");
    return;
  }

  const formData = new FormData();
  formData.append("cedula_usuario", cedula);

  // 👉 Cargar objetivos
  const objResp = await microApi('controlador/?l_objetivos', formData);
  console.log("Respuesta objetivos:", objResp);

  if (!objResp.success) {
    alert(objResp.message || "Error cargando objetivos");
    return;
  }

  const objetivos = Array.isArray(objResp.data[0]) ? objResp.data[0] : objResp.data;
  renderTablaDinamica(objetivos, "tabla-objetivos", "total-objetivos", "peso_objetivo", "nombre_objetivo");

  // 👉 Cargar competencias
  const compResp = await microApi('controlador/?l_competencias');
  console.log("Respuesta competencias:", compResp);

  const competencias = Array.isArray(compResp[0]) ? compResp[0] : compResp;
  renderTablaDinamica(competencias, "tabla-competencias", "total-competencias", "peso_competencia", "nombre_competencia");

  // 👉 Cargar rangos y actualizar totales
  await cargarRangosActuacion();
  actualizarTotalGeneral();
}
