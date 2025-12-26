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
      
     //formulario.submit(); // Enviar el formulario
      if(opc==1)
          guardarUsuario();  
      else
      actualizarUsuario();
          
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
let idRol = document.getElementById('id_rol_sistema').value;
datosPersona.append('rol_id', idRol);

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


/*function abrirModalEditar(cedula,clave, rolSistema) {
// 1) Resetear el formulario del modal
document.getElementById("form-modal-editar").reset();

// 2) Rellenar oculto y selects
document.getElementById("cedula_modal").value      = cedula;
document.getElementById("clave_modal").value       = clave; // No mostrar la clave real
document.getElementById("rol_modal").value         = rolSistema;

// 3) Mostrar el modal (Bootstrap/jQuery)
$("#modalEditar").modal("show");
}

function rellenarSelect(datos, idSelect) {
  const sel = document.getElementById(idSelect);

  // Limpia y agrega la opción por defecto
  sel.innerHTML = '<option value="">-- Seleccione --</option>';

  // Aplana si es un array de arrays
  const flat = Array.isArray(datos[0]) ? datos.flat() : datos;

  // Recorre y agrega opciones
  flat.forEach(o => {
    const opt = document.createElement('option');
    opt.value = o.rol_id;   // valor fijo
    opt.textContent = o.rol; // texto fijo
    sel.appendChild(opt);
  });
}

// Variantes de tu función de listar, apuntando al ID del modal

function listarRolesSistemaModal() {
return microApi('controlador/?listar_RolesSelect')
  .then(datos => rellenarSelect(datos, 'rol_modal'));
}*/
//==========================================================//
//Funcion para rellenar el fomulario

function editarUser(cedula, clave, rolId, nombreCompleto, tipo, ubicacion) {
// Rellenar los campos del formulario con los datos del usuario
document.getElementById('id_cedula_usuario').value = cedula;
//document.getElementById('cedula_modal').value = cedula; // campo oculto
document.getElementById('id_clave').value = clave;
document.getElementById('id_rol_sistema').value = rolId;
document.getElementById('fullname_input').value = nombreCompleto;
document.getElementById('type_str_input').value = tipo;
document.getElementById('additional_input').value = ubicacion;

// Mostrar el mensaje
document.getElementById('mensajeEditar').style.display = 'block';

// Activar el botón Editar
const btnEditar = document.getElementById('btnEditar');
btnEditar.disabled = false;
btnEditar.classList.remove('btn-secondary');
btnEditar.classList.add('btn-warning');
}

//==========================================================//

async function listarTablaUsuarios(datos) {
const tbody = document.querySelector("#tabla-usuarios tbody");
tbody.innerHTML = "";

// Cargar lista de roles desde la API
const rolesResp = await microApi('controlador/?listar_RolesSistema');
const rolesList = Array.isArray(rolesResp[0]) ? rolesResp.flat() : rolesResp;

let html = "";
const ADMIN_ID = 1;

for (let i = 0; i < datos.length; i++) {
  const grupo = datos[i];
  for (let j = 0; j < grupo.length; j++) {
    const item = grupo[j];

    // Buscar rol
    const rolObj = rolesList.find(r => r.rol_id == item.rol_id || r.idrol == item.rol_id);
    const rolTexto = rolObj ? (rolObj.rol || rolObj.nombrerol || "Desconocido") : "Desconocido";

    // Fila
    html += '<tr>';
    html += '<td>' + (item.clave ? "******" : "") + '</td>';
    html += '<td>' + item.cedula_usuario + '</td>';
    html += '<td>' + item.nombre_completo + '</td>'; // Suponiendo que ahora 'datos' incluye nombre_usuario
    html += '<td>' + rolTexto + '</td>';
    html += '<td>' + item.estado_usuario + '</td>'; // Nueva columna con estado

    // Acciones
    if (item.rol_id == ADMIN_ID) {
      html += `<td class="acciones">
        <div class="acciones-icons">
          <img src="img/iconos/Usuario Administrador.png" />
        </div>
      </td>`;
    } else {
      const btnPerm = `abrirModalPermisosUsuario('${item.id_usuario}')`;
      //const editarFila = `abrirModalEditar('${item.cedula_usuario}','${item.clave ? "******" : ""}','${item.rol_id}')`;
      const editarF = `editarUser('${item.cedula_usuario}', '${item.clave ? item.clave : ""}', '${item.rol_id}', '${item.nombre_completo}', '${item.tipo_empleado}', '${item.ubicacion_administrativa}')`;

      const btnEstadoUsuario = `cambiarEstadoUsuario('${item.id_usuario}', '${item.estado_usuario}')`;

      html += `
      <td>
        <div class="dropdown">
          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="javascript:void(0);" onclick="${editarF}"><i class="icon-base bx bx-edit-alt me-1"></i>Editar</a>
            <a class="dropdown-item" href="javascript:void(0);" onclick="${btnPerm}"><i class="icon-base bx bx-lock-open me-1"></i>Permisos</a>
            <a class="dropdown-item" href="javascript:void(0);" onclick="${btnEstadoUsuario}"><i class="icon-base bx bx-toggle-right me-1"></i>Cambiar estado</a>
          </div>
        </div>
      </td>`;
    }

    html += '</tr>';
  }
}

tbody.innerHTML = html;
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
let NuevoRol = document.getElementById('id_rol_sistema').value;
datosPersona.append('rol_id', NuevoRol);

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

Swal.fire({
    icon: 'success',
    title: 'Actualizacion de Usuario',
    text: 'El usuario se actualizó con éxito'
});
}

function pa(cad){
  document.getElementById('id_clave').value = MD5(cad);
}

function valorFormUsuario(cl='',ced='',RolSis=''){
 
  document.getElementById('id_clave').value = cl;
  document.getElementById('id_cedula_usuario').value = ced;
  document.getElementById('id_rol_sistema').value = RolSis;
  

  
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




//Select de roles del sistema

async function listarRolesSistema() {
try {
  // Llamas a la API que te devuelve los datos de jefes_superiores
  const resp = await microApi('controlador/?listar_RolesSelect');

  if (typeof resp === 'string') {
    console.error('Error al listar Roles del Sistema:', resp);
    return;
  }

  llenarSelectRoles(resp);
} catch (err) {
  console.error('La petición de Roles de Sistema falló:', err);
}
}

function llenarSelectRoles(datos) {
const select = document.getElementById('id_rol_sistema');
if (!select) return;

// Opción por defecto
select.innerHTML = '<option value="">-- Seleccione un rol del sistema --</option>';

const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

registros.forEach(item => {
    const valor = item.rol_id || item.idrol;
    const texto = item.rol || item.nombrerol;

  const opcion = document.createElement('option');
  opcion.value = valor;
  opcion.textContent = texto;
  select.appendChild(opcion);
});
}





