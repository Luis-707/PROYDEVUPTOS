//Buscador con filtrado de cedula de uno en uno funcional
/*
$(document).ready(function() {
    async function obtenerDatosPorCedula(cedula) {
        try {
          const resp = await microApi('views/js/datos_empleado.json');
          if (!Array.isArray(resp) || resp.length === 0 || !resp[0].data) {
            console.error('JSON con formato inesperado');
            return null;
          }
          const datos = resp[0].data;
          const cedulaBusqueda = cedula.toLowerCase();
      
          // Buscar coincidencia exacta (convertida a minúsculas para no distinguir mayúsculas)
          const coincidencia = datos.find(emp => emp.pin && emp.pin.toLowerCase() === cedulaBusqueda);
      
          return coincidencia || null; // Devuelve el objeto coincidencia o null si no hay
        } catch (error) {
          console.error('Error al buscar datos:', error);
          return null;
        }
      }
      
  
    // Función para llenar los campos del formulario con los datos encontrados
    function llenarFormulario(datos) {
      if (datos && typeof datos === 'object') {
        $('#id_cedula_usuario').val(datos.pin || '');
        $('#fullname_input').val(datos.fullname || '');
        $('#type_str_input').val(Array.isArray(datos.type_str) ? datos.type_str.join(', ') : (datos.type_str || ''));
        $('#additional_input').val(datos.additional || '');
      } else {
        $('#fullname_input').val('');
        $('#type_str_input').val('');
        $('#additional_input').val('');
      }
    }
  
    // Crear y agregar botón junto al input de búsqueda
    const $inputDiv = $('#id_cedula_usuario').parent();
    const $buscarBtn = $('<button type="button" class="btn btn-primary ms-2">Buscar</button>');
    $inputDiv.append($buscarBtn);
  
    // Evento click botón que ejecuta la búsqueda por cédula y llena el formulario
    $buscarBtn.on('click', async function() {
      const valorCedula = $('#id_cedula_usuario').val().trim();
      if (valorCedula.length > 0) {
        const datos = await obtenerDatosPorCedula(valorCedula);
        llenarFormulario(datos);
      } else {
        llenarFormulario(null);
      }
    });
  
  });

  $('#id_cedula_usuario').on('keypress', function(event) {
    const charCode = event.which ? event.which : event.keyCode;
    if (
      (charCode < 48 || charCode > 57) && // No es número
      charCode !== 8 && // No es backspace
      charCode !== 37 && // No es flecha izquierda
      charCode !== 39 // No es flecha derecha
    ) {
      event.preventDefault();
    }
  });
  */

  //Codigo funcional con error de eventos dinamicos
  /*$(document).ready(function() {
    async function obtenerDatosPorCedula(cedula) {
      try {
        const resp = await microApi('views/js/datos_empleado.json');
        if (!Array.isArray(resp) || resp.length === 0 || !resp[0].data) {
          console.error('JSON con formato inesperado');
          return null;
        }
        const datos = resp[0].data;
        const cedulaBusqueda = cedula.toLowerCase();
  
        // Buscar coincidencia exacta (convertida a minúsculas para no distinguir mayúsculas)
        const coincidencia = datos.find(emp => emp.pin && emp.pin.toLowerCase() === cedulaBusqueda);
  
        return coincidencia || null; // Devuelve el objeto coincidencia o null si no hay
      } catch (error) {
        console.error('Error al buscar datos:', error);
        return null;
      }
    }
  
    // Función para llenar los campos del formulario con los datos encontrados
    function llenarFormulario(datos) {
      if (datos && typeof datos === 'object') {
        $('#id_cedula_usuario').val(datos.pin || '');
        $('#fullname_input').val(datos.fullname || '');
        $('#type_str_input').val(Array.isArray(datos.type_str) ? datos.type_str.join(', ') : (datos.type_str || ''));
        $('#additional_input').val(datos.additional || '');
      } else {
        $('#fullname_input').val('');
        $('#type_str_input').val('');
        $('#additional_input').val('');
      }
    }
  
    // Evento click del botón buscar
    $('#btn_buscar_cedula').off('click').on('click', async function() {
      const valorCedula = $('#id_cedula_usuario').val().trim();
      if (valorCedula.length > 0) {
        const datos = await obtenerDatosPorCedula(valorCedula);
        llenarFormulario(datos);
      } else {
        llenarFormulario(null);
      }
    });
  
    // Validación para que solo se puedan ingresar números en el input cédula
    $('#id_cedula_usuario').off('keypress').on('keypress', function(event) {
      const charCode = event.which ? event.which : event.keyCode;
      if (
        (charCode < 48 || charCode > 57) && // No es número
        charCode !== 8 && // No es backspace
        charCode !== 37 && // No es flecha izquierda
        charCode !== 39 // No es flecha derecha
      ) {
        event.preventDefault();
      }
    });
  });
  
  //const resp = await microApi(`https://api.uptos.edu.ve/1.7.7/directory/search_person.json?pin=${cedula}&token=123`);
  */
  

  // Función para obtener datos por cédula
