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

   
function validar_form(opc) {

   
  // Obtener el formulario
  var formulario = document.getElementById('formulario_usuario');
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
       // Obtener el valor del campo id_usuario_modal
  var idUsuario = document.getElementById("id_usuario_modal").value;

  // Comprobar si el campo está vacío
  if (idUsuario.trim() === '') {
    guardarUsuario(); // Llamar a guardarUsuario si está vacío
  } else {
    actualizarUsuario(); // Llamar a actualizarUsuario si no está vacío
  }
  }
}
async function guardarUsuario() {

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

let datosPersona = capturarValoresFormulario('formulario_usuario');

let idCargo = document.getElementById('id_cargo').value;
datosPersona.append('id_cargo', idCargo);

let idUF = document.getElementById('id_uf').value;
datosPersona.append('id_uf', idUF);

let nombreCompleto = document.getElementById('fullname_input').value;
datosPersona.append('nombre_completo', nombreCompleto);

let tipoEmpleado = document.getElementById('type_str_input').value;
datosPersona.append('tipo_empleado', tipoEmpleado);

let ubicacion = document.getElementById('additional_input').value;
datosPersona.append('ubicacion_administrativa', ubicacion);

try {
    const resp = await microApi('controlador/?g_user', datosPersona);

    if (!resp.success) {
        Swal.fire({
            icon: 'error',
            title: 'Error al guardar',
            text: resp.message
        });
    } else {
        valorFormUsuario();
        listarUsuario();
        Swal.fire({
            icon: 'success',
            title: 'Añadir Usuario',
            text: resp.message
        });
    }
} catch (err) {
    console.error("Error en guardarUsuario:", err);
    Swal.fire({
        icon: 'error',
        title: 'Error inesperado',
        text: 'Ocurrió un error al guardar el usuario'
    });
}
}

async function listarUsuario(){
  var resp = await microApi('controlador/?l_user');
  listarTablaUsuarios(resp);
}



async function buscarUsuario(cod){
  
  let dato = capturarValoresFormulario('formulario_usuario',cod);

  var resp = await microApi('controlador/?b_user',dato);
 
return resp;
 
}

// Listar usuarios en tabla DataTables
/*async function listarTablaUsuarios(datos) {
// Cargar lista de roles desde la API
const rolesResp = await microApi('controlador/?listar_RolesSistema');
const rolesList = Array.isArray(rolesResp[0]) ? rolesResp.flat() : rolesResp;

// Aplanar datos si vienen anidados
const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

// Destruir DataTable existente si existe
if ($.fn.DataTable.isDataTable('#tabla-usuarios')) {
    $('#tabla-usuarios').DataTable().destroy();
}

// Limpiar tbody
$('#tabla-usuarios tbody').empty();

// Preparar datos para DataTables
const tableData = registros.map(item => {
    const ADMIN_ID = 1;
    
    // Buscar rol
    const rolObj = rolesList.find(r => r.rol_id == item.rol_id || r.idrol == item.rol_id);
    const rolTexto = rolObj ? (rolObj.rol || rolObj.nombrerol || "Desconocido") : "Desconocido";
    
    // Columna clave (oculta si existe)
    const claveCol = item.clave ? "******" : "";
    
    let acciones;
    
    if (item.rol_id == ADMIN_ID) {
        // Admin solo icono
        acciones = `
            <div class="acciones-icons">
                <img src="img/iconos/Usuario Administrador.png" alt="Admin" />
            </div>
        `;
    } else {
        // Usuario normal con dropdown
        const editarF = `abrirModalUsuario('${item.id_usuario}', '${item.cedula_usuario}', '${item.clave ? item.clave : ""}', '${item.rol_id}', '${item.nombre_completo}', '${item.tipo_empleado}', '${item.ubicacion_administrativa}')`;
        const btnPerm = `abrirModalPermisosUsuario('${item.id_usuario}')`;
        const btnEstadoUsuario = `cambiarEstadoUsuario('${item.id_usuario}', '${item.estado_usuario}')`;
        
        acciones = `
            <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);" onclick="${editarF}">
                        <i class="icon-base bx bx-edit-alt me-1"></i>Editar
                    </a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="${btnPerm}">
                        <i class="icon-base bx bx-lock-open me-1"></i>Permisos
                    </a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="${btnEstadoUsuario}">
                        <i class="icon-base bx bx-toggle-right me-1"></i>Cambiar estado
                    </a>
                </div>
            </div>
        `;
    }
    
    return [
        claveCol,
        item.cedula_usuario,
        item.nombre_completo,
        rolTexto,
        item.estado_usuario,
        acciones
    ];
});

// Inicializar DataTable
$('#tabla-usuarios').DataTable({
    data: tableData,
    columns: [
        { title: "Clave", width: "80px" },
        { title: "Cédula", width: "120px" },
        { title: "Nombre Completo" },
        { title: "Rol" },
        { title: "Estado", width: "100px" },
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
}*/

