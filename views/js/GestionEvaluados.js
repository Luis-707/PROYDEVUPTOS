

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

  
  // ========================
  //   // Validación y CRUD
  // =========================
  function validar_form_evaluado(opc) {
     // Obtener el formulario
     var formulario = document.getElementById('formulario_evaluado');
     //console.log(formulario);
     // Crear un objeto FormData
     var Data = new FormData(formulario);
     let isValid = true; // Variable para controlar si el formulario es válido
     console.log(Data);
     // Validar cada campo
     for (var [key, valor] of Data.entries()) {
         
         switch (key) { 
            
            case 'cedula_evaluado':
                if (valor === '' || !validarnumero(valor) || valor.length < 7 || valor.length > 10) {
                    isValid = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validación',
                        text: 'La cédula es obligatoria, debe contener solo números y tener entre 7 y 10 dígitos.'
                    });
                }
                break;
            case 'cargo_evaluado':
                if (valor === '') {
                    isValid = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validación',
                        text: 'El cargo del evaluado es obligatorio.'
                    });
                }
                case 'rol':
                  if (valor === '') {
                    isValid = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validación',
                        text: 'El rol del evaluado es obligatorio.'
                    });
                }
                break;


                default:
                    console.log(`Campo ${key} no requiere validación específica.`);
                 // Si hay un error, salimos del bucle
            if (!isValid) {
                break;
            }
            
            
         }

        } 

    if (isValid) {
      if (opc === 1) {
        guardarEvaluado();
      } else{
        actualizarEvaluado();
      }
    }
}
  
  async function guardarEvaluado() {
    let datos = capturarValoresFormulario('formulario_evaluado');
    
    // Agregar rol_id
  let idrolEvaluado = document.getElementById('id_rol_evaluado').value;
  datos.append('rol_id', idrolEvaluado);
    
    try {
      const resp = await microApi('controlador/?g_user_evaluado', datos);
      if (!resp.success) {
        Swal.fire({ icon: 'error', title: 'Error', text: resp.message });
      } else {
        valorFormEvaluado();
        listarGestionEvaluados();
        Swal.fire({ icon: 'success', title: 'Añadir usuario evaluado', text: resp.message });
      }
    } catch (err) {
      console.error("Error en guardarEvaluado:", err);
    }
  }
  
  async function listarGestionEvaluados() {
    const resp = await microApi('controlador/?l_user_evaluado');
    listarTablaEvaluados(resp);
  }
  
  async function listarTablaEvaluados(datos) {
    const tbody = document.querySelector("#tabla-GestionEvaluados tbody");
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
      const cedula = String(item.cedula_usuario || item.cedula_evaluado).trim();
  
      // Buscar empleado en JSON
      const empleado = empleados.find(emp =>
        emp.pin_str === cedula || emp.pin === cedula
      );
  
      const fullname = empleado ? empleado.fullname : "No encontrado";
      //const tipoEmpleado = empleado && empleado.type_str
        //? (Array.isArray(empleado.type_str) ? empleado.type_str.join(', ') : empleado.type_str)
        //: "Desconocido";
      const status = empleado ? empleado.status_str : "Desconocido";
  
      html += `
        <tr>
          <td>${item.clave ? "******" : ""}</td>
          <td>${cedula}</td>
          <td>${fullname}</td>
          <td>${status}</td>
        <td>
          <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="javascript:void(0);" onclick="abrirModalEditarEvaluado(${cedula})"><i class="icon-base bx bx-edit-alt me-1"></i>Editar</a>
              <a class="dropdown-item" href="javascript:void(0);" onclick="eliminarEvaluado(${item.cedula_usuario},${item.id_usuario})"><i class="icon-base bx bx-trash me-1"></i>Eliminar</a>
            </div>
          </div>
        </td>
        </tr>
      `;
    });
  
    tbody.innerHTML = html;
  }
  
  /*async function eliminarEvaluado(cedula) {
    const result = await Swal.fire({
      title: '¿Deseas eliminar este evaluado?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    });
    if (result.isConfirmed) {
      let datos = new FormData();
      datos.append('cedula_usuario', cedula);
      await microApi('controlador/?e_user_evaluado', datos);
      listarEvaluados();
      Swal.fire({ icon: 'success', title: 'Eliminado', text: 'Evaluado eliminado correctamente' });
    }
  }*/

//Eliminar usuario evaluado

