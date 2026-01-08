

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
  
  let clave = document.getElementById('id_clave').value;

// Verificar si la longitud de clave está entre 10 y 16 caracteres
if (clave.length < 10) {
  Swal.fire({
    icon: 'warning',
    title: 'Longitud inválida',
    text: 'La clave debe tener entre 10 y 16 caracteres.'
  });
  return; // Impide continuar con el guardado
}

  // Agregar rol_id
let idrolEvaluado = document.getElementById('id_rol_evaluado').value;
datos.append('rol_id', idrolEvaluado);

let nombreCompleto = document.getElementById('fullname_input').value;
datos.append('nombre_completo', nombreCompleto);

let tipoEmpleado = document.getElementById('type_str_input').value;
datos.append('tipo_empleado', tipoEmpleado);

let ubicacion = document.getElementById('additional_input').value;
datos.append('ubicacion_administrativa', ubicacion);

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

//==========================================================//
//Funcion para rellenar el fomulario

function editarUserEvaluado(cedula, clave, rolId, nombreCompleto, tipo, ubicacion) {
// Rellenar los campos del formulario con los datos del usuario
document.getElementById('id_cedula_usuario').value = cedula;
//document.getElementById('cedula_modal').value = cedula; // campo oculto
document.getElementById('id_clave').value = clave;
document.getElementById('id_rol_evaluado').value = rolId;
document.getElementById('fullname_input').value = nombreCompleto;
document.getElementById('type_str_input').value = tipo;
document.getElementById('additional_input').value = ubicacion;

// Mostrar el mensaje
document.getElementById('mensajeEditarEvaluado').style.display = 'block';

// Activar el botón Editar
const btnEditar = document.getElementById('btnEditarEval');
btnEditar.disabled = false;
btnEditar.classList.remove('btn-secondary');
btnEditar.classList.add('btn-warning');
}

//==========================================================//

