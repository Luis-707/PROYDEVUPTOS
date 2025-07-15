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
            valorFormUsuario(); // limpia formulario
            listarUsuario();
  
}

async function listarUsuario(){
    var resp = await microApi('controlador/?l_usuario');
    listarTablaUsuarios(resp);
}
async function buscarUsuario(cod){
    
    let dato = capturarValoresFormulario('formulario_usuario',cod);

    var resp = await microApi('controlador/?b_usuario',dato);
   // alert('resp '+resp);
   return resp;
   // listarUsuario();
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

            html += '<tr onclick="' + eventoFila + '">'; // Crear fila

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
    let dato = capturarValoresFormulario('formulario_usuario',cod);

    var resp = await microApi('controlador/?e_usuario',dato);
  
    listarUsuario();
}

async function actualizarUsuario(){
    
    // antes de capturar los valores del formulario debes validarlos
    let datosPersona = capturarValoresFormulario('formulario_usuario');

    var resp = await microApi('controlador/?a_usuario',datosPersona);
    valorFormUsuario(); // limpia formulario
    listarTablaUsuarios(resp);
    
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

function MD5(input) {
    if (typeof input !== "string") {
        throw new Error("El parámetro debe ser una cadena de texto.");
    }

    function rotateLeft(lValue, iShiftBits) {
        return (lValue << iShiftBits) | (lValue >>> (32 - iShiftBits));
    }

    function addUnsigned(lX, lY) {
        const lX4 = lX & 0x40000000;
        const lY4 = lY & 0x40000000;
        const lX8 = lX & 0x80000000;
        const lY8 = lY & 0x80000000;
        const lResult = (lX & 0x3fffffff) + (lY & 0x3fffffff);
        if (lX4 & lY4) return lResult ^ 0x80000000 ^ lX8 ^ lY8;
        if (lX4 | lY4) {
            if (lResult & 0x40000000) return lResult ^ 0xc0000000 ^ lX8 ^ lY8;
            else return lResult ^ 0x40000000 ^ lX8 ^ lY8;
        } else return lResult ^ lX8 ^ lY8;
    }

    function f(x, y, z) { return (x & y) | (~x & z); }
    function g(x, y, z) { return (x & z) | (y & ~z); }
    function h(x, y, z) { return x ^ y ^ z; }
    function i(x, y, z) { return y ^ (x | ~z); }

    function ff(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(f(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    }

    function gg(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(g(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    }

    function hh(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(h(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    }

    function ii(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(i(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
    }

    function convertToWordArray(input) {
        let lWordCount = Math.ceil(input.length / 4);
        const lMessageLength = input.length * 8;
        const lNumberOfWordsTemp1 = lWordCount + 1;
        const lNumberOfWordsTemp2 = Math.ceil(lNumberOfWordsTemp1 / 16) * 16;
        const lWordArray = Array(lNumberOfWordsTemp2 - 1);
        let lBytePosition = 0;
        let lByteCount = 0;
        while (lByteCount < input.length) {
            lWordCount = Math.floor(lByteCount / 4);
            lBytePosition = (lByteCount % 4) * 8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (input.charCodeAt(lByteCount) << lBytePosition));
            lByteCount++;
        }
        lWordCount = Math.floor(lByteCount / 4);
        lBytePosition = (lByteCount % 4) * 8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80 << lBytePosition);
        lWordArray[lNumberOfWordsTemp2 - 2] = lMessageLength & 0xffffffff;
        lWordArray[lNumberOfWordsTemp2 - 1] = (lMessageLength >> 32) & 0xffffffff;
        return lWordArray;
    }

    function wordToHex(lValue) {
        let wordToHexValue = "";
        let wordToHexValueTemp = "";
        let lByte;
        for (lByte = 0; lByte <= 3; lByte++) {
            wordToHexValueTemp = ((lValue >> (lByte * 8)) & 0xff).toString(16);
            wordToHexValue += (wordToHexValueTemp.length === 1) ? "0" + wordToHexValueTemp : wordToHexValueTemp;
        }
        return wordToHexValue;
    }

    let x = [];
    let k, AA, BB, CC, DD, a, b, c, d;
    const S11 = 7, S12 = 12, S13 = 17, S14 = 22;
    const S21 = 5, S22 = 9, S23 = 14, S24 = 20;
    const S31 = 4, S32 = 11, S33 = 16, S34 = 23;
    const S41 = 6, S42 = 10, S43 = 15, S44 = 21;

    x = convertToWordArray(input);

    a = 0x67452301; b = 0xefcdab89; c = 0x98badcfe; d = 0x10325476;

    for (k = 0; k < x.length; k += 16) {
        AA = a; BB = b; CC = c; DD = d;
        a = ff(a, b, c, d, x[k + 0], S11, 0xd76aa478);
        d = ff(d, a, b, c, x[k + 1], S12, 0xe8c7b756);
        c = ff(c, d, a, b, x[k + 2], S13, 0x242070db);
        b = ff(b, c, d, a, x[k + 3], S14, 0xc1bdceee);
        a = ff(a, b, c, d, x[k + 4], S11, 0xf57c0faf);
        d = ff(d, a, b, c, x[k + 5], S12, 0x4787c62a);
        c = ff(c, d, a, b, x[k + 6], S13, 0xa8304613);
        b = ff(b, c, d, a, x[k + 7], S14, 0xfd469501);
        a = ff(a, b, c, d, x[k + 8], S11, 0x698098d8);
        d = ff(d, a, b, c, x[k + 9], S12, 0x8b44f7af);
        c = ff(c, d, a, b, x[k + 10], S13, 0xffff5bb1);
        b = ff(b, c, d, a, x[k + 11], S14, 0x895cd7be);
        a = ff(a, b, c, d, x[k + 12], S11, 0x6b901122);
        d = ff(d, a, b, c, x[k + 13], S12, 0xfd987193);
        c = ff(c, d, a, b, x[k + 14], S13, 0xa679438e);
        b = ff(b, c, d, a, x[k + 15], S14, 0x49b40821);
        a = gg(a, b, c, d, x[k + 1], S21, 0xf61e2562);
        d = gg(d, a, b, c, x[k + 6], S22, 0xc040b340);
        c = gg(c, d, a, b, x[k + 11], S23, 0x265e5a51);
        b = gg(b, c, d, a, x[k + 0], S24, 0xe9b6c7aa);
        a = gg(a, b, c, d, x[k + 5], S21, 0xd62f105d);
        d = gg(d, a, b, c, x[k + 10], S22, 0x02441453);
        c = gg(c, d, a, b, x[k + 15], S23, 0xd8a1e681);
        b = gg(b, c, d, a, x[k + 4], S24, 0xe7d3fbc8);
        a = gg(a, b, c, d, x[k + 9], S21, 0x21e1cde6);
        d = gg(d, a, b, c, x[k + 14], S22, 0xc33707d6);
        c = gg(c, d, a, b, x[k + 3], S23, 0xf4d50d87);
        b = gg(b, c, d, a, x[k + 8], S24, 0x455a14ed);
        a = gg(a, b, c, d, x[k + 13], S21, 0xa9e3e905);
        d = gg(d, a, b, c, x[k + 2], S22, 0xfcefa3f8);
        c = gg(c, d, a, b, x[k + 7], S23, 0x676f02d9);
        b = gg(b, c, d, a, x[k + 12], S24, 0x8d2a4c8a);
        a = hh(a, b, c, d, x[k + 5], S31, 0xfffa3942);
        d = hh(d, a, b, c, x[k + 8], S32, 0x8771f681);
        c = hh(c, d, a, b, x[k + 11], S33, 0x6d9d6122);
        b = hh(b, c, d, a, x[k + 14], S34, 0xfde5380c);
        a = hh(a, b, c, d, x[k + 1], S31, 0xa4beea44);
        d = hh(d, a, b, c, x[k + 4], S32, 0x4bdecfa9);
        c = hh(c, d, a, b, x[k + 7], S33, 0xf6bb4b60);
        b = hh(b, c, d, a, x[k + 10], S34, 0xbebfbc70);
        a = hh(a, b, c, d, x[k + 13], S31, 0x289b7ec6);
        d = hh(d, a, b, c, x[k + 0], S32, 0xeaa127fa);
        c = hh(c, d, a, b, x[k + 3], S33, 0xd4ef3085);
        b = hh(b, c, d, a, x[k + 6], S34, 0x04881d05);
        a = hh(a, b, c, d, x[k + 9], S31, 0xd9d4d039);
        d = hh(d, a, b, c, x[k + 12], S32, 0xe6db99e5);
        c = hh(c, d, a, b, x[k + 15], S33, 0x1fa27cf8);
        b = hh(b, c, d, a, x[k + 2], S34, 0xc4ac5665);
        a = ii(a, b, c, d, x[k + 0], S41, 0xf4292244);
        d = ii(d, a, b, c, x[k + 7], S42, 0x432aff97);
        c = ii(c, d, a, b, x[k + 14], S43, 0xab9423a7);
        b = ii(b, c, d, a, x[k + 5], S44, 0xfc93a039);
        a = ii(a, b, c, d, x[k + 12], S41, 0x655b59c3);
        d = ii(d, a, b, c, x[k + 3], S42, 0x8f0ccc92);
        c = ii(c, d, a, b, x[k + 10], S43, 0xffeff47d);
        b = ii(b, c, d, a, x[k + 1], S44, 0x85845dd1);
        a = ii(a, b, c, d, x[k + 8], S41, 0x6fa87e4f);
        d = ii(d, a, b, c, x[k + 15], S42, 0xfe2ce6e0);
        c = ii(c, d, a, b, x[k + 6], S43, 0xa3014314);
        b = ii(b, c, d, a, x[k + 13], S44, 0x4e0811a1);
        a = ii(a, b, c, d, x[k + 4], S41, 0xf7537e82);
        d = ii(d, a, b, c, x[k + 11], S42, 0xbd3af235);
        c = ii(c, d, a, b, x[k + 2], S43, 0x2ad7d2bb);
        b = ii(b, c, d, a, x[k + 9], S44, 0xeb86d391);
        a = addUnsigned(a, AA);
        b = addUnsigned(b, BB);
        c = addUnsigned(c, CC);
        d = addUnsigned(d, DD);
    }

    const temp = wordToHex(a) + wordToHex(b) + wordToHex(c) + wordToHex(d);
    return temp.toLowerCase();
}