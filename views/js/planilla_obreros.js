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
function validar_form_evaluacion_obrero(opc) {
  const formulario = document.getElementById('formulario_planilla_obrero');
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
      case 'rango_id':
        if (!validarnumero(valor)) {
          alert("Opción de rango inválida.");
          isValid = false;
        }
        break;
      case 'fecha_inicio':
      case 'fecha_cierre':
      case 'periodo_evaluacion':
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
      
      guardarEvaluacionObrero();
    }
  }


}

// =============================
// Variables globales
// =============================
let factoresObrero = [];
let rangosCalificacion = [];

// =============================
// Cargar datos del evaluado/evaluador
// =============================
/*async function cargarPlanillaObrero() {
  const cedula = sessionStorage.getItem("cedula_planilla_obrero");
  if (!cedula) {
    alert("No se seleccionó evaluado");
    return;
  }
// ============================ // 1. Cargar datos_empleado.json // ============================ 
const datosEmpleadoResp = await fetch("views/js/datos_empleado.json"); 
const datosEmpleado = await datosEmpleadoResp.json();
  

  const formData = new FormData();
  formData.append("cedula_usuario", cedula);

  const resp = await microApi('controlador/?planilla_obreros', formData);

  if (!resp?.success) {
    alert(resp?.message || "Error cargando planilla");
    return;
  }

  const r = resp.data;

  // ============================================
  // 🔥 AGREGAR tiempo_puesto AL JSON DEL FRONTEND
  // ============================================
  r.tiempo_puesto = await calcularTiempoPuestoPorCedula(r.cedula_usuario);

  console.log("JSON completo recibido (con tiempo_puesto):", r);


  // =============================
  // Evaluado
  // =============================
  document.getElementById("evaluado_nombre").value = r.nombre_completo || "N/D";
  document.getElementById("evaluado_ci").value = r.cedula_usuario || "N/D";
  document.getElementById("evaluado_cargo").value = r.cargo_evaluado || "Sin cargo";
  document.getElementById("fecha_ingreso").value = r.fecha_inicio || "";
  document.getElementById("tiempo_puesto").value = r.tiempo_puesto;
  document.getElementById("ubicacion_admin").value = r.ubicacion_administrativa || "N/D";
  document.getElementById("ubicacion_fisica").value = r.ubicacion_fisica || "N/D";
  document.getElementById("periodo").value = r.periodo_evaluacion || "N/D";
  document.getElementById("area_ocupacional").value = r.area_ocupacional || "N/D";

  // =============================
  // Evaluador
  // =============================
  document.getElementById("evaluador_nombre").value = r.nombre_completo_evaluador || "N/D";
  document.getElementById("evaluador_cargo").value = r.cargo_evaluador || "Sin cargo";
  document.getElementById("evaluador_ubicacion").value = r.ubicacion_evaluador || "N/D";
}*/

async function cargarPlanillaObrero() {
  const cedula = sessionStorage.getItem("cedula_planilla_obrero");
  const idEvalOb = sessionStorage.getItem("id_eval_obreros");
  if (!cedula) {
      alert("No se seleccionó evaluado");
      return;
  }

  console.log("👉 Enviando al servicio :", {
    cedula_usuario: cedula
   
  });

  // ============================
  // 1. Cargar datos_empleado.json (solo para tiempo_puesto)
  // ============================
  const datosEmpleadoResp = await fetch("views/js/datos_empleado.json");
  const datosEmpleado = await datosEmpleadoResp.json();

  // ============================
  // 2. Consultar datos de la planilla
  // ============================
  const formData = new FormData();
  formData.append("cedula_usuario", cedula);
  formData.append("id_eval_obreros", idEvalOb);

  const resp = await microApi('controlador/?planilla_obreros', formData);

  if (!resp?.success) {
      alert(resp?.message || "Error cargando planilla");
      return;
  }

  const r = resp.data;

  // ============================
  // 3. Calcular tiempo en el puesto
  // ============================
  r.tiempo_puesto = await calcularTiempoPuestoPorCedula(r.cedula_usuario);

  console.log("📌 JSON recibido (con tiempo_puesto):", r);

  // ============================
  // 4. Llenar campos visibles
  // ============================
  document.getElementById("evaluado_nombre").value = r.nombre_completo || "N/D";
  document.getElementById("evaluado_ci").value = r.cedula_usuario || "N/D";
  document.getElementById("evaluado_cargo").value = r.cargo_evaluado || "Sin cargo";
  document.getElementById("fecha_ingreso").value = r.fecha_inicio || "";
  document.getElementById("tiempo_puesto").value = r.tiempo_puesto;
  document.getElementById("ubicacion_admin").value = r.ubicacion_administrativa || "N/D";
  document.getElementById("ubicacion_fisica").value = r.ubicacion_fisica || "N/D";
  document.getElementById("periodo").value = r.periodo_evaluacion || "N/D";
  document.getElementById("area_ocupacional").value = r.area_ocupacional || "N/D";

  // ============================
  // 5. Llenar datos del evaluador
  // ============================
  document.getElementById("evaluador_nombre").value = r.nombre_completo_evaluador || "N/D";
  document.getElementById("evaluador_cargo").value = r.cargo_evaluador || "Sin cargo";
  document.getElementById("evaluador_ubicacion").value = r.ubicacion_evaluador || "N/D";

  // ============================
  // 6. Llenar campos ocultos (OBLIGATORIOS PARA GUARDAR)
  // ============================
  document.getElementById("id_evaluado").value = r.id_evaluado;
  document.getElementById("id_usuario").value = r.id_usuario_evaluador;
  document.getElementById("id_eval_obreros").value = r.id_eval_obreros;

  console.log("📥 Campos ocultos cargados:", {
      id_evaluado: r.id_evaluado,
      id_usuario: r.id_usuario_evaluador,
      id_eval_obreros: r.id_eval_obreros
  });
}

