const claveInput = document.getElementById('id_clave');
const toggleBtn = document.getElementById('toggleClave');
      
    toggleBtn.addEventListener('click', () => {
        if (claveInput.type === 'password') {
            claveInput.type = 'text';
            toggleBtn.textContent = 'Ocultar';
        } else {
            claveInput.type = 'password';
            toggleBtn.textContent = 'Mostrar';
          }
    });