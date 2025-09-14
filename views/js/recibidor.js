// Referencias al DOM
const tbodyUsuarios = document
  .querySelector('#tabla-usuarios tbody');

const formEditar = document
  .getElementById('form-editar-usuario');

// 2.1. Función para invocar tu API genérica
async function microApi(action, datos) {
  const resp = await fetch('controlador/?a_usuario=${action}', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(datos)
  });
  if (!resp.ok) throw new Error(resp.statusText);
  return resp.json();
}

// 2.2. Listar usuarios y renderizar la tabla
async function listarTablaUsuarios() {
  try {
    const respuesta = await microApi('listar', {});
    const usuarios = respuesta.data;  // Asumiendo formato { data: [...] }

    // Genera filas sin ondblclick
    let html = '';
    usuarios.forEach(item => {
      html += '<tr>';
      html += <td>${item.id}</td>;
      html += <td>${item.nombre}</td>;
      html += <td>${item.usuario}</td>;
      html +=
        `<td>
           <img
             src="img/iconos/editar.png"
             alt="Editar"
             class="btn-edit"
             style="cursor:pointer; width:22px;"
             data-usuario="${item.usuario}"
           >
         </td>`;
      html += '</tr>';
    });

    // Inyecta en el tbody y enlaza eventos
    tbodyUsuarios.innerHTML = html;
    enlazarBotonesEditar();

  } catch (err) {
    console.error('Error listando usuarios:', err);
  }
}

// 2.3. Enlazar click en cada ícono de editar
function enlazarBotonesEditar() {
  document
    .querySelectorAll('.btn-edit')
    .forEach(btn => {
      btn.addEventListener('click', () => {
        abrirModalEdicion(btn.dataset.usuario);
      });
    });
}

// 2.4. Abrir modal y cargar datos
function abrirModalEdicion(usuario) {
  document.getElementById('inputUsuario').value = usuario;
  document.getElementById('inputClave').value   = '';
  $('#modal-editar').modal('show');
}

// 2.5. Capturar submit y actualizar usuario
formEditar.addEventListener('submit', async event => {
  event.preventDefault();

  const usuario = formEditar.usuario.value;
  const clave   = formEditar.clave.value.trim();

  if (!clave) {
    alert('Ingresa una nueva contraseña');
    return;
  }

  try {
    // Llama al endpoint 'actualizar'
    await microApi('actualizar', { usuario, clave });
    $('#modal-editar').modal('hide');
    await listarTablaUsuarios();
    alert('Contraseña actualizada correctamente');
  } catch (err) {
    console.error('Error actualizando usuario:', err);
    alert('No se pudo actualizar la contraseña');
  }
});