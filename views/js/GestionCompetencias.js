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
function validarcorreo(cadena){
  var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  return regex.test(cadena);
}

   
function validar_form_competencia(opc) {

   
  // Obtener el formulario
  var formulario = document.getElementById('formulario_competencia');
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
          case 'idCompetencia':
              if (!validarnumero(valor)) {
                  alert("El id_odi solo debe contener numeros ");
                  isValid = false; // Marca como inválido
              }
              break;
          case 'pesoCompetencia':
              if (!validarnumero(valor)) {
                  alert("El peso solo debe contener numeros ");
                  isValid = false; // Marca como inválido
              }
              break;

          case 'nombreCompetencia':
              if (!validarCadena(valor)) {
                  alert("El nombre solo debe contener letras y espacios. ");
                  isValid = false; // Marca como inválido
              }
              break;

         /* case 'apellidos':
              if (!validarCadena(valor)) {
                  alert("El apellido solo debe contener letras y espacios. ");
                  isValid = false; // Marca como inválido
              }
              break;
             case 'correo':
                  if (!validarcorreo(valor)) {
                      alert("El Correo no es valido");
                      isValid = false; // Marca como inválido
                  }
              break;*/
           

               

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
          guardarCompetencia();  
      else
          actualizarCompetencia();
  }
}

//Guargar objetivo

async function guardarCompetencia(){

// antes de capturar los valores del formulario debes validarlos
let datosPersona = capturarValoresFormulario('formulario_competencia');

try {
// Llamada al servicio
const resp = await microApi('controlador/?g_competencia', datosPersona);

if (!resp.success) {
  Swal.fire({
      icon: 'error',
      title: 'Error al guardar',
      text: resp.message
  });
} else {
valorFormCompetencia();
listarGCompetencias();
  Swal.fire({
      icon: 'success',
      title: 'Competencia guardada con exito',
      text: resp.message
  });
}
} catch (err) {
console.error("Error en competencia:", err);
Swal.fire({
  icon: 'error',
  title: 'Error inesperado',
  text: 'Ocurrió un error al guardar competencia'
});
}

}

//Cosultar lista de competencias con nombre_competencia, peso_competencia y estado_competencia

async function listarGCompetencias() {
    // Llama a la API o al backend para obtener los objetivos
    const resp = await microApi('controlador/?l_gestionCompetencias');
    listarTablaCompetencias(resp);
  }
  
  //Buscar objetivo por nombre_objetivo
  
  /*async function buscarObjetivo(cod){
      
      let dato = capturarValoresFormulario('formulario_objetivo',cod);
  
      var resp = await microApi('controlador/?b_objetivo',dato);
     
    return resp;
     
  }*/
  
  //===========================================================//
  //Listar competencias
  
  async function listarTablaCompetencias(datos) {
    const tbody = document.querySelector("#tabla-comp tbody");
    tbody.innerHTML = "";
  
    // Aplanar si vienen anidados
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
  
    let html = "";
  
    registros.forEach(item => {
      html += `
        <tr>
          <td>${item.nombre_competencia}</td>
          <td>${item.peso_competencia}</td>
          <td>${item.estado_competencia}</td>
          <td>
          <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="javascript:void(0);" onclick="cambiarEstadoComp(${item.id_competencia}, 'Activo')"><i class="icon-base bx bx-check-circle me-2"></i>Activo</a>
              <a class="dropdown-item" href="javascript:void(0);" onclick="cambiarEstadoComp(${item.id_competencia}, 'Inactivo')"><i class="icon-base bx bx-x-circle me-2"></i>Inactivo</a>
              <a class="dropdown-item" href="javascript:void(0);" onclick="abrirModalEditarCompetencia(${item.id_competencia}, '${item.nombre_competencia}', ${item.peso_competencia})"><i class="icon-base bx bx-edit me-2"></i>Editar</a>
            </div>
          </div>
        </td>
        </tr>
      `;
    });
  
    tbody.innerHTML = html;
  }
  
  //=============================================================//
  
  //Validar datos del formulario

function valorFormCompetencia(nComp='',peso='',estadoComp=''){ 
  document.getElementById('nombre_competencia').value = nComp;
  document.getElementById('peso_competencia').value = peso;
  document.getElementById('estado_competencia').value = estadoComp;
}

//Cambiar el estado de la competencia

async function cambiarEstadoComp(idCompetencia, estado_competencia) {
  const result = await Swal.fire({
    title: `¿Está seguro de cambiar el estado a "${estado_competencia}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, cambiar',
    cancelButtonText: 'Cancelar'
  });

  if (result.isConfirmed) {
    const formData = new FormData();
    formData.append('id_competencia', idCompetencia);
    formData.append('estado_competencia', estado_competencia);

    try {
      const resp = await microApi('controlador/?cambiarEstadoComp', formData);
      listarGCompetencias();
      // Refrescar la tabla si tienes la función lista
      // listarTablaCompetencias();

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

// Función para abrir el modal y rellenar el formulario con datos de la fila
function abrirModalEditarCompetencia(id_competencia, nombre_competencia, peso_competencia) {
  // Resetear formulario si es necesario
  const form = document.getElementById("form-modal-editar-competencia");
  form.reset();

  // Rellenar campos con datos de la fila
  document.getElementById("id_competencia_modal").value = id_competencia;
  document.getElementById("nombre_competencia_modal").value = nombre_competencia;
  document.getElementById("peso_competencia_modal").value = peso_competencia;

  // Mostrar el modal (Bootstrap 5)
  const modal = new bootstrap.Modal(document.getElementById('modalEditarCompetencia'));
  modal.show();
}

//Actualizar competencias
async function actualizarCompetencia(id_competencia, nombre_competencia, peso_competencia) {
  // Capturar los valores del formulario del modal
  //let datosObjetivo = new FormData(document.getElementById('form-modal-editar-objetivo'));
  let datosCompetencia = capturarValoresFormulario('form-modal-editar-competencia', id_competencia, nombre_competencia, peso_competencia);

  try {
    // Enviar datos al backend para actualización
    const resp = await microApi('controlador/?a_competencia', datosCompetencia);

    // Manejo de respuesta según contenido (ajustar mensaje según backend)
    if (typeof resp === 'string' && resp.includes('No Existe')) {
      Swal.fire({
        icon: 'error',
        title: 'Competencia no encontrada',
        text: resp
      });
    } else {
      //valorFormObjetivoModal();
      listarGCompetencias();
      Swal.fire({
        icon: 'success',
        title: 'Competencia actualizada',
        text: 'La competencia se actualizó correctamente'
      });

      // Cerrar modal manualmente (Bootstrap 5)
      const modalElement = document.getElementById('modalEditarCompetencia');
      const modalInstance = bootstrap.Modal.getInstance(modalElement);
      modalInstance.hide();
    }
  } catch (err) {
    console.error("Error actualizando competencia:", err);
    Swal.fire({
      icon: 'error',
      title: 'Error inesperado',
      text: 'Ocurrió un error al actualizar el objetivo'
    });
  }
}