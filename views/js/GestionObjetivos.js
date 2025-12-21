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

     
  function validar_form_objetivos() {

     
    // Obtener el formulario
    var formulario = document.getElementById('form-modal-editar-objetivo');
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
            case 'idOdi':
                if (!validarnumero(valor)) {
                    alert("El id_odi solo debe contener numeros ");
                    isValid = false; // Marca como inválido
                }
                break;
            case 'pesoObjetivo':
                if (!validarnumero(valor)) {
                    alert("El peso solo debe contener numeros ");
                    isValid = false; // Marca como inválido
                }
                break;

            case 'nombreObjetivo':
                if (!validarCadena(valor)) {
                    alert("El nombre solo debe contener letras y espacios. ");
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
      // Obtener el valor del campo id_odi_modal
      var idObjetivo = document.getElementById("id_odi_modal").value;
  
      // Comprobar si el campo está vacío
      if (idObjetivo.trim() === '') {
        guardarObjetivo(); // Llamar a guardarObjetivo si está vacío
      } else {
        actualizarObjetivo(); // Llamar a actualizarObjetivo si no está vacío
      }
    }
}

//Guargar objetivo

async function guardarObjetivo(){

// antes de capturar los valores del formulario debes validarlos
let datosPersona = capturarValoresFormulario('form-modal-editar-objetivo');

try {
  // Llamada al servicio
  const resp = await microApi('controlador/?g_objetivo', datosPersona);

  if (!resp.success) {
    Swal.fire({
        icon: 'error',
        title: 'Error al guardar',
        text: resp.message
    });
} else {
  valorFormObjetivo();
  listarObjetivos();
    Swal.fire({
        icon: 'success',
        title: 'Objetivo guardado con exito',
        text: resp.message
    });
}
} catch (err) {
console.error("Error en guardarSupervisor:", err);
Swal.fire({
    icon: 'error',
    title: 'Error inesperado',
    text: 'Ocurrió un error al guardar el objetivo'
});
}

  
}

//Cosultar lista de objetivos con nombre_objetivo y peso_objetivo

async function listarObjetivos() {
  // Llama a la API o al backend para obtener los objetivos
  const resp = await microApi('controlador/?l_odi');
  listarTablaObjetivos(resp);
}

//Buscar objetivo por nombre_objetivo

async function buscarObjetivo(cod){
    
    let dato = capturarValoresFormulario('formulario_objetivo',cod);

    var resp = await microApi('controlador/?b_objetivo',dato);
   
  return resp;
   
}

//===========================================================//
//Listar objetivos

async function listarTablaObjetivos(datos) {
  const tbody = document.querySelector("#tabla-odi tbody");
  tbody.innerHTML = "";

  // Aplanar si vienen anidados
  const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

  let html = "";

  registros.forEach(item => {
    html += `
      <tr>
        <td>${item.nombre_objetivo}</td>
        <td>${item.peso_objetivo}</td>
        <td>
          <button class="btn btn-sm btn-primary"
            onclick="abrirModalObjetivo(${item.id_odi}, '${item.nombre_objetivo}', ${item.peso_objetivo})">
            Editar
          </button>
        </td>
      </tr>
    `;
  });

  tbody.innerHTML = html;
}

//=============================================================//

//Validar datos del formulario

function valorFormObjetivo(nObj='',peso=''){ 
    document.getElementById('nombre_objetivo_modal').value = nObj;
    document.getElementById('peso_objetivo_modal').value = peso;
}

function valorFormObjetivoModal(idOdi = '', nObjM = '', pesoM = '') {
  const idInput = document.getElementById('id_odi_modal');
  const nombreInput = document.getElementById('nombre_objetivo_modal');
  const pesoInput = document.getElementById('peso_objetivo_modal');

  if (idInput && nombreInput && pesoInput) {
    idInput.value = idOdi;
    nombreInput.value = nObjM;
    pesoInput.value = pesoM;
  } else {
    console.warn('Algunos elementos del formulario modal no se encontraron en el DOM');
  }
}

//Abrir modal con el formulario para los objetivos
function abrirModalObjetivo(id_odi = '', nombre_objetivo = '', peso_objetivo = '') {
  // Resetear formulario si es necesario
  const form = document.getElementById("form-modal-editar-objetivo");
  form.reset();

  // Rellenar campos con datos de la fila o dejar vacíos
  document.getElementById("id_odi_modal").value = id_odi || '';
  document.getElementById("nombre_objetivo_modal").value = nombre_objetivo || '';
  document.getElementById("peso_objetivo_modal").value = peso_objetivo || '';

  // Mostrar el modal (Bootstrap 5)
  const modal = new bootstrap.Modal(document.getElementById('modalEditarObjetivo'));
  modal.show();
}


//Actualizar objetivos

async function actualizarObjetivo(id_odi, nombre_objetivo, peso_objetivo) {
  // Capturar los valores del formulario del modal
  //let datosObjetivo = new FormData(document.getElementById('form-modal-editar-objetivo'));
  let datosObjetivo = capturarValoresFormulario('form-modal-editar-objetivo', id_odi, nombre_objetivo, peso_objetivo);

  try {
    // Enviar datos al backend para actualización
    const resp = await microApi('controlador/?a_objetivo', datosObjetivo);

    // Manejo de respuesta según contenido (ajustar mensaje según backend)
    if (typeof resp === 'string' && resp.includes('No Existe')) {
      Swal.fire({
        icon: 'error',
        title: 'Objetivo no encontrado',
        text: resp
      });
    } else {
      //valorFormObjetivoModal();
      listarObjetivos();
      Swal.fire({
        icon: 'success',
        title: 'Objetivo actualizado',
        text: 'El objetivo se actualizó correctamente'
      });

      // Cerrar modal manualmente (Bootstrap 5)
      const modalElement = document.getElementById('modalEditarObjetivo');
      const modalInstance = bootstrap.Modal.getInstance(modalElement);
      modalInstance.hide();
    }
  } catch (err) {
    console.error("Error actualizando objetivo:", err);
    Swal.fire({
      icon: 'error',
      title: 'Error inesperado',
      text: 'Ocurrió un error al actualizar el objetivo'
    });
  }
}

