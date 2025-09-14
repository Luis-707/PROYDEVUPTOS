// Función para buscar y cargar datos al formulario
/*async function buscarYMostrarDatos(valorBusqueda) {
    try {
        // Consultar JSON usando microApi
        const resp = await microApi('views/js/datos_empleado.json');

        // Suponiendo que resp es un objeto JSON parseado
        if (resp.length == 0 || !resp[0].data) {
            console.error('JSON con formato inesperado');
            return;
        }

        // Buscar coincidencias filtrando por pin, fullname, type_str o additional
        const coincidencias = resp.data.filter(emp => {
            const busqueda = valorBusqueda.toLowerCase();
            return (
                emp.pin.toLowerCase().includes(busqueda) ||
                emp.fullname.toLowerCase().includes(busqueda) ||
                emp.type_str.some(tipo => tipo.toLowerCase().includes(busqueda)) ||
                emp.additional.toLowerCase().includes(busqueda)
            );
        });

        if (coincidencias.length > 0) {
            const primerMatch = coincidencias[0];

            // Asignar valores a los inputs
            document.getElementById('id_cedula_usuario').value = primerMatch.pin || '';
            document.getElementById('fullname_input').value = primerMatch.fullname || '';
            document.getElementById('type_str_input').value = (primerMatch.type_str || []).join(', ') || '';
            document.getElementById('additional_input').value = primerMatch.additional || '';
        } else {
            // Limpiar los campos si no hay coincidencias
            document.getElementById('id_cedula_usuario').value = '';
            document.getElementById('fullname_input').value = '';
            document.getElementById('type_str_input').value = '';
            document.getElementById('additional_input').value = '';
        }
    } catch (error) {
        console.error('Error al buscar datos:', error);
    }
}

// Evento para el input search
document.getElementById('search_input').addEventListener('input', (e) => {
    const valor = e.target.value.trim();
    if (valor.length > 0) {
        buscarYMostrarDatos(valor);
    } else {
        // Limpiar campos si no hay texto en búsqueda
        document.getElementById('id_cedula_usuario').value = '';
        document.getElementById('fullname_input').value = '';
        document.getElementById('type_str_input').value = '';
        document.getElementById('additional_input').value = '';
    }
});*/

// Función asíncrona que trae y filtra datos del JSON según valor de búsqueda
/*async function obtenerDatosJSON(valorBusqueda) {
    try {
        const resp = await microApi('views/js/datos_empleado.json');
        console.log(resp);
        if (resp.length == 0 || !resp[0].data) {
            console.error('JSON con formato inesperado');
            return null;
        }

        const busqueda = valorBusqueda.toLowerCase();
        const coincidencias = resp.data.filter(emp =>
            emp.pin.toLowerCase().includes(busqueda) ||
            emp.fullname.toLowerCase().includes(busqueda) ||
            emp.type_str.some(tipo => tipo.toLowerCase().includes(busqueda)) ||
            emp.additional.toLowerCase().includes(busqueda)
        );

        // Retorna el primer resultado o null si no hay coincidencias
        return coincidencias.length > 0 ? coincidencias[0] : null;

    } catch (error) {
        console.error('Error al buscar datos:', error);
        return null;
    }
}


// Función normal que recibe datos y los coloca en el formulario
function llenarFormulario(datos) {
    if (datos) {
        document.getElementById('id_cedula_usuario').value = datos.pin || '';
        document.getElementById('fullname_input').value = datos.fullname || '';
        document.getElementById('type_str_input').value = (datos.type_str || []).join(', ') || '';
        document.getElementById('additional_input').value = datos.additional || '';
    } else {
        // Limpiar campos si no hay datos
        document.getElementById('id_cedula_usuario').value = '';
        document.getElementById('fullname_input').value = '';
        document.getElementById('type_str_input').value = '';
        document.getElementById('additional_input').value = '';
    }
}


// Evento para el input search
document.getElementById('search_input').addEventListener('input', async (e) => {
    const valor = e.target.value.trim();
    if (valor.length > 0) {
        const datos = await obtenerDatosJSON(valor);
        llenarFormulario(datos);
    } else {
        llenarFormulario(null);
    }
});
*/

