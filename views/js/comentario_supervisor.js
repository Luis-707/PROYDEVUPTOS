// =============================
// Validaciones básicas
// =============================
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

function Validar_form_comentario_supervisor(opc) {
    var formulario = document.getElementById('form_comentario_supervisor');
    var Data = new FormData(formulario);
    let isValid = true;

    console.log(Data);
  
    for (let [key, valor] of Data.entries()) {
        if (!valor) {
          console.warn(`⚠️ El campo ${key} está vacío`);
        }
    
        switch (key) {
          case 'comentario_supervisor':
            if (!validarCadena(valor)) {
              alert("El comentario del supervisor no debe tener caracteres Especiales diferentes a ( _  .  -  ) ");
              isValid = false; // Marca como inválido
            }
            break;
            

            default:
            // No hacer nada para otros campos
            break;
        }
      }
    
      if (isValid) {
        if (opc === 1) {
            guardarComentarioSupervisor();
          }
      }
    }
  
    
 
    async function guardarComentarioSupervisor(){
  
      // antes de capturar los valores del formulario debes validarlos
  let datosPersona = capturarValoresFormulario('form_comentario_supervisor');
  
    try {
      const resp = await microApi('controlador/?g_comentario_supervisor', datosPersona);
  
      if (typeof resp === 'string' && resp.includes(' No Existe')) {
        Swal.fire({
          icon: 'error',
          title: 'Supervisor no encontrado',
          text: resp
        });
      } else {
        valorFormComentarioSup();
  
        Swal.fire({
          icon: 'success',
          title: 'Comentario añadido',
          text: 'El comentario del supervisor se añadio con éxito'
        });
      }
    } catch (err) {
      console.error("Error actualizando supervisor:", err);
      Swal.fire({
        icon: 'error',
        title: 'Error inesperado',
        text: 'Ocurrió un error al actualizar el supervisor'
      });
    }

}
  
 
    function valorFormComentarioSup(ComentarioSuperv = "") {    

    document.getElementById("comentario_supervisor").value = ComentarioSuperv;
    
    }