/*async function eliminarEvaluado(cedula) {
  const result = await Swal.fire({
    title: '¿Deseas eliminar este evaluado?',
    text: "Esta acción no se puede deshacer",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  });

  if (result.isConfirmed) {
    let datos = capturarValoresFormulario('formulario_evaluado', cedula);
    //datos.append('otros_datos', cedula); // o 'cedula_usuario' según backend

    var resp = await microApi('controlador/?e_user_evaluado', datos);

    listarGestionEvaluados();

    Swal.fire({
      icon: 'success',
      title: 'Eliminación de evaluado',
      text: 'El evaluado fue eliminado correctamente'
    });
  } else {
    valorFormEvaluado(); // limpia formulario si cancela
  }
}
*/

async function eliminarEvaluado(cedula) {
  const result = await Swal.fire({
    title: '¿Deseas eliminar este evaluado?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  });
  if (result.isConfirmed) {
    const formData = new FormData();
    formData.append('cedula_usuario', cedula);
    await microApi('controlador/?e_user_evaluado', formData);
    listarGestionEvaluados();
    Swal.fire({ icon: 'success', title: 'Eliminado', text: 'Evaluado eliminado correctamente' });
  }
}


//=========================================


  
  async function actualizarEvaluado() {
    let datos = capturarValoresFormulario('form-modal-editar-evaluado');
    const resp = await microApi('controlador/?a_user_evaluado', datos);
    listarTablaEvaluados(resp);
    Swal.fire({ icon: 'success', title: 'Actualización', text: 'Evaluado actualizado con éxito' });
  }

//Limpiar formulario

function pa(cad){
  document.getElementById('id_clave').value = MD5(cad);
}

function valorFormEvaluado(cl='',ced='',RolSis=''){
 
  document.getElementById('id_clave').value = cl;
  document.getElementById('id_cedula_usuario').value = ced;
  document.getElementById('id_rol_evaluado').value = RolSis;
  //document.getElementById('id_cargo_evaluado').value = cargo;
  

  
}


  // =========================
// Select de cargos evaluados
// =========================
/*async function listarCargosEvaluados() {
    try {
      const resp = await microApi('controlador/?l_cargos_evaluados');
      if (typeof resp === 'string') {
        console.error('Error al listar cargos evaluados:', resp);
        return;
      }
      llenarSelectCargosEvaluados(resp);
    } catch (err) {
      console.error('La petición de cargos evaluados falló:', err);
    }
  }
  
  function llenarSelectCargosEvaluados(datos) {
    const select = document.getElementById('id_cargo_evaluado');
    if (!select) return;
    select.innerHTML = '<option value="">Seleccione un cargo</option>';
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
    registros.forEach(item => {
      const opcion = document.createElement('option');
      opcion.value = item.id_cargo_evaluado;
      opcion.textContent = item.cargo_evaluado;
      select.appendChild(opcion);
    });
  }*/

  //Select para rol de evaluado

  async function listarRolesEvaluados() {
    try {
      // Llamas a la API que te devuelve los datos de jefes_superiores
      const resp = await microApi('controlador/?listar_RolesSistema');
  
      if (typeof resp === 'string') {
        console.error('Error al listar Roles del Sistema:', resp);
        return;
      }
  
      llenarSelectSoloEvaluado(resp);
    } catch (err) {
      console.error('La petición de Roles de Sistema falló:', err);
    }
  }
  
  function llenarSelectSoloEvaluado(datos) {
    const select = document.getElementById('id_rol_evaluado');
    if (!select) return;
  
    // Opción por defecto
    //select.innerHTML = '<option value="">-- Seleccione un rol del sistema --</option>';
  
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
  
    // Filtrar para obtener solo el rol Evaluado
    const rolEvaluado = registros.find(item => (item.rol === 'Evaluado' || item.nombrerol === 'Evaluado'));
  
    if (rolEvaluado) {
      const valor = rolEvaluado.rol_id || rolEvaluado.idrol;
      const texto = rolEvaluado.rol || rolEvaluado.nombrerol;
  
      const opcion = document.createElement('option');
      opcion.value = valor;
      opcion.textContent = texto;
      select.appendChild(opcion);
    }
  }  
  
  // =========================
// Modal de edición
// =========================
function abrirModalEditarEvaluado(cedula) {
  // Reiniciar el formulario de edición
  document.getElementById("form-modal-editar-evaluado").reset();

  // Asignar la cédula al campo oculto del modal
  document.getElementById("cedula_modal_eval").value = cedula;

  // Mostrar el modal
  $("#modalEditarEvaluado").modal("show");
}