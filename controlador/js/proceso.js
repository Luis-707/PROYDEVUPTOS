
function capturarValoresFormulario(idFormulario, otrosDatos = '') {
    const form = document.getElementById(idFormulario);
    if (!form) {
      throw new Error(`Formulario con ID '${idFormulario}' no encontrado`);
    }
    const formData = new FormData(form);
    if (typeof otrosDatos == 'string') {//alert('cadena');
      formData.append('otros_datos', otrosDatos);
    } else if (Array.isArray(otrosDatos)) { //console.log(otrosDatos);
      /* for (let i = 0; i < otrosDatos.length; i++) {
        //alert(otrosDatos[i]);
        formData.append("otrosDatos["+i+"]", otrosDatos[i]);
      }*/
      formData.append("otrosDatos", JSON.stringify(otrosDatos));
    } else {
      throw new Error('otrosDatos debe ser cadena string o array');
    }
    return formData;
  }
//function procesar(servicio, metodoRespuesta, data = new formData('') )
async function procesar(urlServicio, metodoRespuesta = null, data = new FormData()) {

    if (!urlServicio) {
      throw new Error('Servicio no definido');
    }

    return new Promise( (resolve) => {

      fetch(urlServicio, {
        method: 'POST',
        /* headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },*/
        body: data
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la solicitud POST.');
        }
        return response.json();
      })
      .then(req => {
        if(metodoRespuesta != null){
          eval(metodoRespuesta);
        }
        resolve(req);
      })
      .catch(error => {
        console.error('Error:', error);
      });

    } )
    
  }

async function api(url, data = new FormData()) {
  if (!url) {
    throw new Error('Servicio no definido');
  }

  try {
    const response = await fetch(url, {
      method: 'POST',
      /*headers: {
        'Content-Type': 'application/json'
      },*/
      body: JSON.stringify(data)
    });
    const respuesta = await response.json();
    return respuesta;
  } catch (error) {
    return console.error('Error:', error);
  }
}

async function microApi(urlServicio, data = new FormData()) {
  if (!urlServicio) {
    throw new Error('Servicio no definido');
  }

  return new Promise( (resolve) => {

    fetch(urlServicio, {
      method: 'POST',
      body: data
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la solicitud POST.');
        }
        return response.json();
      })
      .then(req => {
        resolve(req);
      })
      .catch(error => {
        console.error('Error:', error);
      });

  } )
}

function mapRepuesta(req){
  var respuesta = [];
  if (!Array.isArray(req)) { // no es un array?
    respuesta.push(req);
  }else{
    respuesta=mapArrayRepuesta(req);
  }
  return respuesta;
}

function mapArrayRepuesta(req){
  var respuesta = [];
  respuesta =  req.map(obj => {
    const result = {};
    for (const key in obj) {
      result[key] = obj[key];
    }
    return result;
  });
  return respuesta;
}

function mostrarVista(nombreArchivo, idElemento='cuerpo') {
  const elemento = document.getElementById(idElemento);
  if (!elemento) {
      console.error(`Elemento con ID "${idElemento}" no encontrado.`);
      return;
  }

  fetch('views/vista.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify({ 
          file: nombreArchivo, 
          idElemento: idElemento 
      }),
  })
  .then(response => response.text())
  .then(data => {
      // Insertar el contenido en el elemento
      elemento.innerHTML = data;

      // Extraer y ejecutar los scripts
      const scripts = elemento.querySelectorAll('script');
      scripts.forEach(script => {
          const newScript = document.createElement('script');

          // Copiar atributos
          Array.from(script.attributes).forEach(attr => {
              newScript.setAttribute(attr.name, attr.value);
          });

          // Copiar contenido interno o src
          if (script.src) {
              newScript.src = script.src;
          } else {
              newScript.textContent = script.textContent;
          }

          // Verificar si el script ya existe Luis
          const scriptsExistentes = document.querySelectorAll(`script[src="${newScript.src}"]`);
          if (scriptsExistentes.length === 0) {
              document.body.appendChild(newScript); // Agregar el script al final del body
          }
      });
  })
  .catch(error => console.error('Error:', error));
}

