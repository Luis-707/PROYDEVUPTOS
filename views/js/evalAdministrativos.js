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
/*function validarcorreo(cadena){
  var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  return regex.test(cadena);
}*/

   
function validar_formEvalAdmin(opc) {

   
  // Obtener el formulario
  var formulario = document.getElementById('formulario_EvalAdmin');
  //console.log(formulario);
  // Crear un objeto FormData
  var Data = new FormData(formulario);
  let isValid = true; // Variable para controlar si el formulario es válido
  console.log(Data);
  // Validar cada campo
  for (var [key, valor] of Data.entries()) {
      
      switch (key) {   
        
          case 'fecha_inicio':
              if (valor === "") {
                  alert("El campo Fecha de Inicio no puede estar vacío.");
                  isValid = false; // Marca como inválido
              }
              break;
          case 'fecha_cierre':
              if (valor === "") {
                  alert("El campo Fecha de Cierre no puede estar vacío.");
                  isValid = false; // Marca como inválido
              }
              break;    

        
          case 'periodo_evaluado':
              if (!validarcaracter(valor)) {
                  alert("Opcion Invalida. ");
                  isValid = false; // Marca como inválido
              }
              break;
           

               

          // Si hay un error, salimos del bucle
          if (!isValid) {
              break;
          }
      }
  }

  // Si todas las validaciones pasan
  if (isValid) {        
      
     //formulario.submit(); // Enviar el formulario
      if(opc==1){
          guardarPeriodoEvaluacion();  
      }
      else{
          actualizarPeriodoEvaluacion();  
      }
          
  }
}

function ajustarFechasPeriodo(periodo) {
  const inicioInput = document.getElementById('fecha_inicio');
  const cierreInput = document.getElementById('fecha_cierre');
  const year = new Date().getFullYear(); // año actual

  if (periodo === 'Enero-Junio') {
    inicioInput.value = `${year}-01-01`;
    cierreInput.value = `${year}-06-01`;
  } else if (periodo === 'Julio-Diciembre') {
    inicioInput.value = `${year}-07-01`;
    cierreInput.value = `${year}-12-01`;
  } else {
    // Si no hay periodo válido, limpiar los campos
    inicioInput.value = '';
    cierreInput.value = '';
  }
}



async function guardarPeriodoEvaluacion() {
  try {
    // Capturar valores del formulario
    let datos = capturarValoresFormulario('formulario_EvalAdmin');

    // Asegurarnos de que el id_evaluado se envía
    let idEvaluado = document.getElementById('id_evaluado').value;
    if (!idEvaluado) {
      Swal.fire({
        icon: 'warning',
        title: 'Falta seleccionar evaluado',
        text: 'Debe seleccionar un usuario evaluado antes de guardar.'
      });
      return;
    }
    datos.append('id_evaluado', idEvaluado);

    // Validar fechas y periodo antes de enviar
    const periodo = document.getElementById('periodo_evaluado').value;
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaCierre = document.getElementById('fecha_cierre').value;

    if (!validarFechasPeriodo(fechaInicio, fechaCierre, periodo)) {
      Swal.fire({
        icon: 'error',
        title: 'Fechas inválidas',
        text: 'Las fechas no corresponden al periodo seleccionado.'
      });
      return;
    }

    // Llamada al servicio PHP para guardar
    const resp = await microApi('controlador/?g_EvalAdmin', datos);

    // Validar respuesta
    if (resp.success) {
      Swal.fire({
        icon: 'success',
        title: 'Registro de Evaluacion guardado',
        text: resp.message
      });

      // Refrescar tabla y limpiar formulario
      listarEvalAdmin();
      valorFormEvalAdmin();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: resp.message
      });
    }
  } catch (err) {
    console.error("Error en guardarPeriodoEvaluacion:", err);
    Swal.fire({
      icon: 'error',
      title: 'Error inesperado',
      text: 'Ocurrió un error al guardar el periodo'
    });
  }
}

function validarFechasPeriodo(fechaInicio, fechaCierre, periodo) {
  const fi = (fechaInicio || '').trim();
  const fc = (fechaCierre || '').trim();

  if (periodo === "Enero-Junio" && fi.endsWith("-01-01") && fc.endsWith("-06-01")) {
    return true;
  }
  if (periodo === "Julio-Diciembre" && fi.endsWith("-07-01") && fc.endsWith("-12-01")) {
    return true;
  }
  return false;
}

//Consultar evaluacines de personal administrativo

async function listarEvalAdmin(){
    var resp = await microApi('controlador/?listar_evalAdministrativo');
    listarTablaEvalAdmin(resp);
}  

//Listar las evaluaciones de personal administrativo en una tabla

