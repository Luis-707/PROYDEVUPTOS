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
  
  function validar_form_indicador() {
    var formulario = document.getElementById('form-modal-Indicador');
    var Data = new FormData(formulario);
    let isValid = true;

    for (var [key, valor] of Data.entries()) {
        switch (key) {
            case 'indicadorid':
                if (!validarnumero(valor)) {
                    alert("El id solo debe contener números");
                    isValid = false;
                }
                break;
            case 'indicador':
                if (!valor.trim()) {
                    alert("El nombre del indicador es obligatorio");
                    isValid = false;
                } else if (!validarCadena(valor)) {
                    alert("El nombre solo debe contener letras y espacios");
                    isValid = false;
                }
                break;
        }
    }

    // MOVER FUERA del switch
    if (!isValid) return; // ← AQUÍ

    // Resto del código igual...
    var indicadorid = document.getElementById("indicador_id_modal").value;
    if (indicadorid.trim() === '') {
        guardarIndicador();
    } else {
        actualizarIndicador(indicadorid, document.getElementById("indicador_modal").value);
    }
}
  /*function validar_form_indicador() {
    // Obtener el formulario
    var formulario = document.getElementById('form-modal-Indicador');
    // Crear un objeto FormData
    var Data = new FormData(formulario);
    let isValid = true; // Variable para controlar si el formulario es válido
  
    // Validar cada campo
    for (var [key, valor] of Data.entries()) {
      switch (key) {
        case 'indicadorid':
          if (!validarnumero(valor)) {
            alert("El id_odi solo debe contener numeros ");
            isValid = false;
          }
          break;
        case 'indicador':
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
      // Obtener el valor del campo indicador_id_modal
      var indicadorid = document.getElementById("indicador_id_modal").value;
  
      // Comprobar si el campo está vacío
      if (indicadorid.trim() === '') {
        guardarIndicador(); // Llamar a guardarIndicador si está vacío
      } else {
        actualizarIndicador(indicadorid, document.getElementById("indicador_modal").value); // Llamar a actualizarIndicador si no está vacío
      }
    }
  }*/

  //Consultar lista de indicadores

  async function listarIndicadores() {
    // Llama a la API o al backend para obtener los indicadores
    const resp = await microApi('controlador/?l_indic');
    listarTablaIndicadores(resp);
  }

  //Validar datos del formulario

  function valorFormIndicador(nIndic =''){ 
    document.getElementById('indicador_modal').value = nIndic;
  }

  //Guardar indicador
/*
  async function guardarIndicador(){

    // antes de capturar los valores del formulario debes validarlos
    let datosIndicador = capturarValoresFormulario('form-modal-Indicador');
    
    try {
    // Llamada al servicio
    const resp = await microApi('controlador/?g_indicador', datosIndicador);
    
    if (!resp.success) {
      Swal.fire({
          icon: 'error',
          title: 'Error al guardar',
          text: resp.message
      });
    } else {
    valorFormIndicador();
    listarIndicadores();
      Swal.fire({
          icon: 'success',
          title: 'Indicador guardado con exito',
          text: resp.message
      });
    }
    } catch (err) {
    console.error("Error en indicador:", err);
    Swal.fire({
      icon: 'error',
      title: 'Error inesperado',
      text: 'Ocurrió un error al guardar indicador'
    });
    } 
  }*/

  // 3. CORREGIR guardarIndicador()
async function guardarIndicador() {
  let datosIndicador = capturarValoresFormulario('form-modal-Indicador'); // ✅ Ahora existe
  
  try {
      const resp = await microApi('controlador/?g_indicador', datosIndicador);
      
      // ✅ CORREGIDO: resp.success EXISTE
      if (resp.success) { // ← CAMBIAR ESTE ORDEN
          valorFormIndicador();
          listarIndicadores();
          Swal.fire({
              icon: 'success',
              title: 'Indicador guardado con éxito',
              text: resp.message
          });
      } else {
          Swal.fire({
              icon: 'error',
              title: 'Error al guardar',
              text: resp.message
          });
      }
  } catch (err) {
      console.error("Error en indicador:", err);
      Swal.fire({
          icon: 'error',
          title: 'Error inesperado',
          text: 'Ocurrió un error al guardar indicador'
      });
  }
}