//===============================================================//
//Funcion que crea las filas de la tabla
/*
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
    //const cargoTexto = item.cargo_evaluado || "Sin cargo";
    //const tipoEmpleado = empleado && empleado.type_str
      //? (Array.isArray(empleado.type_str) ? empleado.type_str.join(', ') : empleado.type_str)
      //: "Desconocido";
    //const status = empleado ? empleado.status_str : "Desconocido";

    html += `
      <tr>
        <td>${item.clave ? "******" : ""}</td>
        <td>${cedula}</td>
        <td>${fullname}</td>
        <td>${item.estado_usuario}</td>
      <td>
        <div class="dropdown">
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="javascript:void(0);" onclick="abrirModalEditarEvaluado(${cedula})"><i class="icon-base bx bx-edit-alt me-1"></i>Editar</a>
            <a class="dropdown-item" href="javascript:void(0);" onclick="eliminarEvaluado(${item.cedula_usuario},${item.id_usuario})"><i class="icon-base bx bx-trash me-1"></i>Eliminar</a>
            <a class="dropdown-item" href="javascript:void(0);" onclick="cambiarEstadoEvaluado(${item.id_usuario},'${item.estado_usuario}')"><i class="icon-base bx bx-toggle-right me-1"></i>Cambiar estado</a>
          </div>
        </div>
      </td>
      </tr>
    `;
  });

  tbody.innerHTML = html;
}*/
//Funcion para listar usuarios evaluados funcional
 /* async function listarTablaEvaluados(datos) {
  const tbody = document.querySelector("#tabla-GestionEvaluados tbody");
  tbody.innerHTML = "";

  // Aplanar si vienen anidados
  const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

  let html = "";

  registros.forEach(item => {
      const cedula = String(item.cedula_usuario || item.cedula_evaluado).trim();
      const fullname = item.nombre_completo || "No encontrado";

      html += `
          <tr>
              <td>${item.clave ? "******" : ""}</td>
              <td>${cedula}</td>
              <td>${fullname}</td>
              <td>${item.estado_usuario}</td>
              <td>
                  <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                          <i class="icon-base bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                          <a class="dropdown-item" href="javascript:void(0);" onclick="editarUserEvaluado('${item.cedula_usuario}', '${item.clave ? item.clave : ""}', '${item.rol_id}', '${item.nombre_completo}', '${item.tipo_empleado}', '${item.ubicacion_administrativa}')">
                              <i class="icon-base bx bx-edit-alt me-1"></i>Editar
                          </a>
                          <a class="dropdown-item" href="javascript:void(0);" onclick="cambiarEstadoEvaluado(${item.id_usuario},'${item.estado_usuario}')">
                              <i class="icon-base bx bx-toggle-right me-1"></i>Cambiar estado
                          </a>
                      </div>
                  </div>
              </td>
          </tr>
      `;
  });

  tbody.innerHTML = html;
}*/
// Listar evaluados en tabla DataTables
async function listarTablaEvaluados(datos) {
const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

// Destruir DataTable existente si existe
if ($.fn.DataTable.isDataTable('#tabla-GestionEvaluados')) {
    $('#tabla-GestionEvaluados').DataTable().destroy();
}

// Limpiar tbody
$('#tabla-GestionEvaluados tbody').empty();

// Preparar datos para DataTables
const tableData = registros.map(item => {
    const cedula = String(item.cedula_usuario || item.cedula_evaluado).trim();
    const fullname = item.nombre_completo || "No encontrado";
    const claveCol = item.clave ? "******" : "";
    
    // Botones de acción
    const acciones = `
        <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="icon-base bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="javascript:void(0);" 
                   onclick="editarUserEvaluado('${item.cedula_usuario}', '${item.clave ? item.clave : ""}', '${item.rol_id}', '${item.nombre_completo}', '${item.tipo_empleado}', '${item.ubicacion_administrativa}')">
                    <i class="icon-base bx bx-edit-alt me-1"></i>Editar
                </a>
                <a class="dropdown-item" href="javascript:void(0);" onclick="cambiarEstadoEvaluado(${item.id_usuario},'${item.estado_usuario}')">
                    <i class="icon-base bx bx-toggle-right me-1"></i>Cambiar estado
                </a>
            </div>
        </div>
    `;
    
    return [
        claveCol,
        cedula,
        fullname,
        item.estado_usuario,
        acciones
    ];
});

// Inicializar DataTable
$('#tabla-GestionEvaluados').DataTable({
    data: tableData,
    columns: [
        { title: "Clave", width: "80px" },
        { title: "Cédula", width: "120px" },
        { title: "Nombre Completo" },
        { title: "Estado", width: "300px" },
        { 
            title: "Acciones", 
            width: "120px",
            orderable: false,
            searchable: false
        }
    ],
    pageLength: 25,
    responsive: true,
    order: [[1, 'asc']], // Ordenar por cédula por defecto
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


//===============================================================//

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

//==========================================================//
//Cambiar estado del usuario

async function cambiarEstadoEvaluado(id_usuario, estado_usuario) {
// Determinar el estado opuesto
const nuevoEstadoEvaluado = estado_usuario === 'Activo' ? 'Inactivo' : 'Activo';

const result = await Swal.fire({
  title: `¿Está seguro de cambiar el estado a "${nuevoEstadoEvaluado}"?`,
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Sí, cambiar',
  cancelButtonText: 'Cancelar'
});

if (result.isConfirmed) {
  const formData = new FormData();
  formData.append('id_usuario', id_usuario);
  formData.append('estado_usuario', nuevoEstadoEvaluado); // Enviar el estado opuesto

  try {
    const resp = await microApi('controlador/?cambioEstadoEvaluado', formData);
    listarGestionEvaluados();

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

//==========================================================//


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
  let datos = capturarValoresFormulario('formulario_evaluado');

  let NuevoRo = document.getElementById('id_rol_evaluado').value;
  datos.append('rol_id', NuevoRo);

  let cedulaEval = document.getElementById('id_cedula_usuario').value;
  datos.append('cedula_usuario', cedulaEval);

  let nombreEditar = document.getElementById('fullname_input').value;
  datos.append('nombre_completo', nombreEditar);

  let tipoEmpleadoEditar = document.getElementById('type_str_input').value;
  datos.append('tipo_empleado', tipoEmpleadoEditar);

  let ubicacionEditar = document.getElementById('additional_input').value;
  datos.append('ubicacion_administrativa', ubicacionEditar);

  var resp = await microApi('controlador/?a_user_evaluado', datos);
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