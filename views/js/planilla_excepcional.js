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
function validar_form_excepcional(opc) {
  const formulario = document.getElementById('form_excepcional');
  const Data = new FormData(formulario);
  let isValid = true;

  for (let [key, valor] of Data.entries()) {
    if (!valor) {
      console.warn(`⚠️ El campo ${key} está vacío`);
    }

    switch (key) {
      
      case 'ex_id_eval_admin':
        if (!validarnumero(valor)) {
          isValid = false;
          console.error('❌ El ID de evaluación administrativa debe ser numérico.');
        }
        break;

      case 'ex_periodo':
        if (!validarcaracter(valor)) {
          isValid = false;
          console.error('❌ El periodo contiene caracteres inválidos.');
        }
        break;

      case 'ex_fecha':
        // Validar formato de fecha YYYY-MM-DD
        const fechaRegex = /^\d{4}-\d{2}-\d{2}$/;
        if (!fechaRegex.test(valor)) {
          isValid = false;
          console.error('❌ La fecha debe tener el formato YYYY-MM-DD.');
        }
        break;

        case 'motivo_indicador_1':
        case 'motivo_indicador_2':
        case 'motivo_indicador_3':
          if (valor.trim().length === 0) {
            isValid = false;
            console.error(`❌ El motivo para ${key} no puede estar vacío.`);
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
      
      guardarDesempenoExcepcional();
    }
  }


}

async function inicializarPlanillaExcepcional() {
    const idEvalAdmin = sessionStorage.getItem('id_eval_admin');
    const cedula = sessionStorage.getItem('cedula_planilla');
  
    if (!idEvalAdmin || !cedula) {
      alert('No se encontró contexto de evaluación. Regrese y seleccione un evaluado.');
      return;
    }
  
    // Guardar en el formulario
    document.getElementById('ex_id_eval_admin').value = idEvalAdmin;
  
    // Obtener datos de la evaluación (reutilizando el servicio de reportes para no duplicar)
    const resp = await microApi(`controlador/?datos_reportes&id_eval_admin=${idEvalAdmin}`);
    if (!resp || !resp.success) {
      Swal.fire({ icon: 'error', title: 'Error', text: resp?.message || 'No se pudieron cargar los datos.' });
      return;
    }
  
    const info = resp.data || {};
    const nombre = info.nombre_evaluado || 'N/D';
    const cedulaEvaluado = info.cedula_evaluado || 'N/D';
    const cargo = info.cargo_evaluado || 'N/D';
    const ubicacion = info.ubicacion_evaluado || 'N/D';
    const periodo = info.periodo_evaluado || 'N/D';
    const fechaInicio = info.fecha_inicio || '';
    const fechaCierre = info.fecha_cierre || '';
    const puntajeFinal = info.puntaje_final ?? 'N/D';
    const rangoActuacion = info.rango_actuacion || 'N/D';
  
    // Poblar Sección A
    setText('ex_eval_fullname', nombre);
    setText('ex_eval_cedula', cedulaEvaluado);
    setText('ex_eval_cargo', cargo);
    setText('ex_eval_ubicacion', ubicacion);
    setText('ex_periodo_texto', periodo);
    setText('ex_puntaje_final', puntajeFinal);
    setText('ex_rango_actuacion', rangoActuacion);
  
    // Calcular año(s) a partir de fecha_inicio y fecha_cierre
    const anioInicio = extraerAnio(fechaInicio);
    const anioCierre = extraerAnio(fechaCierre);
    const aniosTexto = (anioInicio && anioCierre)
      ? (anioInicio === anioCierre ? `${anioInicio}` : `${anioInicio}–${anioCierre}`)
      : (anioInicio || anioCierre || 'N/D');
    setText('ex_periodo_anio', aniosTexto);
  
    // Setear periodo y fecha (para la tabla desempeno_excepcional)
  const fechaHoy = obtenerFechaHoy();
  document.getElementById('ex_periodo').value = periodo;
  document.getElementById('ex_fecha').value = fechaHoy;

  // Mostrar fecha de emisión en la interfaz
  setText('ex_fecha_emision', fechaHoy);
  
    // Verificar rango y habilitar
    const btnGuardar = document.getElementById('btn_guardar_excepcional');
    const badge = document.getElementById('badge-excepcional');
    if (normalizarTexto(rangoActuacion) === normalizarTexto('Desempeño excepcional')) {
        btnGuardar.disabled = false;
        badge.classList.remove('badge-warn');
        badge.classList.add('badge-ok');
        badge.textContent = 'Opción habilitada: rango "Desempeño excepcional" confirmado';
        await cargarIndicadoresExcepcional(cedulaEvaluado);
      } else {
        btnGuardar.disabled = true;
        badge.classList.remove('badge-ok');
        badge.classList.add('badge-warn');
        badge.textContent = 'Opción inactiva: requiere rango "Desempeño excepcional"';
        renderIndicadoresPlaceholder();
      }
  }
  
  function setText(id, txt) {
    const el = document.getElementById(id);
    if (el) el.textContent = txt;
  }
  
  function extraerAnio(fechaStr) {
    if (!fechaStr) return '';
    const d = new Date(fechaStr);
    return isNaN(d.getTime()) ? '' : d.getFullYear();
  }
  
  function obtenerFechaHoy() {
    const d = new Date();
    const yyyy = d.getFullYear();
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const dd = String(d.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
  }
  
  function normalizarTexto(s) {
    return String(s || '')
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .trim();
  }
  
  // Cargar indicadores desde backend (3 fijos). Si el backend los filtra por usuario, enviamos la cédula para resolver el id_usuario.
  async function cargarIndicadoresExcepcional(idEvalAdmin) {
    const resp = await microApi(`controlador/?l_indicadores_excepcional&id_eval_admin=${idEvalAdmin}`);
    if (!resp || !resp.success) {
      console.warn("No se pudieron cargar indicadores excepcionales:", resp?.message);
      renderIndicadoresExcepcional([]); 
      return;
    }
  
    const indicadores = Array.isArray(resp.data) ? resp.data : [];
    renderIndicadoresExcepcional(indicadores);
  }
  
  function renderIndicadoresExcepcional(indicadores) {
    const cont = document.getElementById('contenedor-indicadores');
    cont.innerHTML = "";
  
    if (!Array.isArray(indicadores) || indicadores.length === 0) {
      cont.innerHTML = "<p class='text-muted'>No hay indicadores activos.</p>";
      return;
    }
  
    indicadores.forEach(ind => {
      const card = document.createElement("div");
      card.classList.add("indicador-card", "card", "mb-3", "p-2");
      card.setAttribute("data-indicador-id", ind.indicador_id);
  
      card.innerHTML = `
        <div class="card-body">
          <h6 class="card-title"><strong></strong> ${ind.indicador}</h6>
          <textarea class="form-control motivo-textarea" 
                    placeholder="Escriba el motivo para este indicador..."
                    rows="3"></textarea>
        </div>
      `;
  
      cont.appendChild(card);
    });
  }
  
  async function guardarDesempenoExcepcional() {
    const btn = document.getElementById('btn_guardar_excepcional');
    if (btn.disabled) {
      Swal.fire({ icon: 'info', title: 'No habilitado', text: 'El rango no es “Desempeño excepcional”.' });
      return;
    }
  
    // 👉 Capturar valores del formulario con tu helper
    let datos = capturarValoresFormulario('form_excepcional');
  
    // 🔎 Depuración: mostrar todos los pares clave/valor
    console.group("📋 Valores capturados del formulario excepcional");
    for (let [key, valor] of datos.entries()) {
      console.log(`${key} => ${valor}`);
    }
    console.groupEnd();
  
    // Capturar indicadores y motivos
    const cont  = document.getElementById('contenedor-indicadores');
    const cards = cont.querySelectorAll('.indicador-card');
  
    if (cards.length < 1) {
      Swal.fire({ icon: 'warning', title: 'Faltan indicadores', text: 'Debe existir al menos un indicador.' });
      return;
    }
  
    const indicadores = [];
    for (const card of cards) {
      const indicadorId = card.getAttribute('data-indicador-id');
      const motivo      = (card.querySelector('.motivo-textarea')?.value || '').trim();
  
      console.log("Capturado:", { indicadorId, motivo }); // 🔎 depuración
  
      if (!indicadorId || !motivo) {
        Swal.fire({ icon: 'warning', title: 'Datos incompletos', text: 'Cada indicador debe tener ID y motivo.' });
        return;
      }
  
      indicadores.push({ indicador_id: indicadorId, motivo });
    }
  
    // 👉 Agregar indicadores al FormData
    datos.append("indicadores", JSON.stringify(indicadores));
  
    try {
      const resp = await microApi("controlador/?g_desempeno_excepcional", datos);
      console.log("Respuesta desempeño excepcional:", resp);
  
      if (!resp.success) {
        Swal.fire({
          icon: 'warning',
          title: 'Registro duplicado',
          text: resp.message || "Ya existe una planilla excepcional para esta evaluación"
        });
      } else {
        Swal.fire({
          icon: 'success',
          title: 'Desempeño Excepcional',
          text: "✅ Planilla guardada con éxito"
        });
      }
    } catch (err) {
      console.error("Error en guardarDesempenoExcepcional:", err);
      Swal.fire({
        icon: 'error',
        title: 'Error inesperado',
        text: 'Ocurrió un error al guardar la planilla excepcional'
      });
    }
  }
  