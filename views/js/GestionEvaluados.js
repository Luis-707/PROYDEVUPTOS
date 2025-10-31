

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
     var formulario = document.getElementById('formulario_Gevaluado');
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
    let datos = capturarValoresFormulario('formulario_Gevaluado');
    try {
      const resp = await microApi('controlador/?g_evaluado', datos);
      if (!resp.success) {
        Swal.fire({ icon: 'error', title: 'Error', text: resp.message });
      } else {
        listarEvaluados();
        Swal.fire({ icon: 'success', title: 'Añadir Evaluado', text: resp.message });
      }
    } catch (err) {
      console.error("Error en guardarEvaluado:", err);
    }
  }
  
  async function listarGestionEvaluados() {
    const resp = await microApi('controlador/?l_evaluados');
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
      const cargoTexto = item.cargo_evaluado || "Sin cargo";
      const tipoEmpleado = empleado && empleado.type_str
        ? (Array.isArray(empleado.type_str) ? empleado.type_str.join(', ') : empleado.type_str)
        : "Desconocido";
      const additionalInfo = empleado ? empleado.additional : "N/A";
  
      html += `
        <tr>
          <td>${fullname}</td>
          <td>${cedula}</td>
          <td>${tipoEmpleado}</td>
          <td>${additionalInfo}</td>
          <td>${cargoTexto}</td>
          <td class="acciones">
            <div class="acciones-icons">
              <img src="img/iconos/actualizar.png" 
                   onclick="abrirModalEditarEvaluado(${item.id_usuario}, ${item.id_cargo_evaluado})" />
              <img src="img/iconos/eliminar.png" 
                   onclick="eliminarEvaluado(${item.id_usuario})" />
            </div>
          </td>
        </tr>
      `;
    });
  
    tbody.innerHTML = html;
  }
  
  async function eliminarEvaluado(cedula) {
    const result = await Swal.fire({
      title: '¿Deseas eliminar este evaluado?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    });
    if (result.isConfirmed) {
      let datos = new FormData();
      datos.append('cedula_evaluado', cedula);
      await microApi('controlador/?e_evaluado', datos);
      listarEvaluados();
      Swal.fire({ icon: 'success', title: 'Eliminado', text: 'Evaluado eliminado correctamente' });
    }
  }
  
  async function actualizarEvaluado() {
    let datos = capturarValoresFormulario('form-modal-editar-evaluado');
    const resp = await microApi('controlador/?a_evaluado', datos);
    listarTablaEvaluados(resp);
    Swal.fire({ icon: 'success', title: 'Actualización', text: 'Evaluado actualizado con éxito' });
  }

  // =========================
// Select de cargos evaluados
// =========================
async function listarCargosEvaluados() {
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
  }
  
  // =========================
  // Modal de edición
  // =========================
  function abrirModalEditarEvaluado(cedula, idCargo) {
    document.getElementById("form-modal-editar-evaluado").reset();
    document.getElementById("cedula_modal_eval").value = cedula;
    listarCargosEvaluadosModal(idCargo);
    $("#modalEditarEvaluado").modal("show");
  }
  
  function listarCargosEvaluadosModal(idCargo) {
    return microApi('controlador/?l_cargos_evaluados')
      .then(datos => {
        const sel = document.getElementById('cargo_modal_eval');
        sel.innerHTML = '<option value="">-- Seleccione --</option>';
        const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
        registros.forEach(o => {
          const opt = document.createElement('option');
          opt.value = o.id_cargo_evaluado;
          opt.textContent = o.cargo_evaluado;
          if (idCargo && o.id_cargo_evaluado == idCargo) {
            opt.selected = true;
          }
          sel.appendChild(opt);
        });
      });
  }