async function obtenerDatosPorCedula(cedula) {
  try {
    const resp = await microApi('views/js/datos_empleado.json');
    if (!Array.isArray(resp) || resp.length === 0 || !resp[0].data) {
      console.error('JSON con formato inesperado');
      return null;
    }
    const datos = resp[0].data;
    const cedulaBusqueda = cedula.toLowerCase();

    // Buscar coincidencia exacta
    const coincidencia = datos.find(emp => emp.pin && emp.pin.toLowerCase() === cedulaBusqueda);

    return coincidencia || null;
  } catch (error) {
    console.error('Error al buscar datos:', error);
    return null;
  }
}

// Función para llenar el formulario
function llenarFormulario(datos) {

  let opcDetener = ["Obrero", "Docente"];
  let opcAdministrativo = ["Administrativo"];

  if (view !== "" && view === "gestion_evaluados") {
    if (datos && Array.isArray(datos.type_str)) {
      const tieneOpcDetener = datos.type_str.some(tipo => opcDetener.includes(tipo));
      const tieneAdministrativo = datos.type_str.includes("Administrativo");
      const soloEstudiante = datos.type_str.length === 1 && datos.type_str[0] === "Estudiante";

      // Detiene si solo Estudiante
      if (soloEstudiante) {
        Swal.fire({
          icon: 'warning',
          title: 'Atención',
          text: `La cédula ingresada corresponde solo a un Estudiante.`,
          confirmButtonText: 'Aceptar'
        });
        return false;
      }
      
      // Detiene si contiene Obrero o Docente (incluso con otros valores)
      if (tieneOpcDetener) {
        Swal.fire({
          icon: 'warning',
          title: 'Atención',
          text: `La cédula ingresada corresponde a un tipo no permitido: ${datos.type_str.join(', ')}.`,
          confirmButtonText: 'Aceptar'
        });
        return false;
      }
    }
  }

  
  if (view !== "" && view === "usuarios") {
    if (datos && Array.isArray(datos.type_str)) {
      const tieneObrero = datos.type_str.includes("Obrero");
      const tieneEstudiante = datos.type_str.includes("Estudiante");
      const tieneAdministrativo = datos.type_str.includes("Administrativo");
      const tieneDocente = datos.type_str.includes("Docente");

      // Detener si tiene Obrero o Estudiante
      if (tieneObrero || tieneEstudiante) {
        Swal.fire({
          icon: 'warning',
          title: 'Atención',
          text: `La cédula ingresada corresponde a un tipo no permitido: ${datos.type_str.join(', ')}.`,
          confirmButtonText: 'Aceptar'
        });
        return false;
      }

      // No detener si tiene Administrativo (solo o junto con Docente)
      if (tieneAdministrativo) {
        // Se ejecuta normalmente
      } else {
        // En teoría no llega aquí si solo hay Obrero o Estudiante porque se detiene arriba
      }
    }
  }



  if (datos && typeof datos === 'object') {
    $('#id_cedula_usuario').val(datos.pin || '');
    $('#fullname_input').val(datos.fullname || '');
    $('#type_str_input').val(Array.isArray(datos.type_str) ? datos.type_str.join(', ') : (datos.type_str || ''));
    $('#additional_input').val(datos.additional || '');
  } else {
    $('#fullname_input').val('');
    $('#type_str_input').val('');
    $('#additional_input').val('');
  }
}