// Función para obtener y filtrar datos del JSON
/*async function obtenerDatosJSON(valorBusqueda) {
    try {
        const resp = await microApi('views/js/datos_empleado.json');
        
        if (!Array.isArray(resp) || resp.length === 0 || !resp[0].data) {
            console.error('JSON con formato inesperado');
            return null;
        }

        const datos = resp.data;
        const busqueda = valorBusqueda.toLowerCase();

        const coincidencias = datos.filter(emp => {
            return (
                emp.pin.toLowerCase().includes(busqueda) ||
                emp.fullname.toLowerCase().includes(busqueda) ||
                emp.type_str.some(tipo => tipo.toLowerCase().includes(busqueda)) ||
                emp.additional.toLowerCase().includes(busqueda)
            );
        });

        return coincidencias.length > 0 ? coincidencias[0] : null;

    } catch (error) {
        console.error('Error al buscar datos:', error);
        return null;
    }
}

// Función para llenar los campos del formulario con los datos encontrados
function llenarFormulario(datos) {
    // Verificar que datos no sea undefined, null ni vacío
    if (datos && typeof datos === 'object') {
        document.getElementById('id_cedula_usuario').value = datos.pin || '';
        document.getElementById('fullname_input').value = datos.fullname || '';
        document.getElementById('type_str_input').value = (datos.type_str || []).join(', ') || '';
        document.getElementById('additional_input').value = datos.additional || '';
    } else {
        // Limpiar campos si no hay datos
        document.getElementById('id_cedula_usuario').value = '';
        document.getElementById('fullname_input').value = '';
        document.getElementById('type_str_input').value = '';
        document.getElementById('additional_input').value = '';
    }
}

// Asignación del evento input a search_input solo cuando el formulario esté visible
document.getElementById('search_input').addEventListener('input', async (e) => {
    const valor = e.target.value.trim();
    if (valor.length > 0) {
        const datos = await obtenerDatosJSON(valor);
        llenarFormulario(datos);
    } else {
        llenarFormulario(null);
    }
});*/

$(document).ready(function() {
    // Función para obtener y filtrar datos del JSON
    async function obtenerDatosJSON(valorBusqueda) {
      try {
        const resp = await microApi('views/js/datos_empleado.json');
        
        // Acceder a la matriz de datos dentro del primer elemento
        if (!Array.isArray(resp) || resp.length === 0 || !resp[0].data) {
          console.error('JSON con formato inesperado');
          return null;
        }
        
        const datos = resp[0].data;  // CORRECTO acceso aquí
        const busqueda = valorBusqueda.toLowerCase();
        
        const coincidencias = datos.filter(emp => {
          return (
            (emp.pin && emp.pin.toLowerCase().includes(busqueda)) ||
            (emp.fullname && emp.fullname.toLowerCase().includes(busqueda)) ||
            (Array.isArray(emp.type_str) && emp.type_str.some(tipo => tipo.toLowerCase().includes(busqueda))) ||
            (emp.additional && emp.additional.toLowerCase().includes(busqueda))
          );
        });
        
        return coincidencias.length > 0 ? coincidencias[0] : null;
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
        $('#id_cedula_usuario').val('');
        $('#fullname_input').val('');
        $('#type_str_input').val('');
        $('#additional_input').val('');
      }
    }
  
    // Asignar evento de búsqueda al input
    $('#search_input').on('input', async function() {
      const valor = $(this).val().trim();
      if (valor.length > 0) {
        const datos = await obtenerDatosJSON(valor);
        llenarFormulario(datos);
      } else {
        llenarFormulario(null);
      }
    });

  });
  