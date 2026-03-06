window.addEventListener('load', () => {
    const ced = document.getElementById('cedula');
    const cla = document.getElementById('clave');

    if (ced) ced.value = '';
    if (cla) cla.value = '';
});



// Limpiar campos si venimos del logout
if (new URLSearchParams(window.location.search).has('clear')) {
    const ced = document.getElementById('cedula');
    const cla = document.getElementById('clave');

    if (ced) ced.value = '';
    if (cla) cla.value = '';
}

function validarcaracter(cadena){
    var regex = /^[0-9aA-zZàèìòùÁÉÍÓÚ_.-]+$/;
    return regex.test(cadena);
  }
  
  function validarnumero(numero){
    var regex = /^[0-9]+$/;
    return regex.test(numero);
  }
  
  function validar_form_login(opc) {
    var formulario = document.getElementById('formLogin');
    var Data = new FormData(formulario);
    let isValid = true;
  
    for (var [key, valor] of Data.entries()) {
      switch (key) {
        case 'cedula_usuario':
          if (!validarnumero(valor)) {
            alert("La cédula solo debe contener números");
            isValid = false;
          }
          break;
        case 'clave':
          if (!validarcaracter(valor)) {
            alert("La clave no debe tener caracteres especiales diferentes a ( _ . - )");
            isValid = false;
          }
          break;
      }
      if (!isValid) break;
    }
  
    if (isValid) {

        if(opc==1)
      loginUsuario();
    }
  }
  
  async function loginUsuario() {
    let datosLogin = capturarValoresFormulario('formLogin');

    // Cargar JSON externo
    const resp = await microApi('views/js/datos_empleado.json');
    let pin = resp.pin || null;

    // Añadir el JSON como string al formData
    datosLogin.append("extra", JSON.stringify({ pin: pin }));

    // Llamar al servicio
    var respuesta = await microApi('controlador/?login', datosLogin);

    if (respuesta.success) {
        // Guardar datos en sessionStorage
        sessionStorage.setItem("id_usuario", respuesta.id_usuario);
        sessionStorage.setItem("cedula_usuario", respuesta.cedula);
        sessionStorage.setItem("rol_id", respuesta.rol_id);
        // Redirigir a la vista principal
        window.location.href = "index.php";
    } else {
        if (respuesta.type === "inactive") {
            Swal.fire({
                title: 'Usuario inactivo',
                text: respuesta.message,
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
        } else {
            alert(respuesta.message);
        }
    }
}

  function pa(cad){
    document.getElementById('id_clave').value = MD5(cad);
}

function valorFormUsuario(cl='',ced=''){
   
    document.getElementById('clave').value = cl;
    document.getElementById('cedula_usuario').value = ced;
    
}