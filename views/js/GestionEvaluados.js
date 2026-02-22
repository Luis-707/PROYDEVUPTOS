

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
         // Obtener el valor del campo id_usuario_modal
  var idUsuario = document.getElementById("id_usuario_modal").value;

  // Comprobar si el campo está vacío
  if (idUsuario.trim() === '') {
    guardarEvaluado(); // Llamar a guardarEvaluado si está vacío
  } else {
    actualizarEvaluado(); // Llamar a actualizarEvaluado si no está vacío
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
//let idrolEvaluado = document.getElementById('id_rol_evaluado').value;
//datos.append('rol_id', idrolEvaluado);

let idCargo = document.getElementById('id_cargo').value;
datos.append('id_cargo', idCargo);

let idUF = document.getElementById('id_uf').value;
datos.append('id_uf', idUF);

let nombreCompleto = document.getElementById('fullname_input').value;
datos.append('nombre_completo', nombreCompleto);

let tipoEmpleado = document.getElementById('type_str_input').value;
datos.append('tipo_empleado', tipoEmpleado);

let ubicacion = document.getElementById('additional_input').value;
datos.append('ubicacion_administrativa', ubicacion);

for (let [k, v] of datos.entries()) console.log(k, v);

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
  const clave = item.clave ? "******" : "";  // Clave en asteriscos si no existe
  const cedula = String(item.cedula_usuario || "").trim();
  const nombreCompleto = item.subordinado || "Sin nombre";
  const nombreCargo = item.cargo || "Sin cargo";
  const estadoUsuario = item.estado_usuario;

  // Acciones con funciones específicas para usuarios
  const editarEval = `abrirModalUsuarioEval(
    '${item.id}', 
    '${cedula}', 
    '${item.clave ? item.clave : ""}', 
    '${item.subordinado}', 
    '${item.tipo_empleado || ""}', 
    '${item.ubicacion_administrativa || ""}', 
    '${item.id_cargo}', 
    '${item.id_uf}', 
    '${item.fecha_ingreso || ""}')`;
    const btnEstadoUsuarioEval = `cambiarEstadoEvaluado('${item.id}', '${item.estado_usuario}')`;

    // Botones de acción
    const acciones = `
        <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="icon-base bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="javascript:void(0);" 
                onclick="${editarEval}">
                    <i class="icon-base bx bx-edit-alt me-1"></i>Editar
                </a>
                <a class="dropdown-item" href="javascript:void(0);" onclick="${btnEstadoUsuarioEval}">
                    <i class="icon-base bx bx-toggle-right me-1"></i>Cambiar estado
                </a>
            </div>
        </div>
    `;
    
    return [
      clave,
      cedula,
      nombreCompleto,
      nombreCargo,
      estadoUsuario,
      acciones
    ];
});

// Inicializar DataTable
$('#tabla-GestionEvaluados').DataTable({
    data: tableData,
    columns: [
      { title: "Clave", width: "100px" },
      { title: "Cédula", width: "140px" },
      { title: "Apellidos y nombres" },
      { title: "Cargo" },
      { title: "Estado", width: "100px" },
      { 
          title: "Acciones", 
          width: "140px",
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
//editar usuario evaluado (subordinado abajo de la jerarquia)  
async function actualizarEvaluado() {
  let datos = capturarValoresFormulario('formulario_evaluado');

  let idCargo = document.getElementById('id_cargo').value;
  datos.append('id_cargo', idCargo);

  let idUF = document.getElementById('id_uf').value;
  datos.append('id_uf', idUF);

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
  valorFormEvaluado();
}

//Limpiar formulario

function pa(cad){
document.getElementById('id_clave').value = MD5(cad);
}
/*
function valorFormEvaluado(cl='',ced='',RolSis=''){

document.getElementById('id_clave').value = cl;
document.getElementById('id_cedula_usuario').value = ced;
//document.getElementById('id_rol_evaluado').value = RolSis;
//document.getElementById('id_cargo_evaluado').value = cargo;



}*/

function valorFormEvaluado(idUser='',cl='',ced='',fullname='',typestr='',addict='',cargo='',uf='',ingreso=''){
 
document.getElementById('id_usuario_modal').value = idUser;  
  document.getElementById('id_clave').value = cl;
  document.getElementById('id_cedula_usuario').value = ced;
  document.getElementById('fullname_input').value = fullname;
  document.getElementById('type_str_input').value = typestr;
  document.getElementById('additional_input').value = addict;
  document.getElementById('id_cargo').value = cargo;    
  document.getElementById('id_uf').value = uf;
  document.getElementById('fecha_ingreso').value = ingreso;
}

// =========================
// Modal de edición
// =========================
// Función para abrir el modal y rellenar el formulario con datos de la fila
function abrirModalUsuarioEval(id_usuario = '', cedula_usuario = '', clave = '', nombre_completo = '', tipo_empleado = '', ubicacion_administrativa = '', id_cargo = '', id_uf = '', fecha_ingreso = '') {
// Resetear formulario para asegurar que los campos estén vacíos
const form = document.getElementById("formulario_evaluado");
form.reset();

// Asignar los valores, pero si son undefined, asignar cadena vacía
document.getElementById("id_usuario_modal").value = id_usuario || '';
document.getElementById("id_cedula_usuario").value = cedula_usuario || '';
document.getElementById("fullname_input").value = nombre_completo || '';
document.getElementById("type_str_input").value = tipo_empleado || '';
document.getElementById("additional_input").value = ubicacion_administrativa || '';
document.getElementById("id_clave").value = clave || '';
document.getElementById("id_cargo").value = id_cargo || '';
document.getElementById("id_uf").value = id_uf || '';
document.getElementById("fecha_ingreso").value = fecha_ingreso || '';

// Cambiar el título según si hay ID
const tituloModal = document.querySelector("#modalUsuarioEval .modal-title");
if (id_usuario) {
  tituloModal.textContent = "Editar usuario";
} else {
  tituloModal.textContent = "Nuevo usuario";
}

// Mostrar el modal
const modal = new bootstrap.Modal(document.getElementById('modalUsuarioEval'));
modal.show();
}

//=====================================

async function listarCargosSub() {
try {
  // Llamada a la API para obtener los cargos
  const resp = await microApi('controlador/?l_cargos_sub');

  if (typeof resp === 'string') {
    console.error('Error al listar cargos:', resp);
    return;
  }

  llenarSelectCargosSub(resp);
} catch (err) {
  console.error('La petición de cargos falló:', err);
}
}

function llenarSelectCargosSub(datos) {
const select = document.getElementById('id_cargo');
if (!select) return;

// Opción por defecto (coincide con tu HTML)
select.innerHTML = '<option selected>Seleccione un cargo</option>';

// Manejo flexible del array de datos
const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

registros.forEach(item => {
  const valor = item.id_cargo;  // ID del cargo como value
  const texto = item.nombre_cargo;  // Nombre como texto visible

  if (valor && texto) {  // Validación extra
    const opcion = document.createElement('option');
    opcion.value = valor;
    opcion.textContent = texto;
    select.appendChild(opcion);
  }
});
}