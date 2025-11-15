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

   
function validar_formEvalAdmin(opc) {

   
  // Obtener el formulario
  var formulario = document.getElementById('formulario_EvalAdmin');
  //console.log(formulario);
  // Crear un objeto FormData
  var Data = new FormData(formulario);
  let isValid = true; // Variable para controlar si el formulario es válido
  console.log(Data);
  // Validar cada campo
  for (var [key, valor] of Data.entries()) {
      
      switch (key) {   
        
          case 'fecha_inicio':
              if (valor === "") {
                  alert("El campo Fecha de Inicio no puede estar vacío.");
                  isValid = false; // Marca como inválido
              }
              break;
          case 'fecha_cierre':
              if (valor === "") {
                  alert("El campo Fecha de Cierre no puede estar vacío.");
                  isValid = false; // Marca como inválido
              }
              break;    

        
          case 'periodo_evaluado':
              if (!validarcaracter(valor)) {
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
      if(opc==1){
          guardarPeriodoEvaluacion();  
      }
          
  }
}

function ajustarFechasPeriodo(periodo) {
  const inicioInput = document.getElementById('fecha_inicio');
  const cierreInput = document.getElementById('fecha_cierre');
  const year = new Date().getFullYear(); // año actual

  if (periodo === 'Enero-Junio') {
    inicioInput.value = `${year}-01-01`;
    cierreInput.value = `${year}-06-01`;
  } else if (periodo === 'Julio-Diciembre') {
    inicioInput.value = `${year}-07-01`;
    cierreInput.value = `${year}-12-01`;
  } else {
    // Si no hay periodo válido, limpiar los campos
    inicioInput.value = '';
    cierreInput.value = '';
  }
}



async function guardarPeriodoEvaluacion() {
  try {
    // Capturar valores del formulario
    let datos = capturarValoresFormulario('formulario_EvalAdmin');

    // Asegurarnos de que el id_evaluado se envía
    let idEvaluado = document.getElementById('id_evaluado').value;
    if (!idEvaluado) {
      Swal.fire({
        icon: 'warning',
        title: 'Falta seleccionar evaluado',
        text: 'Debe seleccionar un usuario evaluado antes de guardar.'
      });
      return;
    }
    datos.append('id_evaluado', idEvaluado);

    // Validar fechas y periodo antes de enviar
    const periodo = document.getElementById('periodo_evaluado').value;
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaCierre = document.getElementById('fecha_cierre').value;

    if (!validarFechasPeriodo(fechaInicio, fechaCierre, periodo)) {
      Swal.fire({
        icon: 'error',
        title: 'Fechas inválidas',
        text: 'Las fechas no corresponden al periodo seleccionado.'
      });
      return;
    }

    // Llamada al servicio PHP para guardar
    const resp = await microApi('controlador/?g_periodo', datos);

    // Validar respuesta
    if (resp.success) {
      Swal.fire({
        icon: 'success',
        title: 'Periodo guardado',
        text: resp.message
      });

      // Refrescar tabla y limpiar formulario
      listarEvalAdmin();
      valorFormEvalAdmin();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: resp.message
      });
    }
  } catch (err) {
    console.error("Error en guardarPeriodoEvaluacion:", err);
    Swal.fire({
      icon: 'error',
      title: 'Error inesperado',
      text: 'Ocurrió un error al guardar el periodo'
    });
  }
}

function validarFechasPeriodo(fechaInicio, fechaCierre, periodo) {
  const fi = (fechaInicio || '').trim();
  const fc = (fechaCierre || '').trim();

  if (periodo === "Enero-Junio" && fi.endsWith("-01-01") && fc.endsWith("-06-01")) {
    return true;
  }
  if (periodo === "Julio-Diciembre" && fi.endsWith("-07-01") && fc.endsWith("-12-01")) {
    return true;
  }
  return false;
}


async function listarEvalAdmin(){
    var resp = await microApi('controlador/?l_evalAdministrativos');
    listarTablaEvalAdmin(resp);
}  

async function listarTablaEvalAdmin(datos) {
    const tbody = document.querySelector("#tabla-EvalAdmin tbody");
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
      const additional = empleado ? empleado.additional || "" : "";
      const cargoTexto = item.cargo_evaluado || "Sin cargo";
      const periodoEvaluado = item.periodo_evaluado || "";
  
      html += `
        <tr>
          <td>${cedula}</td>
          <td>${fullname}</td>
          <td>${additional}</td>
          <td>${cargoTexto}</td>
          <td>${periodoEvaluado}</td>
          <td class="acciones">
            <div class="acciones-icons">
              <img src="img/iconos/actualizar.png" onclick="abrirModalEditarCargo(${item.id_usuario}, ${item.id_cargo_evaluado})" />
              <img src="img/iconos/eliminar.png" onclick="eliminarEvaluador(${item.id_usuario})" />
            </div>
          </td>
        </tr>
      `;
    });
  
    tbody.innerHTML = html;
  }

  function valorFormEvalAdmin(evaluado=''){
    // Asignar valores a los campos del formulario
    document.getElementById('id_evaluado').value = evaluado;  
    
  }

  async function listarEvaluadosAdmin() {
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
  
      // Obtener lista de evaluados desde API
      const respEvaluados = await microApi('controlador/?listar_datos');
      if (typeof respEvaluados === 'string') {
        console.error('Error al listar usuarios:', respEvaluados);
        return;
      }
  
      llenarSelectEvaluados(respEvaluados, empleados);
    } catch (err) {
      console.error('La petición falló:', err);
    }
  }
  
  function llenarSelectEvaluados(datos, empleados) {
    const select = document.getElementById('id_evaluado'); // 👈 este id deberías renombrarlo a 'id_evaluado'
    if (!select) return;
  
    select.innerHTML = '<option value="">Seleccione a un usuario</option>';
  
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
    const evaluados = registros.filter(item => item.rol === 'Evaluado');
  
    evaluados.forEach(item => {
      const empleado = empleados.find(emp => emp.pin_str === item.cedula_usuario || emp.pin === item.cedula_usuario);
      const fullname = empleado ? empleado.fullname : item.cedula_usuario;
  
      const opcion = document.createElement('option');
      opcion.value = item.id_evaluado;   // 👈 ahora usamos id_evaluado
      opcion.textContent = fullname;
      select.appendChild(opcion);
    });
  }
  