async function listarTablaEvalAdmin(datos) {
  const tbody = document.querySelector("#tabla-EvalAdmin tbody");
  tbody.innerHTML = "";

  const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

  if ($.fn.DataTable.isDataTable('#tabla-EvalAdmin')) {
      $('#tabla-EvalAdmin').DataTable().destroy();
  }

  const tableData = registros.map(item => {
      const cedula = String(item.cedula_usuario).trim();
      const fullname = item.nombre_completo || "No encontrado";
      const ubicacion = item.ubicacion_administrativa || "Sin ubicación";
      const cargoTexto = item.cargo_evaluado || "Sin cargo";
      const periodoEvaluado = item.periodo_evaluado || "";
      const anioInicio = item.anio_inicio;
      const estado_evaluacion = item.estado_eval_admin;

      const acciones = `
          <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="icon-base bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);" onclick="abrirModalEditarPeriodo(${item.id_eval_admin})">
                      <i class="icon-base bx bx-edit-alt me-1"></i>Editar
                  </a>
                  <a class="dropdown-item" href="javascript:void(0);" onclick="abrirModalObjetivosEvaluador(${item.id_eval_admin})">
                      <i class="icon-base bx bx-target-lock me-1"></i>Objetivos
                  </a>
                  <a class="dropdown-item" href="javascript:void(0);" onclick="cambiarEstadoEvalAdmin(${item.id_eval_admin},'${item.estado_eval_admin}')">
                      <i class="icon-base bx bx-toggle-right me-1"></i>Cambiar estado
                  </a>
              </div>
          </div>
      `;

      return [
          cedula,
          fullname,
          ubicacion,
          cargoTexto,
          anioInicio,
          periodoEvaluado,
          estado_evaluacion,
          acciones
      ];
  });

  $('#tabla-EvalAdmin').DataTable({
      data: tableData,
      columns: [
          { title: "Cédula", width: "120px" },
          { title: "Nombre Completo" },
          { title: "Ubicación", width: "150px" },
          { title: "Cargo" },
          { title: "Año", width: "80px" },
          { title: "Período", width: "100px" },
          { title: "Estado", width: "100px" },
          { 
              title: "Acciones", 
              width: "120px",
              orderable: false,
              searchable: false
          }
      ],
      pageLength: 25,
      responsive: true,
      order: [[0, 'asc']],
      language: {
          search: "Buscar:",
          lengthMenu: "Mostrar _MENU_ registros por página",
          info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
          infoEmpty: "Mostrando 0 a 0 de 0 registros",
          emptyTable: "No hay datos disponibles en la tabla",
          zeroRecords: "No se encontraron registros coincidentes",
          paginate: {
              previous: "Anterior",
              next: "Siguiente"
          }
      }
  });
}

//==============================================================//
async function actualizarPeriodoEvaluacion() {
  // Capturar valores del formulario del modal
  let datosPeriodo = capturarValoresFormulario('form-modal-editar-periodo');

  // Reconstruir fechas completas según el periodo
const periodo = datosPeriodo.get("periodo_evaluado");
const yearInicio = datosPeriodo.get("fecha_inicio");
const yearCierre = datosPeriodo.get("fecha_cierre");

if (periodo === "Enero-Junio") {
  datosPeriodo.set("fecha_inicio", yearInicio + "-01-01");
  datosPeriodo.set("fecha_cierre", yearCierre + "-06-30");
} else if (periodo === "Julio-Diciembre") {
  datosPeriodo.set("fecha_inicio", yearInicio + "-07-01");
  datosPeriodo.set("fecha_cierre", yearCierre + "-12-31");
}

  try {
    // Llamada al servicio PHP
    const resp = await microApi('controlador/?a_periodo', datosPeriodo);

    // Validar respuesta
    if (typeof resp === 'string' && resp.includes(' No Existe')) {
      Swal.fire({
        icon: 'error',
        title: 'Evaluación no encontrada',
        text: resp
      });
    } else {
      // Refrescar tabla y limpiar formulario
      listarEvalAdmin();
      valorFormEvalAdmin();

      Swal.fire({
        icon: 'success',
        title: 'Periodo actualizado',
        text: 'Las fechas y el periodo se actualizaron con éxito'
      });

      // Cerrar modal si usas Bootstrap
      $("#modalEditarPeriodo").modal("hide");
    }
  } catch (err) {
    console.error("Error actualizando periodo:", err);
    Swal.fire({
      icon: 'error',
      title: 'Error inesperado',
      text: 'Ocurrió un error al actualizar el periodo'
    });
  }
}

function valorFormEvalAdmin(evaluado=''){
  // Asignar valores a los campos del formulario
  document.getElementById('id_evaluado').value = evaluado;  
  
}

//===============================================================//
//Select de evaluados

async function listarEvaluadosAdmin() {
  try {
    // Obtener lista de evaluados desde API
    const respEvaluados = await microApi('controlador/?listar_datos');
    if (typeof respEvaluados === 'string') {
      console.error('Error al listar usuarios:', respEvaluados);
      return;
    }

    llenarSelectEvaluadosA(respEvaluados);
  } catch (err) {
    console.error('La petición falló:', err);
  }
}

function llenarSelectEvaluadosA(datos) {
  const select = document.getElementById('id_evaluado');
  if (!select) return;

  select.innerHTML = '<option value="">Seleccione a un usuario</option>';

  const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
  const evaluados = registros.filter(item => item.rol === 'Evaluado');

  evaluados.forEach(item => {
    const fullname = item.nombre_completo || item.cedula_usuario;

    const opcion = document.createElement('option');
    opcion.value = item.id_evaluado;
    opcion.textContent = fullname;
    select.appendChild(opcion);
  });
}

