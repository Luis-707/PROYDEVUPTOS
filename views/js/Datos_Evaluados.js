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
  
     
  function validar_formDatosEvaluado(opc) {
  
     
    // Obtener el formulario
    var formulario = document.getElementById('formulario_DatosEvaluado');
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
        
       //formulario.submit(); // Enviar el formulario
        if(opc==1)
            guardarDatosEvaluado();  
        else
        actualizarDatosEvaluado();
            
    }
  }
  async function guardarDatosEvaluado() {
    // Capturar valores del formulario
    let datosPersona = capturarValoresFormulario('formulario_DatosEvaluado');

    // Agregar id_cargo_evaluador
    let idCargoEval = document.getElementById('id_cargo_evaluado').value;
    datosPersona.append('id_cargo_evaluado', idCargoEval);

    let idUsuarioEval = document.getElementById('id_usuario').value;
    datosPersona.append('id_usuario', idUsuarioEval);

    let idUsuarioSesion = document.getElementById('id_usuario_sesion').value;
    datosPersona.append('id_usuario_sesion', idUsuarioSesion);

    try {
      // Llamada al servicio
      const resp = await microApi('controlador/?g_datos_evaluado', datosPersona);

      if (!resp.success) {
        Swal.fire({
            icon: 'error',
            title: 'Error al guardar',
            text: resp.message
        });
    } else {
        valorFormDatosEvaluado();
        listarDatosEvaluados();
        Swal.fire({
            icon: 'success',
            title: 'Añadir Cargo de Evaluado',
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
  
  /*async function listarCargosEval(){
    var resp = await microApi('controlador/?l_cargos_evaluadores');
    listarTablaEvaluadores(resp);
  }*/
  
  async function listarDatosEvaluados(){
    var resp = await microApi('controlador/?l_datos_evaluados');
    listarTablaDatosEvaluados(resp);
  }
  
  async function buscarEvaluado(cod){
      
    let dato = capturarValoresFormulario('formulario_DatosEvaluado',cod);
  
    var resp = await microApi('controlador/?b_evaluado',dato);
   
  return resp;
   
  }
  
  async function listarTablaDatosEvaluados(datos) {
    const tbody = document.querySelector("#tabla-DatosEvaluados tbody");
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
      const additional = empleado ? empleado.additional || "" : "";
      const cargoTexto = item.cargo_evaluado || "Sin cargo";
  
      html += `
        <tr>
          <td>${cedula}</td>
          <td>${fullname}</td>
          <td>${additional}</td>
          <td>${cargoTexto}</td>
          <td>
          <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="javascript:void(0);" onclick="abrirModalEditarCargoEvaluado(${item.id_usuario}, ${item.id_cargo_evaluado})"><i class="icon-base bx bx-edit-alt me-1"></i>Editar</a>
              <a class="dropdown-item" href="javascript:void(0);" onclick="eliminarDatosEvaluado(${item.id_usuario})"><i class="icon-base bx bx-trash me-1"></i>Eliminar</a>
            </div>
          </div>
          </td>
        </tr>
      `;
    });
  
    tbody.innerHTML = html;
  }
  async function eliminarDatosEvaluado(idUsuario) {
    const result = await Swal.fire({
      title: '¿Está seguro de eliminar este cargo?',
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
        const resp = await microApi('controlador/?e_datos_evaluados', formData);
        listarDatosEvaluados(); // refresca la tabla
  
        Swal.fire({
          icon: 'success',
          title: 'Evaluado eliminado',
          text: typeof resp === 'string' ? resp : 'El evaluado fue eliminado correctamente'
        });
      } catch (err) {
        console.error("Error eliminando evaluado:", err);
        Swal.fire({
          icon: 'error',
          title: 'Error al eliminar',
          text: 'Ocurrió un error al eliminar el evaluado'
        });
      }
    }
  }
  
  async function actualizarDatosEvaluado(){
  
            // antes de capturar los valores del formulario debes validarlos
        let datosPersona = capturarValoresFormulario('form-modal-editar-cargo-evaluado');
  
         /*// Obtener el valor del select 'id_cargo_evaluador'
         let idCargoEval = document.getElementById('id_cargo_evaluador').value;
         // Agregarlo a los datos que se enviarán
         datosPersona.append('id_cargo_evaluador', idCargoEval);
          // Obtener el valor del select 'id_usuario' 
          let idUsuarioEval = document.getElementById('id_usuario').value;
          // Agregarlo a los datos que se enviarán
          datosPersona.append('id_usuario', idUsuarioEval);*/
        
          try {
            const resp = await microApi('controlador/?a_datos_evaluados', datosPersona);
        
            if (typeof resp === 'string' && resp.includes(' No Existe')) {
              Swal.fire({
                icon: 'error',
                title: 'Evaluado no encontrado',
                text: resp
              });
            } else {
              valorFormDatosEvaluado();
              listarDatosEvaluados();
        
              Swal.fire({
                icon: 'success',
                title: 'Cargo actualizado',
                text: 'El cargo del evaluado se actualizó con éxito'
              });
            }
          } catch (err) {
            console.error("Error actualizando evaluado:", err);
            Swal.fire({
              icon: 'error',
              title: 'Error inesperado',
              text: 'Ocurrió un error al actualizar el evaluado'
            });
          }
  
  }
  
  function valorFormDatosEvaluado(usuarioevaluado='',cargoseval='',usuario_sesion='' ){
    // Asignar valores a los campos del formulario
    document.getElementById('id_usuario').value = usuarioevaluado;
    document.getElementById('id_cargo_evaluado').value = cargoseval;
    document.getElementById('id_usuario_sesion').value = usuario_sesion;
   
    
  
    
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
    
    async function listarUsuariosEvaluados() {
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
        const respEvaluados = await microApi('controlador/?l_evaluados');
        if (typeof respEvaluados === 'string') {
          console.error('Error al listar usuarios:', respEvaluados);
          return;
        }
    
        llenarSelectEvaluados(respEvaluados, empleados);
      } catch (err) {
        console.error('La petición falló:', err);
      }
    }
    
    function llenarSelectEvaluados(datos, empleados) {
      const select = document.getElementById('id_usuario');
      if (!select) return;
    
      select.innerHTML = '<option value="">Seleccione a un usuario</option>';
    
      const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
      const supervisores = registros.filter(item => item.rol === 'Evaluado');
    
      supervisores.forEach(item => {
        const empleado = empleados.find(emp => emp.pin_str === item.cedula_usuario || emp.pin === item.cedula_usuario);
        const fullname = empleado ? empleado.fullname : item.cedula_usuario;
    
        const opcion = document.createElement('option');
        opcion.value = item.id_usuario;
        opcion.textContent = fullname;
    
        select.appendChild(opcion);
      });
    }
    
  //Select de cargos de evaluadores
  
  async function listarCargosEvaluados() {
    try {
      // Llamada a la API que devuelve cargos supervisores
      const resp = await microApi('controlador/?l_cargos_evaluados');
  
      if (typeof resp === 'string') {
        console.error('Error al listar cargos supervisores:', resp);
        return;
      }
  
      llenarSelectCargosEvaluados(resp);
    } catch (err) {
      console.error('La petición de cargos supervisores falló:', err);
    }
  }
  
  function llenarSelectCargosEvaluados(datos) {
    const select = document.getElementById('id_cargo_evaluado');
    if (!select) return;
  
    // Opción por defecto
    select.innerHTML = '<option value="">Seleccione a un cargo</option>';
  
    // Para manejar arrays anidados si los hubiera
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
  
    // Se asume que datos son objetos con id_cargo_supervisor y cargo_supervisor
    registros.forEach(item => {
      const opcion = document.createElement('option');
      opcion.value = item.id_cargo_evaluado;   // id para cada option
      opcion.textContent = item.cargo_evaluado; // texto visible en el select
  
      select.appendChild(opcion);
    });
  }
  
  function abrirModalEditarCargoEvaluado(idUsuario, idCargoActualEval) {
    // 1) Resetear el formulario del modal
    document.getElementById("form-modal-editar-cargo-evaluado").reset();
  
    // 2) Guardar el id_usuario en un campo oculto
    document.getElementById("id_usuario_modal").value = idUsuario;
  
    // 3) Llenar el select de cargos y marcar el actual
    listarCargosEvaluadosModal(idCargoActualEval);
  
    // 4) Mostrar el modal
    $("#modalEditarCargoDatosEval").modal("show");
  }
  
  
  
  
  function rellenarSelectCargosDatosEval(datos, idSelect, idCargoActualEval = null) {
    const sel = document.getElementById(idSelect);
  
    // Limpia y agrega la opción por defecto
    sel.innerHTML = '<option value="">-- Seleccione --</option>';
  
    // Aplana si es un array de arrays
    const flat = Array.isArray(datos[0]) ? datos.flat() : datos;
  
    // Recorre y agrega opciones
    flat.forEach(o => {
      const opt = document.createElement('option');
      opt.value = o.id_cargo_evaluado;   
      opt.textContent = o.cargo_evaluado; 
      if (idCargoActualEval && o.id_cargo_evaluado == idCargoActualEval) {
        opt.selected = true;
      }
      sel.appendChild(opt);
    });
  }
  
  
  function listarCargosEvaluadosModal(idCargoActualEval) {
    return microApi('controlador/?l_cargos_evaluados')
      .then(datos => rellenarSelectCargosDatosEval(datos, 'cargoEvaluado_modal', idCargoActualEval));
  }

  