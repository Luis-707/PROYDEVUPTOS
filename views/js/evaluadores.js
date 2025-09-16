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

   
function validar_formEvaluador(opc) {

   
  // Obtener el formulario
  var formulario = document.getElementById('formulario_evaluador');
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
          guardarEvaluador();  
      else
      actualizarEvaluador();
          
  }
}
async function guardarEvaluador() {
  // Capturar valores del formulario
  let datosPersona = capturarValoresFormulario('formulario_evaluador');

  // Agregar id_cargo_evaluador
  let idCargoEval = document.getElementById('id_cargo_evaluador').value;
  datosPersona.append('id_cargo_evaluador', idCargoEval);

  // Agregar id_usuario
  let idUsuarioEval = document.getElementById('id_usuario').value;
  datosPersona.append('id_usuario', idUsuarioEval);

  try {
      // Llamada al servicio
      const resp = await microApi('controlador/?g_cargoevaluador', datosPersona);

      // Validar respuesta JSON
      if (!resp.success) {
          alert(resp.message);
      } else {
          valorFormEval();      // Limpia formulario
          listarEvaluadores();   // Refresca tabla
          alert(resp.message);
      }
  } catch (err) {
      console.error("Error en guardarEvaluador:", err);
      alert("Ocurrió un error al guardar el cargo");
  }
}

/*async function listarCargosEval(){
  var resp = await microApi('controlador/?l_cargos_evaluadores');
  listarTablaEvaluadores(resp);
}*/

async function listarEvaluadores(){
  var resp = await microApi('controlador/?l_evaluadores');
  listarTablaEvaluadores(resp);
}

async function buscarEvaluador(cod){
    
  let dato = capturarValoresFormulario('formulario_evaluador',cod);

  var resp = await microApi('controlador/?b_evaluador',dato);
 
return resp;
 
}

async function listarTablaEvaluadores(datos) {
  const tbody = document.querySelector("#tabla-evaluadores tbody");
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
      const cargoTexto = item.cargo_evaluador || "Sin cargo";

      html += `
          <tr>
              <td>${fullname}</td>
              <td>${cedula}</td>
              <td>${cargoTexto}</td>
              <td onclick="abrirModalEditarCargo(${item.id_usuario}, ${item.id_cargo_evaluador})">
                  <img src="img/iconos/actualizar.png" style="cursor:pointer;width: 22px;">
              </td>
              <td onclick="eliminarEvaluador(${item.id_usuario})">
                  <img src="img/iconos/eliminar.png" style="cursor:pointer;width: 22px;">
              </td>
          </tr>
      `;
  });

  tbody.innerHTML = html;
}
async function eliminarEvaluador(idUsuario) {
  if (confirm('¿Está seguro de eliminar este evaluador?')) {
      // Creamos el FormData con el id_usuario que espera el servicio
      const formData = new FormData();
      formData.append('id_usuario', idUsuario);

      try {
          const resp = await microApi('controlador/?e_evaluadores', formData);
          listarEvaluadores(); // refresca la tabla
          alert(resp);
      } catch (err) {
          console.error("Error eliminando evaluador:", err);
          alert("Ocurrió un error al eliminar el evaluador");
      }
  }
}

async function actualizarEvaluador(){

          // antes de capturar los valores del formulario debes validarlos
      let datosPersona = capturarValoresFormulario('form-modal-editar-cargo');

       /*// Obtener el valor del select 'id_cargo_evaluador'
       let idCargoEval = document.getElementById('id_cargo_evaluador').value;
       // Agregarlo a los datos que se enviarán
       datosPersona.append('id_cargo_evaluador', idCargoEval);
        // Obtener el valor del select 'id_usuario' 
        let idUsuarioEval = document.getElementById('id_usuario').value;
        // Agregarlo a los datos que se enviarán
        datosPersona.append('id_usuario', idUsuarioEval);*/
      
      var resp = await microApi('controlador/?a_evaluadores',datosPersona);
           // limpia formulario
          if (resp.includes(' No Existe')) {
              alert(resp);
          }else{
              valorFormEval();
              listarEvaluadores();
              alert('El Cargo se Actualizo con Exito');
          }

}