async function listarTablaUsuarios(datos) {
const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

// Destruir DataTable existente si existe
if ($.fn.DataTable.isDataTable('#tabla-usuarios')) {
    $('#tabla-usuarios').DataTable().destroy();
}

// Limpiar tbody
$('#tabla-usuarios tbody').empty();

// Preparar datos para DataTables
const tableData = registros.map(item => {
    const clave = item.clave ? "******" : "";  // Clave en asteriscos si no existe
    const cedula = String(item.cedula_usuario || "").trim();
    const nombreCompleto = item.nombre_completo || "Sin nombre";
    const nombreCargo = item.nombre_cargo || "Sin cargo";
    const estadoUsuario = item.estado_usuario;
    
    // Acciones con funciones específicas para usuarios
    const editarF = `abrirModalUsuario(
      '${item.id_usuario}', 
      '${cedula}', 
      '${item.clave ? item.clave : ""}', 
      '${item.nombre_completo}', 
      '${item.tipo_empleado || ""}', 
      '${item.ubicacion_administrativa || ""}', 
      '${item.id_cargo}', 
      '${item.id_uf}', 
      '${item.fecha_ingreso || ""}')`;
    const btnPerm = `abrirModalPermisosUsuario('${item.id_usuario}')`;
    const btnrol = `abrirModalRolesUsuario('${item.id_usuario}')`;
    const btnEstadoUsuario = `cambiarEstadoUsuario('${item.id_usuario}', '${item.estado_usuario}')`;
    
    const acciones = `
        <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="icon-base bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="javascript:void(0);" onclick="${editarF}">
                    <i class="icon-base bx bx-edit-alt me-1"></i>Editar
                </a>
                <a class="dropdown-item" href="javascript:void(0);" onclick="${btnPerm}">
                    <i class="icon-base bx bx-lock-open me-1"></i>Permisos
                </a>
                <a class="dropdown-item" href="javascript:void(0);" onclick="${btnrol}">
                    <i class="icon-base bx bx-lock-open me-1"></i>Roles
                </a>
                <a class="dropdown-item" href="javascript:void(0);" onclick="${btnEstadoUsuario}">
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
$('#tabla-usuarios').DataTable({
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
    pageLength: 10,
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


//==========================================================//
//Cambiar estado del usuario

async function cambiarEstadoUsuario(id_usuario, estado_usuario) {
// Determinar el estado opuesto
const nuevoEstadoUsuario = estado_usuario === 'Activo' ? 'Inactivo' : 'Activo';

const result = await Swal.fire({
  title: `¿Está seguro de cambiar el estado a "${nuevoEstadoUsuario}"?`,
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
  formData.append('estado_usuario', nuevoEstadoUsuario); // Enviar el estado opuesto

  try {
    const resp = await microApi('controlador/?cambiarEstadoUsuario', formData);
    listarUsuario();

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

async function eliminarUsuario(cod) {
const result = await Swal.fire({
    title: '¿Deseas eliminar este usuario?',
    text: "Esta acción no se puede deshacer",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
});

if (result.isConfirmed) {
    let dato = capturarValoresFormulario('formulario_usuario', cod);
    var resp = await microApi('controlador/?e_user', dato);

    listarUsuario();

    Swal.fire({
        icon: 'success',
        title: 'Eliminacion de usuario',
        text: 'El usuario fue eliminado correctamente'
    });
} else {
    valorFormUsuario(); // limpia formulario
}
}

async function actualizarUsuario() {
let datosPersona = capturarValoresFormulario('formulario_usuario');

let idCargo = document.getElementById('id_cargo').value;
datosPersona.append('id_cargo', idCargo);

let idUF = document.getElementById('id_uf').value;
datosPersona.append('id_uf', idUF);

let nombreEditar = document.getElementById('fullname_input').value;
datosPersona.append('nombre_completo', nombreEditar);

let tipoEmpleadoEditar = document.getElementById('type_str_input').value;
datosPersona.append('tipo_empleado', tipoEmpleadoEditar);

let ubicacionEditar = document.getElementById('additional_input').value;
datosPersona.append('ubicacion_administrativa', ubicacionEditar);

//let cedulaIdentidad = document.getElementById('cedula_modal').value;
//datosPersona.append('cedula_usuario', cedulaIdentidad);

var resp = await microApi('controlador/?a_user', datosPersona);

listarTablaUsuarios(resp);
valorFormUsuario();
Swal.fire({
    icon: 'success',
    title: 'Actualizacion de Usuario',
    text: 'El usuario se actualizó con éxito'
});
}

function pa(cad){
  document.getElementById('id_clave').value = MD5(cad);
}

function valorFormUsuario(idUser='',cl='',ced='',fullname='',typestr='',addict='',cargo='',uf='',ingreso=''){
 
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

 // Funciones de permisos usuario

 // Lista y renderiza los permisos de un usuario en el modal
async function listarPermisosUsuario(id_usuario) {
const datos = new FormData();
datos.append('id_usuario', id_usuario);

const resp = await microApi('controlador/?listar_permisos_usuarios', datos);
console.log("Respuesta listar_permisos_usuarios:", resp);

if (!resp) {
  Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron cargar los permisos' });
  return;
}

const registros = Array.isArray(resp[0]) ? resp.flat() : resp;

const cont = document.getElementById('contenedor-switches-permisos');
cont.innerHTML = '';

registros.forEach(p => {
  cont.innerHTML += `
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox"
             id="perm_${p.permisos_id}"
             ${p.acceso == 1 ? 'checked' : ''}
             onchange="togglePermiso(${id_usuario}, ${p.permisos_id}, this.checked)">
      <label class="form-check-label" for="perm_${p.permisos_id}">${p.nombre_permiso}</label>
    </div>`;
});
}

// Abre el modal de permisos y lista los permisos del usuario
function abrirModalPermisosUsuario(id_usuario) {
window.usuarioActual = id_usuario;

const modalEl = document.getElementById('modalPermisos');
const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
modal.show();

// Pintar switches al abrir
listarPermisosUsuario(id_usuario);
}

// Activa o desactiva un permiso y refresca el listado
async function togglePermiso(id_usuario, permisos_id, checked) {
const datos = new FormData();
datos.append('id_usuario', id_usuario);
datos.append('permisos_id', permisos_id);

const servicio = checked ? 'controlador/?activar_permiso' : 'controlador/?desactivar_permiso';
const resp = await microApi(servicio, datos);

if (!resp || resp.success === false) {
  // Revertir switch si falla
  document.getElementById(`perm_${permisos_id}`).checked = !checked;
  Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo actualizar el permiso' });
} else {
  // 🔄 Refrescar switches desde la BD
  await listarPermisosUsuario(id_usuario);
}
}




//Select de cargos del sistema

async function listarCargos() {
  try {
    // Llamada a la API para obtener los cargos
    const resp = await microApi('controlador/?l_cargos');

    if (typeof resp === 'string') {
      console.error('Error al listar cargos:', resp);
      return;
    }

    llenarSelectCargos(resp);
  } catch (err) {
    console.error('La petición de cargos falló:', err);
  }
}

function llenarSelectCargos(datos) {
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

//==================================================================//
//Listar ubicaciones fisicas (sede)
async function listarUbicacionesFisicas() {
try {
  // Llamada a la API para obtener las ubicaciones físicas
  const resp = await microApi('controlador/?l_uf');

  if (typeof resp === 'string') {
    console.error('Error al listar ubicaciones físicas:', resp);
    return;
  }

  llenarSelectUbicaciones(resp);
} catch (err) {
  console.error('La petición de ubicaciones físicas falló:', err);
}
}

function llenarSelectUbicaciones(datos) {
const select = document.getElementById('id_uf');
if (!select) return;

// Opción por defecto (coincide con tu HTML)
select.innerHTML = '<option selected>Seleccione una ubicacion fisica</option>';

// Manejo flexible del array de datos
const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

registros.forEach(item => {
  const valor = item.id_uf;        // ID de ubicación física como value
  const texto = item.nombre_ubicacion;  // Nombre como texto visible

  if (valor && texto) {  // Validación extra
    const opcion = document.createElement('option');
    opcion.value = valor;
    opcion.textContent = texto;
    select.appendChild(opcion);
  }
});
}

//=================================================================//

// Función para abrir el modal y rellenar el formulario con datos de la fila
function abrirModalUsuario(id_usuario = '', cedula_usuario = '', clave = '', nombre_completo = '', tipo_empleado = '', ubicacion_administrativa = '', id_cargo = '', id_uf = '', fecha_ingreso = '') {
// Resetear formulario para asegurar que los campos estén vacíos
const form = document.getElementById("formulario_usuario");
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
const tituloModal = document.querySelector("#modalUsuario .modal-title");
if (id_usuario) {
  tituloModal.textContent = "Editar usuario";
} else {
  tituloModal.textContent = "Nuevo usuario";
}

// Mostrar el modal
const modal = new bootstrap.Modal(document.getElementById('modalUsuario'));
modal.show();
}

// Lista y renderiza los roles de un usuario en el modal
async function listarRolesUsuario(id_usuario) {
const datos = new FormData();
datos.append('id_usuario', id_usuario);

// Consulta SQL adaptada para el controlador PHP
const resp = await microApi('controlador/?listar_roles_sistema', datos);
console.log("Respuesta listar_roles_usuario:", resp);

if (!resp) {
  Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron cargar los roles' });
  return;
}

const registros = Array.isArray(resp[0]) ? resp.flat() : resp;

const cont = document.getElementById('contenedor-switches-roles');
cont.innerHTML = '';

registros.forEach(r => {
  cont.innerHTML += `
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox"
             id="rol_${r.rol_id}"
             ${r.acceso == 1 ? 'checked' : ''}
             onchange="toggleRol(${id_usuario}, ${r.rol_id}, this.checked)">
      <label class="form-check-label" for="rol_${r.rol_id}">${r.rol}</label>
    </div>`;
});
}

// Abre el modal de roles y lista los roles del usuario
function abrirModalRolesUsuario(id_usuario) {
window.usuarioActual = id_usuario;

const modalEl = document.getElementById('modalRoles');
const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
modal.show();

// Pintar switches al abrir
listarRolesUsuario(id_usuario);
}

// Activa o desactiva un rol y refresca el listado
async function toggleRol(id_usuario, rol_id, checked) {
const datos = new FormData();
datos.append('id_usuario', id_usuario);
datos.append('rol_id', rol_id);

const servicio = checked ? 'controlador/?asignar_rol' : 'controlador/?revocar_rol';
const resp = await microApi(servicio, datos);

if (!resp || resp.success === false) {
  // Revertir switch si falla
  document.getElementById(`rol_${rol_id}`).checked = !checked;
  Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo actualizar el rol' });
} else {
  // 🔄 Refrescar switches desde la BD
  await listarRolesUsuario(id_usuario);
}
}

