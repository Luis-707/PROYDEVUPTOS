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

            case 'login':
                if (!validarcaracter(valor)) {
                    alert("El loguin no debe tener caracteres Especiales diferentes a ( _  .  -  )");
                    isValid = false; // Marca como inválido
                }
                break;
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

            case 'nombres':
                if (!validarCadena(valor)) {
                    alert("El nombre solo debe contener letras y espacios. ");
                    isValid = false; // Marca como inválido
                }
                break;

            case 'apellidos':
                if (!validarCadena(valor)) {
                    alert("El apellido solo debe contener letras y espacios. ");
                    isValid = false; // Marca como inválido
                }
                break;
               case 'correo':
                    if (!validarcorreo(valor)) {
                        alert("El Correo no es valido");
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

        var resp = await microApi('controlador/?g_usuario',datosPersona);
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
    var resp = await microApi('controlador/?l_usuario');
    listarTablaUsuarios(resp);
}



async function buscarUsuario(cod){
    
    let dato = capturarValoresFormulario('formulario_usuario',cod);

    var resp = await microApi('controlador/?b_usuario',dato);
   
  return resp;
   
}
/*function listarTablaUsuarios(datos) {
    const tbody = document.querySelector("#tabla-usuarios tbody");

    // Limpiar el contenido actual del tbody
    tbody.innerHTML = "";
    let html = "";
    // Iterar sobre los datos usando un bucle for
    for (let i = 0; i < datos.length; i++) {
        const grupo = datos[i]; // Acceder al primer nivel del arreglo

        for (let j = 0; j < grupo.length; j++) {
            const item = grupo[j]; // Acceder al segundo nivel del arreglo
            let eventoFila = "valorFormUsuario('"+item.usuario+"','"+item.clave+"','"+item.correo_usu+"')"; 
            html = html+'<tr onclick = "'+eventoFila+'">'; // crear fila

            html = html+'<td>'+item.usuario+'</td>';
            html = html+'<td>'+item.clave+'</td>';
            html = html+'<td>'+item.correo_usu+'</td>';
            let eliminarFila = "eliminarUsuario('"+item.usuario+"')"
            html = html+'<td onclick = "'+eliminarFila+'"><img src="img/iconos/eliminar.png" style="cursor:pointer;width: 22px;"></td>';

            html = html+'</tr>'; // cierra fila
            // Agregar la fila al tbody
        }
    }
    tbody.innerHTML = html;
} */
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
            let eventoFila = "valorFormUsuario('" + 
                item.usuario + "','" + 
                item.clave + "','" + 
                item.cedula_usu + "','" + 
                item.nombres_usu + "','" + 
                item.apellidos_usu + "','" + 
                item.correo_usu + "','" + 
                item.foto_usu + "','" + 
                item.estatus_usu + "')"; 

            html += '<tr ondblclick="' + eventoFila + '">'; // Crear fila

            // Agregar celdas para cada campo
            html += '<td>' + item.usuario + '</td>';
            html += '<td>' + (item.clave ? "******" : "") + '</td>'; // Ocultar clave por seguridad
            html += '<td>' + item.cedula_usu + '</td>';
            html += '<td>' + item.nombres_usu + '</td>';
            html += '<td>' + item.apellidos_usu + '</td>';
            html += '<td>' + item.correo_usu + '</td>';
            html += '<td><img src="' + item.foto_usu + '" alt="Foto" style="cursor:pointer;width: 50px;"></td>';
            html += '<td>' + item.estatus_usu + '</td>';

            // Botón para eliminar usuario
            let eliminarFila = "eliminarUsuario('" + item.usuario + "')";
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

       var resp = await microApi('controlador/?e_usuario',dato);
    
        listarUsuario();
    }else{
       
        valorFormUsuario(); // limpia formulario
    }
}

async function actualizarUsuario(){
    
    // antes de capturar los valores del formulario debes validarlos
    let datosPersona = capturarValoresFormulario('formulario_usuario');

    var resp = await microApi('controlador/?a_usuario',datosPersona);
    valorFormUsuario(); // limpia formulario
    listarTablaUsuarios(resp);
    alert('El Usuario se actualizo con Exito');
    
}

function pa(cad){
    document.getElementById('id_clave').value = MD5(cad);
}

function valorFormUsuario(login='',cl='',ced='',nomb='',ape='',mail='',fot='',stat=''){
    document.getElementById('id_login').value = login;
    document.getElementById('id_clave').value = cl;
    document.getElementById('id_cedula').value = ced;
    document.getElementById('id_nombres').value = nomb;
    document.getElementById('id_apellidos').value =ape;
    document.getElementById('id_mail').value = mail;
    document.getElementById('id_foto').value = fot;
    document.getElementById('id_estatus').value =stat;
    
}