//===============================================================//

function abrirModalEditarPeriodo(idEvalAdmin, periodo, fechaInicio, fechaCierre) {
  // Resetear formulario
  document.getElementById("form-modal-editar-periodo").reset();

  // Setear valores actuales
  document.getElementById("id_eval_admin_modal").value = idEvalAdmin;
  document.getElementById("periodo_evaluado_modal").value = periodo;

  // Extraer año de las fechas actuales
  const yearInicio = new Date(fechaInicio).getFullYear();
  const yearCierre = new Date(fechaCierre).getFullYear();

  document.getElementById("fecha_inicio_modal").value = yearInicio;
  document.getElementById("fecha_cierre_modal").value = yearCierre;

  // Mostrar modal
  $("#modalEditarPeriodo").modal("show");
}

function ajustarFechasPeriodoModal(periodo) {
  const yearInicio = document.getElementById("fecha_inicio_modal").value || new Date().getFullYear();
  const yearCierre = document.getElementById("fecha_cierre_modal").value || new Date().getFullYear();

  if (periodo === "Enero-Junio") {
    // Mantener mes/día fijos, solo cambiar año
    document.getElementById("fecha_inicio_modal").value = yearInicio;
    document.getElementById("fecha_cierre_modal").value = yearCierre;
  } else if (periodo === "Julio-Diciembre") {
    document.getElementById("fecha_inicio_modal").value = yearInicio;
    document.getElementById("fecha_cierre_modal").value = yearCierre;
  }
}



//==============================================================//  
  // Lista y renderiza los objetivos disponibles para una evaluacion
async function listarObjetivosEvaluador(id_eval_admin) {
  const datos = new FormData();
  datos.append('id_eval_admin', id_eval_admin);

  const resp = await microApi('controlador/?listarObjetivosAsig', datos);
  console.log("Respuesta listarObjetivosAsig:", resp);

  if (!resp) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron cargar los objetivos' });
    return;
  }

  // Normalizar la estructura recibida
  const registros = Array.isArray(resp[0]) ? resp.flat() : resp;

  const cont = document.getElementById('contenedor-switches-objetivos');
  cont.innerHTML = '';

  registros.forEach(o => {
    cont.innerHTML += `
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox"
               id="obj_${o.id_odi}"
               ${o.acceso == 1 ? 'checked' : ''}
               onchange="toggleObjetivo(${id_eval_admin}, ${o.id_odi}, this.checked)">
        <label class="form-check-label" for="obj_${o.id_odi}">${o.nombre_objetivo}</label>
      </div>`;
  });
}

// Abre el modal de objetivos y lista los objetivos del evaluador
function abrirModalObjetivosEvaluador(id_eval_admin) {
  window.evalAdminActual = id_eval_admin;

  const modalEl = document.getElementById('modalObjetivos');
  const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
  modal.show();

  // Pintar switches al abrir
  listarObjetivosEvaluador(id_eval_admin);
}

// Activa o desactiva una relación y refresca el listado
async function toggleObjetivo(id_eval_admin, id_odi, checked) {
  const datos = new FormData();
  datos.append('id_eval_admin', id_eval_admin);
  datos.append('id_odi', id_odi);

  const servicio = checked ? 'controlador/?agregarObjetivoEval' : 'controlador/?desactivarObjetivoEval';
  const resp = await microApi(servicio, datos);

  if (!resp || resp.success === false) {
    // Revertir switch si falla
    document.getElementById(`obj_${id_odi}`).checked = !checked;
    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo actualizar el objetivo' });
  } else {
    // Refrescar objetivos desde la BD
    await listarObjetivosEvaluador(id_eval_admin);
  }
}

//==========================================================//
//Cambiar estado de la evaluacion (Iniciada, Finalizada)

async function cambiarEstadoEvalAdmin(id_eval_admin, estado_eval_admin) {
  // Determinar el estado opuesto
  const nuevoEstadoEvalAdmin = estado_eval_admin === 'Iniciada' ? 'Finalizada' : 'Iniciada';

  const result = await Swal.fire({
    title: `¿Está seguro de cambiar el estado a "${nuevoEstadoEvalAdmin}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, cambiar',
    cancelButtonText: 'Cancelar'
  });

  if (result.isConfirmed) {
    const formData = new FormData();
    formData.append('id_eval_admin', id_eval_admin);
    formData.append('estado_eval_admin', nuevoEstadoEvalAdmin); // Enviar el estado opuesto

    try {
      const resp = await microApi('controlador/?cambiar_estadoEvalAdmin', formData);
      listarEvalAdmin();

      Swal.fire({
        icon: 'success',
        title: 'Estado cambiado',
        text: typeof resp === 'string' ? resp : 'El estado fue cambiado correctamente'
      });
    } catch (err) {
      console.error("Error al cambiar estado:", err);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Ocurrió un error al cambiar el estado'
      });
    }
  }
}

//==========================================================//