//==================================================================//
   // Listar indicadores en tabla DataTables
 
async function listarTablaIndicadores(datos) {
  const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
  
  // Destruir DataTable existente si existe
  if ($.fn.DataTable.isDataTable('#tabla-indic')) {
      $('#tabla-indic').DataTable().destroy();
  }
  
  // Limpiar tbody
  $('#tabla-indic tbody').empty();
  
  // Preparar datos para DataTables
  const tableData = registros.map(item => {
      // Botones de acción con opciones específicas de indicadores
      const acciones = `
          <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="icon-base bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);" onclick="abrirModalIndicador(${item.indicador_id}, '${item.indicador}')">
                      <i class="icon-base bx bx-edit me-2"></i>Editar
                  </a>
                  <a class="dropdown-item" href="javascript:void(0);" onclick="cambiarEstadoIndic(${item.indicador_id}, '${item.estado_indicador}')">
                      <i class="icon-base bx bx-toggle-right me-1"></i>Cambiar estado
                  </a>
              </div>
          </div>
      `;
      
      return [
          item.indicador,
          item.estado_indicador,
          acciones
      ];
  });
  
  // Inicializar DataTable
  $('#tabla-indic').DataTable({
      data: tableData,
      columns: [
          { title: "Indicador" },
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
      order: [[0, 'asc']], // Ordenar por nombre de indicador por defecto
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
    
//=================================================================//
   
  //Actualizar competencias
  async function actualizarIndicador(indicador_id, indicador) {
  // Capturar los valores del formulario del modal
  let datosInd = capturarValoresFormulario('form-modal-Indicador', indicador_id, indicador);
  
    try {
      // Enviar datos al backend para actualización
      const resp = await microApi('controlador/?a_indicador', datosInd);
  
      // Manejo de respuesta según contenido (ajustar mensaje según backend)
      if (typeof resp === 'string' && resp.includes('No Existe')) {
        Swal.fire({
          icon: 'error',
          title: 'Indicador no encontrado',
          text: resp
        });
      } else {
        listarIndicadores();
        Swal.fire({
          icon: 'success',
          title: 'Indicador actualizado',
          text: 'El indicador se actualizó correctamente'
        });
  
        // Cerrar modal manualmente (Bootstrap 5)
        const modalElement = document.getElementById('modalIndicador');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        modalInstance.hide();
      }
    } catch (err) {
      console.error("Error actualizando indicador:", err);
      Swal.fire({
        icon: 'error',
        title: 'Error inesperado',
        text: 'Ocurrió un error al actualizar el indicador'
      });
    }
  }

  //Cambiar el estado del indicador

  async function cambiarEstadoIndic(indicador_id, estado_indicador) {
    // Determinar el estado opuesto
    const nuevoEstadoIndicador = estado_indicador === 'Activo' ? 'Inactivo' : 'Activo';
  
    const result = await Swal.fire({
      title: `¿Está seguro de cambiar el estado a "${nuevoEstadoIndicador}"?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, cambiar',
      cancelButtonText: 'Cancelar'
    });
  
    if (result.isConfirmed) {
      const formData = new FormData();
      formData.append('indicador_id', indicador_id);
      formData.append('estado_indicador', nuevoEstadoIndicador);
      try {
        const resp = await microApi('controlador/?cambiar_estadoIndic', formData);
        listarIndicadores();
  
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

  // Función para abrir el modal indicador y rellenar el formulario con datos de la fila
function abrirModalIndicador(indicador_id = '', indicador = '') {
  // Resetear formulario para asegurar que los campos estén vacíos
  const form = document.getElementById("form-modal-Indicador");
  form.reset();

  // Asignar los valores, pero si son undefined, asignar cadena vacía
  document.getElementById("indicador_id_modal").value = indicador_id || '';
  document.getElementById("indicador_modal").value = indicador || '';

  // Cambiar el título según si hay ID
  const tituloModal = document.querySelector("#modalIndicador .modal-title");
  if (indicador_id) {
    tituloModal.textContent = "Editar indicador";
  } else {
    tituloModal.textContent = "Nuevo indicador";
  }

  // Mostrar el modal
  const modal = new bootstrap.Modal(document.getElementById('modalIndicador'));
  modal.show();
}
