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
  
     
  function validar_formSupervisor() {
  
     
    // Obtener el formulario
    var formulario = document.getElementById('form-modal-cargo-superv');
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
    // Obtener el valor del campo id_usuario_modal
    var idUsuarioModal = document.getElementById("id_usuario_modal").value;

    // Comprobar si el campo está vacío
    if (idUsuarioModal.trim() === '') {
      guardarSupervisor(); // Llamar a guardarSupervisor si está vacío
    } else {
      actualizarSupervisor(); // Llamar a actualizarSupervisor si no está vacío
    }
  }
  }
  async function guardarSupervisor() {
    // Capturar valores del formulario
    let datosPersona = capturarValoresFormulario('form-modal-cargo-superv');

    // Agregar id_cargo_evaluador
    /*let idCargoEvalSuperv = document.getElementById('id_cargo_supervisor').value;
    datosPersona.append('id_cargo_evaluador', idCargoEvalSuperv);

    // Agregar id_usuario
    let idUsuarioEvalSuperv = document.getElementById('id_usuario').value;
    datosPersona.append('id_usuario', idUsuarioEvalSuperv);*/

    try {
      // Llamada al servicio
      const resp = await microApi('controlador/?g_cargosupervisor', datosPersona);

      if (!resp.success) {
        Swal.fire({
            icon: 'error',
            title: 'Error al guardar',
            text: resp.message
        });
    } else {
        //valorFormSuperv();
        listarSupervisores();
        Swal.fire({
            icon: 'success',
            title: 'Añadir Cargo de Supervisor',
            text: resp.message
        });
    }
} catch (err) {
    console.error("Error en guardarSupervisor:", err);
    Swal.fire({
        icon: 'error',
        title: 'Error inesperado',
        text: 'Ocurrió un error al guardar el supervisor'
    });
}
}
    
  async function listarSupervisores(){
    var resp = await microApi('controlador/?l_supervisores');
    listarTablaSupervisores(resp);
  }
  
  async function buscarSupervisor(cod){
      
    let dato = capturarValoresFormulario('form-modal-cargo-superv',cod);
  
    var resp = await microApi('controlador/?b_supervisor',dato);
   
  return resp;
   
  }
  