// =============================
// Resetear formulario
// =============================
function valorFormEvaluacionObrero(evaluado='', evaluador='', RangoCalificacion='', puntajeObrero='', periodo_evaluacion='',tiempo_puesto='') {
  document.getElementById('id_evaluado_obrero').value = evaluado;
  document.getElementById('id_usuario_evaluador').value = evaluador;
  document.getElementById('periodo-evaluacion').value = periodo_evaluacion;
  document.getElementById('rango_id').value = RangoCalificacion;
  document.getElementById('puntaje_final').value = puntajeObrero;
  document.getElementById('tiempo_puesto').value = tiempo_puesto;
}

// =============================
// Calcular tiempo en el puesto usando JSON "create"
// =============================
async function calcularTiempoPuestoPorCedula(cedula) {
  try {
    // 1. Cargar JSON usando microApi
    const resp = await microApi('views/js/datos_empleado.json');

    // Validar formato
    if (!Array.isArray(resp) || resp.length === 0 || !resp[0].data) {
      console.error("JSON con formato inesperado");
      return "";
    }

    const datos = resp[0].data;
    const cedulaBusqueda = cedula.toLowerCase();

    // 2. Buscar empleado por PIN (cédula)
    const empleado = datos.find(emp => emp.pin && emp.pin.toLowerCase() === cedulaBusqueda);

    if (!empleado || !empleado.create) {
      console.warn("Empleado no encontrado o sin fecha 'create'");
      return "";
    }

    // 3. Extraer solo la fecha (YYYY-MM-DD)
    const fechaStr = empleado.create.split(" ")[0];
    const ingreso = new Date(fechaStr);
    const hoy = new Date();

    if (isNaN(ingreso.getTime())) {
      console.warn("Fecha inválida en JSON");
      return "";
    }

    // 4. Calcular diferencia
    const diffMs = hoy - ingreso;
    const diffDias = Math.floor(diffMs / (1000 * 60 * 60 * 24));

    const años = Math.floor(diffDias / 365);
    const meses = Math.floor((diffDias % 365) / 30);

    return `${años}`;

  } catch (error) {
    console.error("Error calculando tiempo en el puesto:", error);
    return "";
  }
}

// =============================
// Cargar factores y criterios
// =============================
async function cargarFactoresYCriteriosObrero() {
  const resp = await microApi('controlador/?l_factores_criterios_obreros');
  if (!resp?.success) {
    Swal.fire({ icon: 'error', title: 'Error', text: resp?.message || 'No se pudo cargar factores' });
    return;
  }

  factoresObrero = resp.data;
  renderFactoresObrero(factoresObrero);

  // Cargar rangos de calificación
  const respRangos = await microApi('controlador/?l_rangos_calificacion');
  rangosCalificacion = respRangos?.data || [];
}

