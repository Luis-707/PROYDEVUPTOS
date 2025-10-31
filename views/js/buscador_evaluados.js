// Función para obtener datos por cédula (igual que antes)
async function obtenerDatosPorCedulaEvaluado(cedula) {
    try {
      const resp = await microApi('views/js/datos_empleado.json');
      if (!Array.isArray(resp) || resp.length === 0 || !resp[0].data) {
        console.error('JSON con formato inesperado');
        return null;
      }
      const datos = resp[0].data;
      const cedulaBusqueda = cedula.toLowerCase();
  
      // Buscar coincidencia exacta
      const coincidencia = datos.find(emp => 
        emp.pin && emp.pin.toLowerCase() === cedulaBusqueda
      );
  
      return coincidencia || null;
    } catch (error) {
      console.error('Error al buscar datos:', error);
      return null;
    }
  }
  
  // Función para llenar el formulario de evaluados
  function llenarFormularioEvaluado(datos) {
    if (datos && typeof datos === 'object') {
      $('#id_cedula_evaluado').val(datos.pin || '');
      $('#fullname_eval').val(datos.fullname || '');
      $('#type_str_eval').val(
        Array.isArray(datos.type_str) ? datos.type_str.join(', ') : (datos.type_str || '')
      );
      $('#additional_eval').val(datos.additional || '');
    } else {
      $('#fullname_eval').val('');
      $('#type_str_eval').val('');
      $('#additional_eval').val('');
    }

}