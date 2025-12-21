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

   
function validar_formEvaluador() {

   
  // Obtener el formulario
  var formulario = document.getElementById('form-modal-editar-cargo');
  //console.log(formulario);
  // Crear un objeto FormData
  var Data = new FormData(formulario);
  let isValid = true; // Variable para controlar si el formulario es válido
  console.log(Data);
  // Validar cada campo
  for (var [key, valor] of Data.entries()) {
      
      switch (key) {         

          case 'RolSistema':
              if (!validarCadena(valor)) {
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
    // Obtener el valor del campo id_competencia_modal
    var idUsuarioModal = document.getElementById("id_usuario_modal").value;

    // Comprobar si el campo está vacío
    if (idUsuarioModal.trim() === '') {
      guardarEvaluador(); // Llamar a guardarEvaluador si está vacío
    } else {
      actualizarEvaluador(); // Llamar a actualizarEvaluador si no está vacío
    }
  }
}
async function guardarEvaluador() {
  // Capturar valores del formulario
  let datosPersona = capturarValoresFormulario('form-modal-editar-cargo');

  // Agregar id_cargo_evaluador
  /*let idCargoEval = document.getElementById('id_cargo_evaluador').value;
  datosPersona.append('id_cargo_evaluador', idCargoEval);

  // Agregar id_usuario
  let idUsuarioEval = document.getElementById('id_usuario').value;
  datosPersona.append('id_usuario', idUsuarioEval);

  // Agregar id_supervisor
  let idSupervisor = document.getElementById('id_supervisor').value;
  datosPersona.append('id_supervisor', idSupervisor);
*/
  try {
      // Llamada al servicio
      const resp = await microApi('controlador/?g_cargoevaluador', datosPersona);

      if (!resp.success) {
        Swal.fire({
            icon: 'error',
            title: 'Error al guardar',
            text: resp.message
        });
    } else {
        //valorFormEval();
        listarEvaluadores();
        Swal.fire({
            icon: 'success',
            title: 'Añadir Evaluador y Cargo de Evaluador',
            text: resp.message
        });
    }
} catch (err) {
    console.error("Error en guardarEvaluador:", err);
    Swal.fire({
        icon: 'error',
        title: 'Error inesperado',
        text: 'Ocurrió un error al guardar el evaluador'
    });
}
}

/*async function listarCargosEval(){
  var resp = await microApi('controlador/?l_cargos_evaluadores');
  listarTablaEvaluadores(resp);
}*/

async function listarEvaluadores(){
  var resp = await microApi('controlador/?l_evaluadores');
  listarTablaEvaluadores(resp);
}

async function buscarEvaluador(cod){
    
  let dato = capturarValoresFormulario('formulario_evaluador',cod);

  var resp = await microApi('controlador/?b_evaluador',dato);
 
return resp;
 
}

//=============================================================//
//Funcion para crear las filas de la tabla

async function listarTablaEvaluadores(datos) {
  const tbody = document.querySelector("#tabla-evaluadores tbody");
  tbody.innerHTML = "";

  // Aplanar si vienen anidados
  const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

  let html = "";

  registros.forEach(item => {
      const cedula = String(item.cedula_usuario).trim();
      const fullname = item.nombre_completo || "No encontrado";
      const ubicacion = item.ubicacion_administrativa || "Sin ubicación";
      const cargoTexto = item.cargo_evaluador || "Sin cargo";

      html += `
          <tr>
              <td>${cedula}</td>
              <td>${fullname}</td>
              <td>${ubicacion}</td>
              <td>${cargoTexto}</td>
              <td>
                  <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                          <i class="icon-base bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                          <a class="dropdown-item" href="javascript:void(0);" 
                             onclick="abrirModalEditarCargo('tabla', ${item.id_usuario}, ${item.id_cargo_evaluador})">
                              <i class="icon-base bx bx-edit-alt me-1"></i>Editar
                          </a>
                      </div>
                  </div>
              </td>
          </tr>
      `;
  });

  tbody.innerHTML = html;
}


//=============================================================//

async function eliminarEvaluador(idUsuario) {
  const result = await Swal.fire({
    title: '¿Está seguro de eliminar este evaluador?',
    text: 'Esta acción no se puede deshacer',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  });

  if (result.isConfirmed) {
    const formData = new FormData();
    formData.append('id_usuario', idUsuario);

    try {
      const resp = await microApi('controlador/?e_evaluadores', formData);
      listarEvaluadores(); // refresca la tabla

      Swal.fire({
        icon: 'success',
        title: 'Evaluador eliminado',
        text: typeof resp === 'string' ? resp : 'El evaluador fue eliminado correctamente'
      });
    } catch (err) {
      console.error("Error eliminando evaluador:", err);
      Swal.fire({
        icon: 'error',
        title: 'Error al eliminar',
        text: 'Ocurrió un error al eliminar el evaluador'
      });
    }
  }
}

async function actualizarEvaluador() {
  let datosPersona = capturarValoresFormulario('form-modal-editar-cargo');
  
  // Agregar id_usuario
  let idUsuarioEval = document.getElementById('id_usuario_modal').value;
  datosPersona.append('id_usuario', idUsuarioEval);

  try {
    const resp = await microApi('controlador/?a_evaluadores', datosPersona);

    if (typeof resp === 'string' && resp.includes(' No Existe')) {
      Swal.fire({
        icon: 'error',
        title: 'Evaluador no encontrado',
        text: resp
      });
    } else {
      //valorFormEval();
      listarEvaluadores();

      Swal.fire({
        icon: 'success',
        title: 'Cargo actualizado',
        text: 'El cargo del evaluador se actualizó con éxito'
      });
    }
  } catch (err) {
    console.error("Error actualizando evaluador:", err);
    Swal.fire({
      icon: 'error',
      title: 'Error inesperado',
      text: 'Ocurrió un error al actualizar el evaluador'
    });
  }
}
function valorFormEval(usuarioevaluador='',cargoeval='',supervisoreval=''){
  // Asignar valores a los campos del formulario
  document.getElementById('id_usuario').value = usuarioevaluador;
  document.getElementById('cargo_modal').value = cargoeval;
  document.getElementById('id_supervisor').value = supervisoreval;
  

  
}

// Cerrar el modal al hacer clic

  //Select para usuarios con el rol de evaluador

  async function listarUsuariosEvaluador() {
    try {
        // Obtener lista de evaluadores desde API
        const respEvaluadores = await microApi('controlador/?listar_evaluadores');
        if (typeof respEvaluadores === 'string') {
            console.error('Error al listar usuarios:', respEvaluadores);
            return;
        }

        llenarSelectEvaluador(respEvaluadores);
    } catch (err) {
        console.error('La petición falló:', err);
    }
}

function llenarSelectEvaluador(datos) {
    const select = document.getElementById('id_usuario_select');
    if (!select) return;

    select.innerHTML = '<option value="">Seleccione a un usuario</option>';

    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
    const evaluadores = registros.filter(item => item.rol === 'Evaluador');

    evaluadores.forEach(item => {
        const fullname = item.nombre_completo || item.cedula_usuario;

        const opcion = document.createElement('option');
        opcion.value = item.id_usuario;
        opcion.textContent = fullname;

        select.appendChild(opcion);
    });
}

  
//Select de cargos de evaluadores

async function listarCargosEvaluadores() {
  try {
    // Llamada a la API que devuelve cargos evaluadores
    const resp = await microApi('controlador/?l_cargos_evaluadores');

    if (typeof resp === 'string') {
      console.error('Error al listar cargos evaluadores:', resp);
      return;
    }

    llenarSelectCargosEvaluadores(resp);
  } catch (err) {
    console.error('La petición de cargos evaluadores falló:', err);
  }
}

function llenarSelectCargosEvaluadores(datos) {
  const select = document.getElementById('id_cargo_evaluador');
  if (!select) return;

  // Opción por defecto
  select.innerHTML = '<option value="">Seleccione a un cargo</option>';

  // Para manejar arrays anidados si los hubiera
  const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

  // Se asume que datos son objetos con id_cargo_evaluador y cargo_evaluador
  registros.forEach(item => {
    const opcion = document.createElement('option');
    opcion.value = item.id_cargo_evaluador;   // id para cada option
    opcion.textContent = item.cargo_evaluador; // texto visible en el select

    select.appendChild(opcion);
  });
}

//Abrir modal con formulario (habra campos que solo apareceran dependiendo desde donde se llame a la funcion)
function abrirModalEditarCargo(origen, idUsuario, idCargoActual) {
  // Resetear el formulario
  document.getElementById("form-modal-editar-cargo").reset();

  // Mostrar u ocultar campos según el origen
  if (origen === 'boton') {
    document.getElementById('div_id_usuario').style.display = 'block';
    document.getElementById('div_id_supervisor').style.display = 'block';
  } else {
    document.getElementById('div_id_usuario').style.display = 'none';
    document.getElementById('div_id_supervisor').style.display = 'none';
  }

  // Asignar el valor recibido al campo oculto
  if (idUsuario) {
    document.getElementById("id_usuario_modal").value = idUsuario;
  } else {
    document.getElementById("id_usuario_modal").value = '';
  }

  // Llenar el select de cargos y marcar el actual
  listarCargosEvaluadoresModal(idCargoActual);

  // Mostrar el modal
  $("#modalEditarCargo").modal("show");
}




function rellenarSelectCargos(datos, idSelect, idCargoActual = null) {
  const sel = document.getElementById(idSelect);

  // Limpia y agrega la opción por defecto
  sel.innerHTML = '<option value="">-- Seleccione --</option>';

  // Aplana si es un array de arrays
  const flat = Array.isArray(datos[0]) ? datos.flat() : datos;

  // Recorre y agrega opciones
  flat.forEach(o => {
    const opt = document.createElement('option');
    opt.value = o.id_cargo_evaluador;   
    opt.textContent = o.cargo_evaluador; 
    if (idCargoActual && o.id_cargo_evaluador == idCargoActual) {
      opt.selected = true;
    }
    sel.appendChild(opt);
  });
}


function listarCargosEvaluadoresModal(idCargoActual) {
  return microApi('controlador/?l_cargos_evaluadores')
    .then(datos => rellenarSelectCargos(datos, 'cargo_modal', idCargoActual));
}

//Select para mostrar supevisores de los evaluadores

async function listarSupervisoresCargos() {
  try {
    // Llamar a la API para obtener supervisores con cargos
    const resp = await microApi('controlador/?mostrar_supervisor');

    if (typeof resp === 'string') {
      console.error('Error al listar supervisores:', resp);
      return;
    }

    llenarSelectSupervisores(resp);
  } catch (err) {
    console.error('La petición de supervisores falló:', err);
  }
}

function llenarSelectSupervisores(datos) {
  const select = document.getElementById('id_supervisor');
  if (!select) return;

  // Opción por defecto
  select.innerHTML = '<option value="">Seleccione un supervisor</option>';

  // Manejar arrays anidados si los hubiera
  const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

  // Se asume que datos son objetos con id_supervisor y cargo_supervisor
  registros.forEach(item => {
    const opcion = document.createElement('option');
    opcion.value = item.id_supervisor;    // valor del option
    opcion.textContent = item.cargo_supervisor; // texto visible en el select
    select.appendChild(opcion);
  });
}