// =============================
// Render de factores
// =============================
function renderFactoresObrero(factores) {
  const tbody = document.querySelector(".table-eval tbody");
  tbody.innerHTML = "";

  factores.forEach(f => {

      // ============================
      // Encabezado del factor
      // ============================
      const trHeader = document.createElement("tr");
      trHeader.className = "factor-title";
      trHeader.innerHTML = `
          <td colspan="5">
              <strong>${f.nombre_factor}</strong> (${f.valor_factor}%) -
              <span class="fw-normal">Puntaje factor: </span>
              <span class="factor-score" id="score-${f.factor_id}">0</span>
          </td>
      `;
      tbody.appendChild(trHeader);

      // ============================
      // Criterios del factor
      // ============================
      f.criterios.forEach((c, index) => {
          const tr = document.createElement("tr");

          if (index === 0) {
              tr.innerHTML = `
                  <td><strong>${c.codigo_criterio}</strong></td>
                  <td>${c.descripcion_criterio}</td>
                  <td rowspan="${f.criterios.length}" class="factor-weight text-center align-middle">
                      ${f.valor_factor}%
                  </td>
                  <td class="col-puntaje text-center">${c.valor_criterio}</td>
                  <td class="text-center">
                      <input type="checkbox" class="form-check-input factor-option"
                          data-factor="${f.factor_id}"
                          data-criterio="${c.criterio_id}"
                          data-score="${c.valor_criterio}">
                  </td>
              `;
          } else {
              tr.innerHTML = `
                  <td><strong>${c.codigo_criterio}</strong></td>
                  <td>${c.descripcion_criterio}</td>
                  <td class="col-puntaje text-center">${c.valor_criterio}</td>
                  <td class="text-center">
                      <input type="checkbox" class="form-check-input factor-option"
                          data-factor="${f.factor_id}"
                          data-criterio="${c.criterio_id}"
                          data-score="${c.valor_criterio}">
                  </td>
              `;
          }

          tbody.appendChild(tr);
      });
  });

  // Eventos
  document.querySelectorAll('.factor-option').forEach(opt => {
      opt.addEventListener('change', () => {
          const factor = opt.dataset.factor;
          document.querySelectorAll(`.factor-option[data-factor="${factor}"]`).forEach(o => {
              if (o !== opt) o.checked = false;
          });
          recalculateScores();
      });
  });
}


// =============================
// Recalcular puntajes y calificación
// =============================
function recalculateScores() {
  const factorScores = {};

  document.querySelectorAll('.factor-option').forEach(opt => {
      const factor = opt.dataset.factor;
      const score = Number(opt.dataset.score) || 0;
      if (!factorScores[factor]) factorScores[factor] = 0;
      if (opt.checked) factorScores[factor] = score;
  });

  // Puntaje por factor
  document.querySelectorAll('.factor-score').forEach(span => {
      const factorId = span.id.replace('score-', '');
      span.textContent = factorScores[factorId] || 0;
  });

  // Total
  let total = 0;
  Object.values(factorScores).forEach(v => total += v);
  document.getElementById('total-score').value = total;

  // Rango
  let rangoId = 0;
  let rangoNombre = "Sin calificación";

  rangosCalificacion.forEach(r => {
      if (total >= r.puntaje_min && total <= r.puntaje_max) {
          rangoId = r.rango_id;
          rangoNombre = r.nombre_rango;
      }
  });

  document.getElementById("rango_id").value = rangoId;
  document.getElementById("label-eval").textContent = rangoNombre;

  validarFactoresCompletos();
}

function validarFactoresCompletos() {
  let valido = true;

  document.querySelectorAll('.factor-title').forEach(header => {
      const factorId = header.querySelector('.factor-score').id.replace('score-', '');

      const opciones = document.querySelectorAll(`.factor-option[data-factor="${factorId}"]`);
      const algunaMarcada = Array.from(opciones).some(opt => opt.checked);

      if (!algunaMarcada) {
          header.classList.add('factor-incompleto');
          valido = false;
      } else {
          header.classList.remove('factor-incompleto');
      }
  });

  return valido;
}


// =============================
// Guardar evaluación
// =============================
async function guardarEvaluacionObrero() {

  if (!validarFactoresCompletos()) {
      Swal.fire({
          icon: 'warning',
          title: 'Faltan factores por evaluar',
          text: 'Debe seleccionar una opción en cada factor antes de guardar.'
      });
      return;
  }

  // =============================
  // Capturar valores del formulario (USANDO TU FUNCIÓN)
  // =============================
  let datos = capturarValoresFormulario("formulario_planilla_obrero");

  // =============================
  // Capturar criterios seleccionados
  // =============================
  const seleccion = [];
  document.querySelectorAll('.factor-option:checked').forEach(opt => {
    seleccion.push({
      criterio_id: parseInt(opt.dataset.criterio),
      puntaje_obtenido: parseInt(opt.dataset.score)
  });
  });

  datos.append("seleccion", JSON.stringify(seleccion));

  // =============================
  // Depuración: ver qué se está enviando
  // =============================
  console.group("📤 Datos enviados al backend (g_evaluacion_obrero)");
  for (let pair of datos.entries()) {
      console.log(pair[0] + ": ", pair[1]);
  }
  console.groupEnd();

  // =============================
  // Enviar al backend
  // =============================
  const resp = await microApi('controlador/?g_evaluacion_obreros', datos);

  console.log("ID de evaluación recibido del backend:", resp.id_eval_obreros);

  if (!resp?.success) {
      Swal.fire({ icon: 'error', title: 'Error', text: resp?.message });
      return;
  }

  Swal.fire({
      icon: 'success',
      title: 'Evaluación guardada',
      text: 'La evaluación del obrero fue registrada correctamente'
  });
}
