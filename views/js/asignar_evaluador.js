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

   
function validar_form_asignar(opc) {

   
  // Obtener el formulario
  var formulario = document.getElementById('form_asignar_supervisores');
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
              case 'id_supervisor':
                if (valor.trim() === "") {
                    alert("Debe seleccionar un supervisor.");
                    isValid = false;
                }
                break;

            case 'evaluadores[]':
                // No validamos aquí uno por uno, lo haremos después del bucle
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
          guardarAsignacion();  
    }
     
        
}

//Select de usuarios Supervisores

async function listarSupervisor() {
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
  
      SelectSupervisor(respSupervisores, empleados);
    } catch (err) {
      console.error('La petición falló:', err);
    }
  }
  
  function SelectSupervisor(datos, empleados) {
    const select = document.getElementById('id_supervisor');
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

//Check de evaluadores

async function listarCheckEvaluadores() {
  try {
      const respEmpleados = await microApi('views/js/datos_empleado.json');

      let empleados = [];
      if (Array.isArray(respEmpleados)) {
          empleados = respEmpleados[0]?.data || respEmpleados[0] || [];
      } else if (respEmpleados?.data) {
          empleados = respEmpleados.data;
      } else {
          empleados = respEmpleados;
      }

      const respEvaluadores = await microApi('controlador/?l_evaluadores_check');
      if (typeof respEvaluadores === 'string') {
          console.error('Error al listar evaluadores:', respEvaluadores);
          return;
      }

      const registros = Array.isArray(respEvaluadores[0]) ? respEvaluadores.flat() : respEvaluadores;

      const contenedor = document.getElementById('listaEvaluadores');
      if (!contenedor) return;
      contenedor.innerHTML = '';

      registros.forEach(user => {
          const empleado = empleados.find(emp => emp.pin_str === user.cedula_usuario || emp.pin === user.cedula_usuario);
          const fullname = empleado ? empleado.fullname : user.cedula_usuario;

          // 🔹 Asegurar que el ID sea numérico y válido
          const idEval = parseInt(user.id_evaluador, 10);
          if (isNaN(idEval) || idEval <= 0) {
              console.warn(`Evaluador con ID inválido detectado:`, user);
              return; // Saltar este registro
          }

          const checkbox = document.createElement('input');
          checkbox.type = 'checkbox';
          checkbox.id = `eval_${idEval}`;
          checkbox.name = 'evaluadores[]'; // Clave para que FormData los capture como array
          checkbox.value = idEval; // 🔹 Siempre numérico

          const label = document.createElement('label');
          label.htmlFor = checkbox.id;
          label.textContent = fullname;

          const div = document.createElement('div');
          div.appendChild(checkbox);
          div.appendChild(label);

          contenedor.appendChild(div);
      });
  } catch (err) {
      console.error('Error al listar evaluadores:', err);
  }
}

async function guardarAsignacion() {
  // Captura todos los campos del formulario, incluyendo evaluadores[]
  let datosAsignacion = capturarValoresFormulario('form_asignar_supervisores');

  let idSupervisor = datosAsignacion.get('id_supervisor');
  let evaluadoresSeleccionados = datosAsignacion.getAll('evaluadores[]'); 

  if (!idSupervisor) {
      alert("Debe seleccionar un supervisor.");
      return;
  }

  if (evaluadoresSeleccionados.length === 0) {
      alert("Debe seleccionar al menos un evaluador.");
      return;
  }

  // 🔹 Depuración opcional: ver qué se envía
  console.group("DEBUG FormData enviado");
  for (let [clave, valor] of datosAsignacion.entries()) {
      console.log(clave, "=>", valor);
  }
  console.groupEnd();

  // Enviar datos al backend tal cual
  try {
      const data = await microApi('controlador/?asignar_supervisores', datosAsignacion);
      valorFormAsignar(); // Limpiar el formulario
      alert(data.message);
  } catch (err) {
      console.error(err);
      alert("Error al guardar asignación");
  }
}

function valorFormAsignar(supervisor='', evaluadores='') {
  
document.getElementById('id_supervisor').value = supervisor;
document.getElementById('listaEvaluadores').value = evaluadores;

}