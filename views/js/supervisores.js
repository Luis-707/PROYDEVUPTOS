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
  
     
  function validar_formSupervisor(opc) {
  
     
    // Obtener el formulario
    var formulario = document.getElementById('formulario_supervisor');
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
            guardarSupervisor();  
        else
        actualizarSupervisor();
            
    }
  }
  async function guardarSupervisor() {
    // Capturar valores del formulario
    let datosPersona = capturarValoresFormulario('formulario_supervisor');

    // Agregar id_cargo_evaluador
    let idCargoEvalSuperv = document.getElementById('id_cargo_supervisor').value;
    datosPersona.append('id_cargo_evaluador', idCargoEvalSuperv);

    // Agregar id_usuario
    let idUsuarioEvalSuperv = document.getElementById('id_usuario').value;
    datosPersona.append('id_usuario', idUsuarioEvalSuperv);

    try {
        // Llamada al servicio
        const resp = await microApi('controlador/?g_cargosupervisor', datosPersona);

        // Validar respuesta JSON
        if (!resp.success) {
            alert(resp.message);
        } else {
            valorFormSuperv();      // Limpia formulario
            listarSupervisores();   // Refresca tabla
            alert(resp.message);
        }
    } catch (err) {
        console.error("Error en guardarSupervisor:", err);
        alert("Ocurrió un error al guardar el cargo");
    }
}
  
  /*async function listarCargosEval(){
    var resp = await microApi('controlador/?l_cargos_evaluadores');
    listarTablaEvaluadores(resp);
  }*/
  
  async function listarSupervisores(){
    var resp = await microApi('controlador/?l_supervisores');
    listarTablaSupervisores(resp);
  }
  
  async function buscarSupervisor(cod){
      
    let dato = capturarValoresFormulario('formulario_supervisor',cod);
  
    var resp = await microApi('controlador/?b_supervisor',dato);
   
  return resp;
   
  }
  
  async function listarTablaSupervisores(datos) {
    const tbody = document.querySelector("#tabla-supervisores tbody");
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
        const cargoTexto = item.cargo_supervisor || "Sin cargo";
  
        html += `
            <tr>
                <td>${fullname}</td>
                <td>${cedula}</td>
                <td>${cargoTexto}</td>
                <td onclick="abrirModalEditarCargoSuperv(${item.id_usuario}, ${item.id_cargo_supervisor})">
                    <img src="img/iconos/actualizar.png" style="cursor:pointer;width: 22px;">
                </td>
                <td onclick="eliminarSupervisor(${item.id_usuario})">
                    <img src="img/iconos/eliminar.png" style="cursor:pointer;width: 22px;">
                </td>
            </tr>
        `;
    });
  
    tbody.innerHTML = html;
  }
  async function eliminarSupervisor(idUsuario) {
    if (confirm('¿Está seguro de eliminar este supervisor?')) {
        // Creamos el FormData con el id_usuario que espera el servicio
        const formData = new FormData();
        formData.append('id_usuario', idUsuario);
  
        try {
            const resp = await microApi('controlador/?e_supervisores', formData);
            listarSupervisores(); // refresca la tabla
            alert(resp);
        } catch (err) {
            console.error("Error eliminando supervisor:", err);
            alert("Ocurrió un error al eliminar el supervisor");
        }
    }
  }
  
  async function actualizarSupervisor(){
  
            // antes de capturar los valores del formulario debes validarlos
        let datosPersona = capturarValoresFormulario('form-modal-editar-cargo-superv');
  
         /*// Obtener el valor del select 'id_cargo_evaluador'
         let idCargoEval = document.getElementById('id_cargo_evaluador').value;
         // Agregarlo a los datos que se enviarán
         datosPersona.append('id_cargo_evaluador', idCargoEval);
          // Obtener el valor del select 'id_usuario' 
          let idUsuarioEval = document.getElementById('id_usuario').value;
          // Agregarlo a los datos que se enviarán
          datosPersona.append('id_usuario', idUsuarioEval);*/
        
        var resp = await microApi('controlador/?a_supervisores',datosPersona);
             // limpia formulario
            if (resp.includes(' No Existe')) {
                alert(resp);
            }else{
                valorFormSuperv();
                listarSupervisores();
                alert('El Cargo se Actualizo con Exito');
            }
  
  }
  
  function valorFormSuperv(usuariosupervisor='',cargosuperv=''){
    // Asignar valores a los campos del formulario
    document.getElementById('id_usuario').value = usuariosupervisor;
    document.getElementById('id_cargo_supervisor').value = cargosuperv;
    
  
    
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
    
    async function listarUsuariosSupervisor() {
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
        const respSupervisores = await microApi('controlador/?listar_supervisores');
        if (typeof respSupervisores === 'string') {
          console.error('Error al listar usuarios:', respSupervisores);
          return;
        }
    
        llenarSelectSupervisor(respSupervisores, empleados);
      } catch (err) {
        console.error('La petición falló:', err);
      }
    }
    
    function llenarSelectSupervisor(datos, empleados) {
      const select = document.getElementById('id_usuario');
      if (!select) return;
    
      select.innerHTML = '<option value="">Seleccione a un usuario</option>';
    
      const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
      const supervisores = registros.filter(item => item.rol === 'Supervisor del Evaluador');
    
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
  
  function abrirModalEditarCargoSuperv(idUsuario, idCargoActualSuperv) {
    // 1) Resetear el formulario del modal
    document.getElementById("form-modal-editar-cargo-superv").reset();
  
    // 2) Guardar el id_usuario en un campo oculto
    document.getElementById("id_usuario_modal").value = idUsuario;
  
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

  