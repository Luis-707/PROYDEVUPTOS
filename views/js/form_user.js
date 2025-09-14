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
async function guardarUsuario(){

            // antes de capturar los valores del formulario debes validarlos
        let datosPersona = capturarValoresFormulario('formulario_usuario');

         // Obtener el valor del select 'id_rol_sistema'
         let idRol = document.getElementById('id_rol_sistema').value;
         // Agregarlo a los datos que se enviarán
         datosPersona.append('rol_id', idRol);
        
        var resp = await microApi('controlador/?g_user',datosPersona);
             // limpia formulario
            if (resp.includes(' Ya Exite')) {
                alert(resp);
            }else{
                valorFormUsuario();
                listarUsuario();
                alert('El Usuario se Guardo con Exito');
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


function abrirModalEditar(cedula,clave, rolSistema) {
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
  return microApi('controlador/?listar_RolesSistema')
    .then(datos => rellenarSelect(datos, 'rol_modal'));
}

async function listarTablaUsuarios(datos) {
  const tbody = document.querySelector("#tabla-usuarios tbody");
  tbody.innerHTML = "";

  // Cargar JSON con datos de empleados
  var resp = await microApi('views/js/datos_empleado.json');
  const empleados = resp[0].data; // asumiendo estructura del JSON enviada

  // Cargar lista de roles desde la API (igual que listarRolesSistema)
  const rolesResp = await microApi('controlador/?listar_RolesSistema');
  // Normalizar arreglo de roles
  const rolesList = Array.isArray(rolesResp[0]) ? rolesResp.flat() : rolesResp;

  let html = "";

  for (let i = 0; i < datos.length; i++) {
      const grupo = datos[i];
      for (let j = 0; j < grupo.length; j++) {
          const item = grupo[j];
          
          // Buscar empleado cuyo pin coincida con cedula_usuario
          let empleado = empleados.find(emp => emp.pin_str === item.cedula_usuario || emp.pin === item.cedula_usuario);

          // Obtener fullname si existe el empleado
          let fullname = empleado ? empleado.fullname : "No encontrado";
          //let status = empleado ? empleado.status_str : "Desconocido";

        // Buscar texto del rol según rol_id
          const rolObj = rolesList.find(r => r.rol_id == item.rol_id || r.idrol == item.rol_id);
          const rolTexto = rolObj ? (rolObj.rol || rolObj.nombrerol || "Desconocido") : "Desconocido";


          html += '<tr>';
          html += '<td>' + (item.clave ? "******" : "") + '</td>';
          html += '<td>' + item.cedula_usuario + '</td>';
          // Mostrar fullname en vez de cédula o también se puede mostrar ambos si quieres
          html += '<td>' + fullname + '</td>';
          //html += '<td>' + status + '</td>'; // Nueva columna con estado
          html += '<td>' + rolTexto + '</td>'; // Nueva columna para Rol
          let eliminarFila = "eliminarUsuario('" + item.cedula_usuario + "')";
          html += '<td onclick="' + eliminarFila + '"><img src="img/iconos/eliminar.png" style="cursor:pointer;width: 22px;"></td>';

          let editarFila = `abrirModalEditar(
              '${item.cedula_usuario}',
              '${item.clave ? "******" : ""}',
              '${item.rol_id}'
          )`;
          html += '<td onclick="' + editarFila + '"><img src="img/iconos/actualizar.png" style="cursor:pointer;width:22px;margin-left:8px;" /></td>';
          html += '</tr>';
      }
  }

  tbody.innerHTML = html;
}

async function eliminarUsuario(cod){
    if(confirm("Deseas eliminar este Usuario"))
    {
        let dato = capturarValoresFormulario('formulario_usuario',cod);

       var resp = await microApi('controlador/?e_user',dato);
    
        listarUsuario();
    }else{
       
        valorFormUsuario(); // limpia formulario
    }
}

async function actualizarUsuario(){
    
    // antes de capturar los valores del formulario debes validarlos
    let datosPersona = capturarValoresFormulario('form-modal-editar');

     // Obtener el valor del select 'rol_modal'
     let NuevoRol = document.getElementById('rol_modal').value;
     // Agregarlo a los datos que se enviarán
     datosPersona.append('rol_id', NuevoRol);

    var resp = await microApi('controlador/?a_user',datosPersona);
    /*valorFormUsuario();*/// limpia formulario
    listarTablaUsuarios(resp);
    alert('El Usuario se actualizo con Exito');
    
}

function pa(cad){
    document.getElementById('id_clave').value = MD5(cad);
}

function valorFormUsuario(cl='',ced='',RolSis=''){
   
    document.getElementById('id_clave').value = cl;
    document.getElementById('id_cedula_usuario').value = ced;
    document.getElementById('id_RolSistema').value = RolSis;
    

    
}

  //Select de roles del sistema

async function listarRolesSistema() {
  try {
    // Llamas a la API que te devuelve los datos de jefes_superiores
    const resp = await microApi('controlador/?listar_RolesSistema');

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






