// eventos del menu

/*  $("#demo").click(function(){
            $("#cuerpo").empty().load("views/form-demo.php");
        })*/
       //-------- evento menu llamado a Formulario demo -----------------
       /*$("#formularioDemo").click(function(){
        $("#cuerpo").empty().load("views/form-demo.php");
    })

    $("#formularioDemo2").click(function(){
        mostrarVista('vistaDemo');
    })*/

        async function verificarAccesoMenu(nombre_permiso) {
          const idUsuarioSesion = sessionStorage.getItem('id_usuario');
          if (!idUsuarioSesion) {
            alert("Sesión inválida. Vuelve a iniciar sesión.");
            return false;
          }
        
          const datos = new FormData();
          datos.append('id_usuario', idUsuarioSesion);
          datos.append('nombre_permiso', nombre_permiso);
        
          const resp = await microApi('controlador/?verificar_permiso', datos);
          const acceso = resp && resp.acceso === true;
        
          if (!acceso) {
            alert(`No tienes permiso para acceder a ${nombre_permiso}.`);
          }
        
          return acceso;
        }



        $("#formularioEvaluadores").click(async function(){
          if (await verificarAccesoMenu('Gestion de Evaluadores')) {
            mostrarVista('evaluadores');
            listarUsuariosEvaluador();
            listarCargosEvaluadores();
            listarSupervisoresCargos();
            listarEvaluadores();
          }
        });
        
        $("#formularioSupervisores").click(async function(){
          if (await verificarAccesoMenu('Gestion de Supervisores')) {
            mostrarVista('supervisores');
            listarUsuariosSupervisor();
            listarCargosSupervisores();
            listarSupervisores();
            listarSupervisor();
          }
        });
        
        $("#formularioEvaluacion").click(async function(){
          if (await verificarAccesoMenu('Evaluaciones')) {
            mostrarVista('evaluacion');
            listarEvaluados();
          }
        });
        
        $("#formularioUsuarios").click(async function(){
          if (await verificarAccesoMenu('Gestion de Usuarios')) {
            mostrarVista('usuarios');
            listarUsuario();
            listarRolesSistema();
            listarRolesSistemaModal();
          }
        });

    function abrirPlanilla(cedula){ 
        // Guardamos la cédula seleccionada 
        console.log("👉 abrirPlanilla() recibió:", cedula);
        sessionStorage.setItem("cedula_planilla", cedula); 
        // Cargamos la vista de la planilla en el cuerpo 
        mostrarVista('planilla'); 
        // Una vez cargada la vista, ejecutamos la carga de datos 
        setTimeout(async () => { 
            await cargarPlanilla();
            setPeriodoAutomatico();

            // Crea las tablas para objetivos y competencias
            await cargarTablasPlanilla();

           

         }, 300); 
    }

    // Evento del botón
/*document.querySelectorAll('.btn-detalles').forEach(btn => {
    btn.addEventListener('click', e => {
      const cedula = e.target.getAttribute('data-cedula');
      sessionStorage.setItem("cedula_planilla", cedula);
      mostrarVista('planilla'); // tu función que cambia de vista
      setTimeout(() => {
        cargarPlanilla(); // se ejecuta en planilla.js
      }, 300);
    });
  });*/

    //click menu seguridad usuario
    /*$("#formularioUsuario").click(function(){
        mostrarVista('usuario');
        listarUsuario();
    })*/

        // Delegación de eventos: funciona aunque cambies de vista
$(document).on('click', '#btn_buscar_cedula', async function() {
  const valorCedula = $('#id_cedula_usuario').val().trim();
  if (valorCedula.length > 0) {
    const datos = await obtenerDatosPorCedula(valorCedula);
    llenarFormulario(datos);
  } else {
    llenarFormulario(null);
  }
});

$(document).on('keypress', '#id_cedula_usuario', function(event) {
  const charCode = event.which ? event.which : event.keyCode;
  if (
    (charCode < 48 || charCode > 57) && // No es número
    charCode !== 8 && // No es backspace
    charCode !== 37 && // No es flecha izquierda
    charCode !== 39   // No es flecha derecha
  ) {
    event.preventDefault();
  }
});


    document.addEventListener('DOMContentLoaded', async () => {
        // Detectar recarga de la página
        const navEntry = performance.getEntriesByType('navigation')[0];
        const isReload = navEntry ? navEntry.type === 'reload' : (performance.navigation && performance.navigation.type === 1);
      
        if (isReload) {
          try {
            // Cerrar sesión en el servidor
            await microApi('controlador/?logout');
          } catch (e) {
            console.error('Error cerrando sesión en reload:', e);
          }
          // Redirigir al login
          window.location.href = 'login.html';
        }
      });




