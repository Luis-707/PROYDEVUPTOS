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

   
function validar_formEvaluador(opc) {

   
  // Obtener el formulario
  var formulario = document.getElementById('formulario_evaluador');
  //console.log(formulario);
  // Crear un objeto FormData
  var Data = new FormData(formulario);
  let isValid = true; // Variable para controlar si el formulario es válido
  console.log(Data);
  // Validar cada campo
  for (var [key, valor] of Data.entries()) {
      
      switch (key) {         

          /*case 'login':
              if (!validarcaracter(valor)) {
                  alert("El loguin no debe tener caracteres Especiales diferentes a ( _  .  -  )");
                  isValid = false; // Marca como inválido
              }
              break;*/
          /*case 'clave':
                  if (!validarcaracter(valor)) {
                      alert("La clave no debe tener caracteres Especiales diferentes a ( _  .  -  ) ");
                      isValid = false; // Marca como inválido
                  }
          break;*/
          /*case 'cedula_usuario':
              if (!validarnumero(valor)) {
                  alert("La cedula solo debe contener numeros ");
                  isValid = false; // Marca como inválido
              }
              break; */

          /*case 'CargoSupervisor':
              if (!validarCadena(valor)) {
                  alert("Opcion Invalida. ");
                  isValid = false; // Marca como inválido
              }
              break;*/

          /*case 'JefeSuperior':
              if (!validarCadena(valor)) {
                  alert("Opcion Invalida. ");
                  isValid = false; // Marca como inválido
              }
              break;*/
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
      
     //formulario.submit(); // Enviar el formulario
      if(opc==1)
          guardarEvaluador();  
      else
      actualizarEvaluador();
          
  }
}
async function guardarEvaluador() {
  // Capturar valores del formulario
  let datosPersona = capturarValoresFormulario('formulario_evaluador');

  // Agregar id_cargo_evaluador
  let idCargoEval = document.getElementById('id_cargo_evaluador').value;
  datosPersona.append('id_cargo_evaluador', idCargoEval);

  // Agregar id_usuario
  let idUsuarioEval = document.getElementById('id_usuario').value;
  datosPersona.append('id_usuario', idUsuarioEval);

  // Agregar id_supervisor
  let idSupervisor = document.getElementById('id_supervisor').value;
  datosPersona.append('id_supervisor', idSupervisor);

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
        valorFormEval();
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

async function listarTablaEvaluadores(datos) {
  const tbody = document.querySelector("#tabla-evaluadores tbody");
  tbody.innerHTML = "";

  // Cargar JSON con datos de empleados
  const resp = await microApi('views/js/datos_empleado.json');
  let empleados = [];

  if (Array.isArray(resp)) {
    empleados = resp[0]?.data || resp[0] || [];
  } else if (resp?.data) {
    empleados = resp.data;
  }

  // Aplanar si vienen anidados
  const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

  let html = "";

  registros.forEach(item => {
    const cedula = String(item.cedula_usuario).trim();
    const empleado = empleados.find(emp =>
      emp.pin_str === cedula || emp.pin === cedula
    );

    const fullname = empleado ? empleado.fullname : "No encontrado";
    const cargoTexto = item.cargo_evaluador || "Sin cargo";

    html += `
      <tr>
        <td>${fullname}</td>
        <td>${cedula}</td>
        <td>${cargoTexto}</td>
        <td class="acciones">
          <div class="acciones-icons">
            <img src="img/iconos/actualizar.png" onclick="abrirModalEditarCargo(${item.id_usuario}, ${item.id_cargo_evaluador})" />
            <img src="img/iconos/eliminar.png" onclick="eliminarEvaluador(${item.id_usuario})" />
          </div>
        </td>
      </tr>
    `;
  });

  tbody.innerHTML = html;
}
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

  try {
    const resp = await microApi('controlador/?a_evaluadores', datosPersona);

    if (typeof resp === 'string' && resp.includes(' No Existe')) {
      Swal.fire({
        icon: 'error',
        title: 'Evaluador no encontrado',
        text: resp
      });
    } else {
      valorFormEval();
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
  document.getElementById('id_cargo_evaluador').value = cargoeval;
  document.getElementById('id_supervisor').value = supervisoreval;
  

  
}

// Cerrar el modal al hacer clic


/*async function actualizarCargoEvaluador(){

          // antes de capturar los valores del formulario debes validarlos
      let datosPersona = capturarValoresFormulario('formulario_evaluador');

       // Obtener el valor del select 'id_cargo_evaluador'
       let idCargoEval = document.getElementById('id_cargo_evaluador').value;
       // Agregarlo a los datos que se enviarán
       datosPersona.append('id_cargo_evaluador', idCargoEval);
      
      var resp = await microApi('controlador/?actualizar_cargoevaluador',datosPersona);
           // limpia formulario
          if (resp.includes(' No Exite')) {
              alert(resp);
          }else{
              valorFormCargosEval();
              listarEvaluadores();
              alert('El Cargo se Actualizo con Exito');
              // Cerrar el modal
              const modal = document.getElementById('modalEditarEvaluador');
              modal.style.display = 'none';
          }

}*/

  //Select para usuarios con el rol de evaluador
  
  async function listarUsuariosEvaluador() {
    try {
      // Cargar JSON con datos de empleados
      const resp = await microApi('views/js/datos_empleado.json');
  
      // Obtener empleados con robustez para varias estructuras
      let empleados = [];
      if (Array.isArray(resp)) {
        empleados = resp[0]?.data || resp[0] || [];
      } else if (resp?.data) {
        empleados = resp.data;
      } else {
        empleados = resp;
      }
  
      // Obtener lista de evaluadores desde API
      const respEvaluadores = await microApi('controlador/?listar_evaluadores');
      if (typeof respEvaluadores === 'string') {
        console.error('Error al listar usuarios:', respEvaluadores);
        return;
      }
  
      llenarSelectEvaluador(respEvaluadores, empleados);
    } catch (err) {
      console.error('La petición falló:', err);
    }
  }
  
  function llenarSelectEvaluador(datos, empleados) {
    const select = document.getElementById('id_usuario');
    if (!select) return;
  
    select.innerHTML = '<option value="">Seleccione a un usuario</option>';
  
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
    const evaluadores = registros.filter(item => item.rol === 'Evaluador');
  
    evaluadores.forEach(item => {
      const empleado = empleados.find(emp => emp.pin_str === item.cedula_usuario || emp.pin === item.cedula_usuario);
      const fullname = empleado ? empleado.fullname : item.cedula_usuario;
  
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

function abrirModalEditarCargo(idUsuario, idCargoActual) {
  // 1) Resetear el formulario del modal
  document.getElementById("form-modal-editar-cargo").reset();

  // 2) Guardar el id_usuario en un campo oculto
  document.getElementById("id_usuario_modal").value = idUsuario;

  // 3) Llenar el select de cargos y marcar el actual
  listarCargosEvaluadoresModal(idCargoActual);

  // 4) Mostrar el modal
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