function valorFormEval(usuarioevaluador='',cargoeval=''){
  // Asignar valores a los campos del formulario
  document.getElementById('id_usuario').value = usuarioevaluador;
  document.getElementById('id_cargo_evaluador').value = cargoeval;
  

  
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
  
  async function listarUsuariosEvaluador() {
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
      const respEvaluadores = await microApi('controlador/?listar_evaluadores');
      if (typeof respEvaluadores === 'string') {
        console.error('Error al listar usuarios:', respEvaluadores);
        return;
      }
  
      llenarSelectEvaluador(respEvaluadores, empleados);
    } catch (err) {
      console.error('La petición falló:', err);
    }
  }
  
  function llenarSelectEvaluador(datos, empleados) {
    const select = document.getElementById('id_usuario');
    if (!select) return;
  
    select.innerHTML = '<option value="">Seleccione a un usuario</option>';
  
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
    const evaluadores = registros.filter(item => item.rol === 'Evaluador');
  
    evaluadores.forEach(item => {
      const empleado = empleados.find(emp => emp.pin_str === item.cedula_usuario || emp.pin === item.cedula_usuario);
      const fullname = empleado ? empleado.fullname : item.cedula_usuario;
  
      const opcion = document.createElement('option');
      opcion.value = item.id_usuario;
      opcion.textContent = fullname;
  
      select.appendChild(opcion);
    });
  }
  
//Select de cargos de evaluadores

async function listarCargosEvaluadores() {
  try {
    // Llamada a la API que devuelve cargos evaluadores
    const resp = await microApi('controlador/?l_cargos_evaluadores');

    if (typeof resp === 'string') {
      console.error('Error al listar cargos evaluadores:', resp);
      return;
    }

    llenarSelectCargosEvaluadores(resp);
  } catch (err) {
    console.error('La petición de cargos evaluadores falló:', err);
  }
}

function llenarSelectCargosEvaluadores(datos) {
  const select = document.getElementById('id_cargo_evaluador');
  if (!select) return;

  // Opción por defecto
  select.innerHTML = '<option value="">Seleccione a un cargo</option>';

  // Para manejar arrays anidados si los hubiera
  const registros = Array.isArray(datos[0]) ? datos.flat() : datos;

  // Se asume que datos son objetos con id_cargo_evaluador y cargo_evaluador
  registros.forEach(item => {
    const opcion = document.createElement('option');
    opcion.value = item.id_cargo_evaluador;   // id para cada option
    opcion.textContent = item.cargo_evaluador; // texto visible en el select

    select.appendChild(opcion);
  });
}

function abrirModalEditarCargo(idUsuario, idCargoActual) {
  // 1) Resetear el formulario del modal
  document.getElementById("form-modal-editar-cargo").reset();

  // 2) Guardar el id_usuario en un campo oculto
  document.getElementById("id_usuario_modal").value = idUsuario;

  // 3) Llenar el select de cargos y marcar el actual
  listarCargosEvaluadoresModal(idCargoActual);

  // 4) Mostrar el modal
  $("#modalEditarCargo").modal("show");
}




function rellenarSelectCargos(datos, idSelect, idCargoActual = null) {
  const sel = document.getElementById(idSelect);

  // Limpia y agrega la opción por defecto
  sel.innerHTML = '<option value="">-- Seleccione --</option>';

  // Aplana si es un array de arrays
  const flat = Array.isArray(datos[0]) ? datos.flat() : datos;

  // Recorre y agrega opciones
  flat.forEach(o => {
    const opt = document.createElement('option');
    opt.value = o.id_cargo_evaluador;   
    opt.textContent = o.cargo_evaluador; 
    if (idCargoActual && o.id_cargo_evaluador == idCargoActual) {
      opt.selected = true;
    }
    sel.appendChild(opt);
  });
}


function listarCargosEvaluadoresModal(idCargoActual) {
  return microApi('controlador/?l_cargos_evaluadores')
    .then(datos => rellenarSelectCargos(datos, 'cargo_modal', idCargoActual));
}