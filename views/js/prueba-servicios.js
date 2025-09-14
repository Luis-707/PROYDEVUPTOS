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
  function validarcorreo(cadena){
    var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return regex.test(cadena);
  }

     
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

            case 'passw':
                    if (!validarcaracter(valor)) {
                        alert("La clave no debe tener caracteres Especiales diferentes a ( _  .  -  ) ");
                        isValid = false; // Marca como inválido
                    }
            break;
            case 'cedula':
                if (!validarnumero(valor)) {
                    alert("La cedula solo debe contener numeros ");
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
            console.log("Problema con el servicio");
    }
}
async function guardarUsuario(){

            // antes de capturar los valores del formulario debes validarlos
        let datosPersona = capturarValoresFormulario('formulario_usuario');

        var resp = await microApi('controlador/?g_user',datosPersona);
             // limpia formulario
            if (resp.includes(' Ya Existe')) {
                alert(resp);
            }else{
                valorFormUsuarioPrueba();
                listarUsuarioPrueba();
                alert('La Prueba se Guardo con Exito');
            }
  
}

async function listarUsuario(){
    var resp = await microApi('controlador/?l_user');
    listarTablaUsuariosPruebas(resp);
}

async function buscarUsuario(cod){
    
    let dato = capturarValoresFormulario('formulario_usuario',cod);

    var resp = await microApi('controlador/?b_user',dato);
   
  return resp;
   
}

function listarTablaUsuarios(datos) {
    const tbody = document.querySelector("#tabla-usuarios tbody");

    // Limpiar el contenido actual del tbody
    tbody.innerHTML = "";
    let html = "";

    // Iterar sobre los datos usando un bucle for
    for (let i = 0; i < datos.length; i++) {
        const grupo = datos[i]; // Acceder al primer nivel del arreglo

        for (let j = 0; j < grupo.length; j++) {
            const item = grupo[j]; // Acceder al segundo nivel del arreglo
            // Agregar celdas para cada campo
            html += '<tr>'; // Inicia una nueva fila
            html += '<td>' + (item.clave ? "******" : "") + '</td>'; // Ocultar clave por seguridad
            html += '<td>' + item.cedula_usuario + '</td>';

            // Botón para eliminar usuario
            let eliminarFila = "eliminarUsuarioPrueba('" + item.cedula_usuario + "')";
            html += '<td onclick="' + eliminarFila + '"><img src="img/iconos/eliminar.png" style="cursor:pointer;width: 22px;"></td>';

            html += '</tr>'; // Cierra fila
        }
    }

    // Agregar las filas generadas al tbody
    tbody.innerHTML = html;
}

async function eliminarUsuario(cod){
    if(confirm("Deseas eliminar este Usuario"))
    {
        let dato = capturarValoresFormulario('formulario_usuario',cod);

       var resp = await microApi('controlador/?e_user',dato);
    
        listarUsuarioPrueba();
    }else{
       
        valorFormUsuarioPrueba(); // limpia formulario
    }
}

function pa(cad){
    document.getElementById('id_clave').value = MD5(cad);
}

function valorFormUsuario(cl='',ced=''){
    document.getElementById('id_clave').value = cl;
    document.getElementById('id_cedula_usuario').value = ced;
    
}