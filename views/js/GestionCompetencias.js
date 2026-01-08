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

   
function validar_form_competencia() {
  // Obtener el formulario
  var formulario = document.getElementById('form-modal-editar-competencia');
  // Crear un objeto FormData
  var Data = new FormData(formulario);
  let isValid = true; // Variable para controlar si el formulario es válido

  // Validar cada campo
  for (var [key, valor] of Data.entries()) {
    switch (key) {
      case 'idCompetencia':
        if (!validarnumero(valor)) {
          alert("El id_odi solo debe contener numeros ");
          isValid = false;
        }
        break;
      case 'pesoCompetencia':
        if (!validarnumero(valor)) {
          alert("El peso solo debe contener numeros ");
          isValid = false;
        }
        break;
      case 'nombreCompetencia':
        if (!validarCadena(valor)) {
          alert("El nombre solo debe contener letras y espacios. ");
          isValid = false;
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
    var idCompetencia = document.getElementById("id_competencia_modal").value;

    // Comprobar si el campo está vacío
    if (idCompetencia.trim() === '') {
      guardarCompetencia(); // Llamar a guardarCompetencia si está vacío
    } else {
      actualizarCompetencia(); // Llamar a actualizarCompetencia si no está vacío
    }
  }
}


//Guargar objetivo

async function guardarCompetencia(){

// antes de capturar los valores del formulario debes validarlos
let datosPersona = capturarValoresFormulario('form-modal-editar-competencia');

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
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
    
    // Destruir DataTable existente si existe
    if ($.fn.DataTable.isDataTable('#tabla-comp')) {
        $('#tabla-comp').DataTable().destroy();
    }
    
    // Limpiar tbody
    $('#tabla-comp tbody').empty();
    
    // Preparar datos para DataTables
    const tableData = registros.map(item => {
        // Botones de acción con opciones específicas de competencias
        const acciones = `
            <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);" onclick="cambiarEstadoComp(${item.id_competencia}, 'Activo')">
                        <i class="icon-base bx bx-check-circle me-2"></i>Activo
                    </a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="cambiarEstadoComp(${item.id_competencia}, 'Inactivo')">
                        <i class="icon-base bx bx-x-circle me-2"></i>Inactivo
                    </a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="abrirModalCompetencia(${item.id_competencia}, '${item.nombre_competencia}', ${item.peso_competencia})">
                        <i class="icon-base bx bx-edit me-2"></i>Editar
                    </a>
                </div>
            </div>
        `;
        
        return [
            item.nombre_competencia,
            item.peso_competencia,
            item.estado_competencia,
            acciones
        ];
    });
    
    // Inicializar DataTable
    $('#tabla-comp').DataTable({
        data: tableData,
        columns: [
            { title: "Competencia" },
            { title: "Peso", width: "100px" },
            { title: "Estado", width: "120px" },
            { 
                title: "Acciones", 
                width: "140px",
                orderable: false,
                searchable: false
            }
        ],
        pageLength: 25,
        responsive: true,
        order: [[0, 'asc']], // Ordenar por nombre de competencia por defecto
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
  
  //=============================================================//
  
  //Validar datos del formulario

function valorFormCompetencia(nComp='',peso=''){ 
  document.getElementById('nombre_competencia_modal').value = nComp;
  document.getElementById('peso_competencia_modal').value = peso;
  //document.getElementById('estado_competencia').value = estadoComp;
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
function abrirModalCompetencia(id_competencia = '', nombre_competencia = '', peso_competencia = '') {
  // Resetear formulario para asegurar que los campos estén vacíos
  const form = document.getElementById("form-modal-editar-competencia");
  form.reset();

  // Asignar los valores, pero si son undefined, asignar cadena vacía
  document.getElementById("id_competencia_modal").value = id_competencia || '';
  document.getElementById("nombre_competencia_modal").value = nombre_competencia || '';
  document.getElementById("peso_competencia_modal").value = peso_competencia || '';

  // Mostrar el modal
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