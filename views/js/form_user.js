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

  var formulario = document.getElementById('formulario_usuario');
  var Data = new FormData(formulario);
  let isValid = true;

  // Campos adicionales que NO están en el FormData original
  let cargo = document.getElementById("id_cargo").value;
  let uf = document.getElementById("id_uf").value;
  let fechaIngreso = document.getElementById("fecha_ingreso").value;

  for (var [key, valor] of Data.entries()) {

      valor = valor.trim();

      switch (key) {

          case 'cedula_usuario':
              if (valor === "" || !/^[0-9]+$/.test(valor) || valor.length < 6) {
                  Swal.fire({
                      icon: "warning",
                      title: "Cédula inválida",
                      text: "Debe ingresar una cédula válida (solo números, mínimo 6 dígitos)."
                  });
                  isValid = false;
              }
          break;

          case 'fullname':
              if (valor === "" || valor === "undefined") {
                  Swal.fire({
                      icon: "warning",
                      title: "Nombre requerido",
                      text: "Debe buscar y cargar los datos del usuario antes de guardar."
                  });
                  isValid = false;
              }
          break;

          case 'type_str':
              if (valor === "") {
                  Swal.fire({
                      icon: "warning",
                      title: "Tipo de empleado requerido",
                      text: "Debe cargar el tipo de empleado antes de continuar."
                  });
                  isValid = false;
              }
          break;

          case 'additional':
              if (valor === "") {
                  Swal.fire({
                      icon: "warning",
                      title: "Ubicación requerida",
                      text: "Debe cargar la ubicación administrativa antes de continuar."
                  });
                  isValid = false;
              }
          break;

         case 'clave':
    const idUsuario = document.getElementById("id_usuario_modal").value;

    // Nuevo usuario → clave obligatoria
    if (idUsuario.trim() === '') {
        if (valor === "" || valor.length < 10) {
            Swal.fire({
                icon: "warning",
                title: "Clave inválida",
                text: "La clave debe tener al menos 10 caracteres."
            });
            isValid = false;
        }
    }

    // Edición → clave opcional
    else {
        if (valor !== "" && valor.length < 10) {
            Swal.fire({
                icon: "warning",
                title: "Clave inválida",
                text: "Si desea cambiar la clave, debe tener al menos 10 caracteres."
            });
            isValid = false;
        }
    }
break;
      }

      if (!isValid) break;
  }

  // Validación de selects fuera del FormData
  if (isValid) {

      if (cargo === "" || cargo === "Seleccione un cargo") {
          Swal.fire({
              icon: "warning",
              title: "Cargo requerido",
              text: "Debe seleccionar un cargo válido."
          });
          return;
      }

      if (uf === "" || uf === "Seleccione una ubicacion fisica") {
          Swal.fire({
              icon: "warning",
              title: "Ubicación física requerida",
              text: "Debe seleccionar una ubicación física válida."
          });
          return;
      }

      if (fechaIngreso === "") {
          Swal.fire({
              icon: "warning",
              title: "Fecha requerida",
              text: "Debe seleccionar una fecha de ingreso válida."
          });
          return;
      }

      // Si todo está correcto → guardar o actualizar
      var idUsuario = document.getElementById("id_usuario_modal").value;

      if (idUsuario.trim() === '') {
          guardarUsuario();
      } else {
          actualizarUsuario();
      }
  }
}
async function guardarUsuario() {

  let clave = document.getElementById('id_clave').value;

  if (clave.length < 10) {
    Swal.fire({
      icon: 'warning',
      title: 'Longitud inválida',
      text: 'La clave debe tener al menos 10 caracteres.'
    });
    return;
  }

  let datosPersona = capturarValoresFormulario('formulario_usuario');

  datosPersona.append('id_cargo', document.getElementById('id_cargo').value);
  datosPersona.append('id_uf', document.getElementById('id_uf').value);
  datosPersona.append('nombre_completo', document.getElementById('fullname_input').value);
  datosPersona.append('tipo_empleado', document.getElementById('type_str_input').value);
  datosPersona.append('ubicacion_administrativa', document.getElementById('additional_input').value);

  const idCargo = document.getElementById('id_cargo').value;

  // ============================================================
  // 🔍 VALIDACIÓN DE CARGO OCUPADO POR OTRO USUARIO ACTIVO
  // ============================================================
  const usuarios = await microApi('controlador/?l_user');
  const lista = Array.isArray(usuarios[0]) ? usuarios.flat() : usuarios;

  const ocupado = lista.some(u =>
    u.id_cargo == idCargo &&
    u.estado_usuario === 'Activo'
  );

  if (ocupado) {
    Swal.fire({
      icon: 'error',
      title: 'Cargo ocupado',
      text: 'Este cargo ya está asignado a un usuario ACTIVO.'
    });
    return;
  }

  // ============================================================
  // Guardar usuario
  // ============================================================
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
      '', 
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
                    <i class="icon-base bx bx-group me-1"></i>Roles
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

  if (!result.isConfirmed) return;

  // ============================================================
  // 🔍 VALIDACIÓN FRONTEND: evitar activar un usuario cuyo cargo
  //     ya está ocupado por otro usuario ACTIVO
  // ============================================================
  if (nuevoEstadoUsuario === 'Activo') {

    // Obtener lista de usuarios
    const usuarios = await microApi('controlador/?l_user');
    const lista = Array.isArray(usuarios[0]) ? usuarios.flat() : usuarios;

    // Buscar al usuario que se está activando
    const usuarioActual = lista.find(u => u.id_usuario == id_usuario);

    if (usuarioActual) {
      const idCargo = usuarioActual.id_cargo;

      // Verificar si otro usuario activo tiene este cargo
      const ocupado = lista.some(u =>
        u.id_cargo == idCargo &&
        u.estado_usuario === 'Activo' &&
        u.id_usuario != id_usuario
      );

      if (ocupado) {
        Swal.fire({
          icon: 'error',
          title: 'Cargo ocupado',
          text: 'No puede ACTIVAR este usuario: su cargo ya está ocupado por otro usuario ACTIVO.'
        });
        return;
      }
    }
  }

  // ============================================================
  // Enviar solicitud al backend
  // ============================================================
  const formData = new FormData();
  formData.append('id_usuario', id_usuario);
  formData.append('estado_usuario', nuevoEstadoUsuario);

  try {
    const resp = await microApi('controlador/?cambiarEstadoUsuario', formData);
    listarUsuario();

    Swal.fire({
      icon: resp.success ? 'success' : 'error',
      title: resp.success ? 'Estado cambiado' : 'Error',
      text: resp.message || 'El estado fue cambiado correctamente'
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

  datosPersona.append('id_cargo', document.getElementById('id_cargo').value);
  datosPersona.append('id_uf', document.getElementById('id_uf').value);
  datosPersona.append('nombre_completo', document.getElementById('fullname_input').value);
  datosPersona.append('tipo_empleado', document.getElementById('type_str_input').value);
  datosPersona.append('ubicacion_administrativa', document.getElementById('additional_input').value);

  const idCargo = document.getElementById('id_cargo').value;
  const idUsuario = document.getElementById('id_usuario_modal').value;

  // ============================================================
  // 🔍 VALIDACIÓN DE CARGO OCUPADO POR OTRO USUARIO ACTIVO
  // ============================================================
  const usuarios = await microApi('controlador/?l_user');
  const lista = Array.isArray(usuarios[0]) ? usuarios.flat() : usuarios;

  const ocupado = lista.some(u =>
    u.id_cargo == idCargo &&
    u.estado_usuario === 'Activo' &&
    u.id_usuario != idUsuario
  );

  if (ocupado) {
    Swal.fire({
      icon: 'error',
      title: 'Cargo ocupado',
      text: 'Este cargo ya está asignado a un usuario ACTIVO.'
    });
    return;
  }

  // ============================================================
  // Actualizar usuario
  // ============================================================
  try {
    var resp = await microApi('controlador/?a_user', datosPersona);

    listarTablaUsuarios(resp.data);
    valorFormUsuario();

    Swal.fire({
      icon: 'success',
      title: 'Actualización de Usuario',
      text: 'El usuario se actualizó con éxito'
    });

  } catch (err) {
    console.error("Error en actualizarUsuario:", err);
    Swal.fire({
      icon: 'error',
      title: 'Error inesperado',
      text: 'Ocurrió un error al actualizar el usuario'
    });
  }
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

function abrirModalUsuario(
  id_usuario = '',
  cedula_usuario = '',
  clave = '',
  nombre_completo = '',
  tipo_empleado = '',
  ubicacion_administrativa = '',
  id_cargo = '',
  id_uf = '',
  fecha_ingreso = ''
) {
    const form = document.getElementById("formulario_usuario");
    form.reset();

    document.getElementById("id_usuario_modal").value = id_usuario || '';
    document.getElementById("id_cedula_usuario").value = cedula_usuario || '';
    document.getElementById("fullname_input").value = nombre_completo || '';
    document.getElementById("type_str_input").value = tipo_empleado || '';
    document.getElementById("additional_input").value = ubicacion_administrativa || '';
    document.getElementById("id_cargo").value = id_cargo || '';
    document.getElementById("id_uf").value = id_uf || '';
    document.getElementById("fecha_ingreso").value = fecha_ingreso || '';

    // 🔥 Solo en modo EDICIÓN (cuando hay id_usuario)
    if (id_usuario) {
        const claveInput = document.getElementById("id_clave");
        claveInput.value = "";
        claveInput.placeholder = "Dejar en blanco para no cambiar";

        const msg = document.getElementById("mensajeSeguridad");
        msg.textContent = "La clave actual no se muestra por seguridad.";
        msg.style.color = "gray";
    }

    const tituloModal = document.querySelector("#modalUsuario .modal-title");
    tituloModal.textContent = id_usuario ? "Editar usuario" : "Nuevo usuario";

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