//============================================================//
//Funcion para crear las filas de la tabla

  async function listarTablaSupervisores(datos) {
    const tbody = document.querySelector("#tabla-supervisores tbody");
    tbody.innerHTML = "";

    // Aplanar si vienen anidados
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

    let html = "";

    registros.forEach(item => {
        const cedula = String(item.cedula_usuario).trim();
        const fullname = item.nombre_completo || "No encontrado";
        const ubicacion = item.ubicacion_administrativa || "Sin ubicación";
        const cargoTexto = item.cargo_supervisor || "Sin cargo";

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
                               onclick="abrirModalCargoSuperv('tabla', ${item.id_usuario}, ${item.id_cargo_supervisor})">
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

//============================================================//

  async function eliminarSupervisor(idUsuario) {
    const result = await Swal.fire({
      title: '¿Está seguro de eliminar este supervisor?',
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
        const resp = await microApi('controlador/?e_supervisores', formData);
        listarSupervisores(); // refresca la tabla
  
        Swal.fire({
          icon: 'success',
          title: 'Supervisor eliminado',
          text: typeof resp === 'string' ? resp : 'El supervisor fue eliminado correctamente'
        });
      } catch (err) {
        console.error("Error eliminando supervisor:", err);
        Swal.fire({
          icon: 'error',
          title: 'Error al eliminar',
          text: 'Ocurrió un error al eliminar el supervisor'
        });
      }
    }
  }
  
  async function actualizarSupervisor(){
  
            // antes de capturar los valores del formulario debes validarlos
        let datosPersona = capturarValoresFormulario('form-modal-cargo-superv');
  
        // Agregar id_usuario
  let idUsuarioEval = document.getElementById('id_usuario_modal').value;
  datosPersona.append('id_usuario', idUsuarioEval);
         /*// Obtener el valor del select 'id_cargo_evaluador'
         let idCargoEval = document.getElementById('id_cargo_evaluador').value;
         // Agregarlo a los datos que se enviarán
         datosPersona.append('id_cargo_evaluador', idCargoEval);
          // Obtener el valor del select 'id_usuario' 
          let idUsuarioEval = document.getElementById('id_usuario').value;
          // Agregarlo a los datos que se enviarán
          datosPersona.append('id_usuario', idUsuarioEval);*/
        
          try {
            const resp = await microApi('controlador/?a_supervisores', datosPersona);
        
            if (typeof resp === 'string' && resp.includes(' No Existe')) {
              Swal.fire({
                icon: 'error',
                title: 'Supervisor no encontrado',
                text: resp
              });
            } else {
             // valorFormSuperv();
              listarSupervisores();
        
              Swal.fire({
                icon: 'success',
                title: 'Cargo actualizado',
                text: 'El cargo del supervisor se actualizó con éxito'
              });
            }
          } catch (err) {
            console.error("Error actualizando supervisor:", err);
            Swal.fire({
              icon: 'error',
              title: 'Error inesperado',
              text: 'Ocurrió un error al actualizar el supervisor'
            });
          }
  
  }
  
  function valorFormSuperv(usuariosupervisor='',cargosuperv=''){
    // Asignar valores a los campos del formulario
    document.getElementById('id_usuario').value = usuariosupervisor;
    document.getElementById('id_cargo_supervisor').value = cargosuperv;
    
  
    
  }
  
  // Cerrar el modal al hacer clic
  
    //Select para usuarios con el rol de evaluador

    async function listarUsuariosSupervisor() {
      try {
          // Obtener lista de supervisores desde API
          const respSupervisores = await microApi('controlador/?listar_supervisores');
          if (typeof respSupervisores === 'string') {
              console.error('Error al listar usuarios:', respSupervisores);
              return;
          }
  
          llenarSelectSupervisor(respSupervisores);
      } catch (err) {
          console.error('La petición falló:', err);
      }
  }
  
  function llenarSelectSupervisor(datos) {
      const select = document.getElementById('id_usuario');
      if (!select) return;
  
      select.innerHTML = '<option value="">Seleccione a un usuario</option>';
  
      const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
      const supervisores = registros.filter(item => item.rol === 'Supervisor del evaluador');
  
      supervisores.forEach(item => {
          const fullname = item.nombre_completo || item.cedula_usuario;
  
          const opcion = document.createElement('option');
          opcion.value = item.id_usuario;
          opcion.textContent = fullname;
  
          select.appendChild(opcion);
      });
  }
  
    
  //Select de cargos de evaluadores
  
  async function listarCargosSupervisores() {
    try {
      // Llamada a la API que devuelve cargos supervisores
      const resp = await microApi('controlador/?l_cargos_supervisores');
  
      if (typeof resp === 'string') {
        console.error('Error al listar cargos supervisores:', resp);
        return;
      }
  
      llenarSelectCargosSupervisores(resp);
    } catch (err) {
      console.error('La petición de cargos supervisores falló:', err);
    }
  }
  
  function llenarSelectCargosSupervisores(datos) {
    const select = document.getElementById('id_cargo_supervisor');
    if (!select) return;
  
    // Opción por defecto
    select.innerHTML = '<option value="">Seleccione a un cargo</option>';
  
    // Para manejar arrays anidados si los hubiera
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
  
    // Se asume que datos son objetos con id_cargo_supervisor y cargo_supervisor
    registros.forEach(item => {
      const opcion = document.createElement('option');
      opcion.value = item.id_cargo_supervisor;   // id para cada option
      opcion.textContent = item.cargo_supervisor; // texto visible en el select
  
      select.appendChild(opcion);
    });
  }
  
  function abrirModalCargoSuperv(origen, idUsuario, idCargoActualSuperv) {
    // 1) Resetear el formulario del modal
    document.getElementById("form-modal-cargo-superv").reset();
  
    // Mostrar u ocultar campos según el origen
     if (origen === 'boton') {
      document.getElementById('div_id_usuario_supervisor').style.display = 'block';
      } else {
        document.getElementById('div_id_usuario_supervisor').style.display = 'none';
      }

  // Asignar el valor recibido al campo oculto
  if (idUsuario) {
    document.getElementById("id_usuario_modal").value = idUsuario;
  } else {
    document.getElementById("id_usuario_modal").value = '';
  }
    // 3) Llenar el select de cargos y marcar el actual
    listarCargosSupervisoresModal(idCargoActualSuperv);
  
    // 4) Mostrar el modal
    $("#modalEditarCargoSuperv").modal("show");
  }
  
  
  
  
  function rellenarSelectCargosSuperv(datos, idSelect, idCargoActualSuperv = null) {
    const sel = document.getElementById(idSelect);
  
    // Limpia y agrega la opción por defecto
    sel.innerHTML = '<option value="">-- Seleccione --</option>';
  
    // Aplana si es un array de arrays
    const flat = Array.isArray(datos[0]) ? datos.flat() : datos;
  
    // Recorre y agrega opciones
    flat.forEach(o => {
      const opt = document.createElement('option');
      opt.value = o.id_cargo_supervisor;   
      opt.textContent = o.cargo_supervisor; 
      if (idCargoActualSuperv && o.id_cargo_supervisor == idCargoActualSuperv) {
        opt.selected = true;
      }
      sel.appendChild(opt);
    });
  }
  
  
  function listarCargosSupervisoresModal(idCargoActualSuperv) {
    return microApi('controlador/?l_cargos_supervisores')
      .then(datos => rellenarSelectCargosSuperv(datos, 'cargosuperv_modal', idCargoActualSuperv));
  }

  