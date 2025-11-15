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
   

          // Replegar menú automáticamente al hacer clic en cualquier opción
$(document).on('click', '.menu-link', function() {
  const toggler = document.querySelector('.layout-menu-toggle');
  if (toggler) {
    toggler.click(); // simula el clic en el botón de colapso de Sneat
  }
});

        async function verificarAccesoMenu(nombre_permiso, idMenuElemento) {
          const idUsuarioSesion = sessionStorage.getItem('id_usuario');
          if (!idUsuarioSesion) {
            if (idMenuElemento) {
              document.getElementById(idMenuElemento).style.display = 'none';
            }
            return false;
          }
        
          const datos = new FormData();
          datos.append('id_usuario', idUsuarioSesion);
          datos.append('nombre_permiso', nombre_permiso);
        
          const resp = await microApi('controlador/?verificar_permiso', datos);
          const acceso = resp && resp.acceso === true;
        
          if (idMenuElemento) {
            document.getElementById(idMenuElemento).style.display = acceso ? 'block' : 'none';
          }
        
          return acceso;
        }



        $("#formularioEvaluadores").click(async function(){
          if (await verificarAccesoMenu('Gestion de Evaluadores', 'formularioEvaluadores')) {
            mostrarVista('evaluadores');
            listarUsuariosEvaluador();
            listarCargosEvaluadores();
            listarSupervisoresCargos();
            listarEvaluadores();
            
          }
        });
        
        $("#formularioSupervisores").click(async function(){
          if (await verificarAccesoMenu('Gestion de Supervisores', 'formularioSupervisores')) {
            mostrarVista('supervisores');
            listarUsuariosSupervisor();
            listarCargosSupervisores();
            listarSupervisores();
            listarSupervisor();
            
          }
        });
        
        $("#formularioEvaluacion").click(async function(){
          if (await verificarAccesoMenu('Evaluaciones', 'formularioEvaluacion')) {
            mostrarVista('evaluacion');
            listarEvaluados();
            
          }
        });

        $("#formularioComentarios").click(async function(){
          if (await verificarAccesoMenu('Comentarios', 'formularioComentarios')) {
            mostrarVista('comentarios');
            listarEvaluadosComentarios();
            
          }
        });

        $("#formularioGestionEvaluados").click(async function(){
          if (await verificarAccesoMenu('Gestion de Evaluados', 'formularioGestionEvaluados')) {
            // Cargar la vista evaluados.php
            mostrarVista('gestion_evaluados');
        
            // Inicializar funciones específicas de la vista
            listarGestionEvaluados();        // Poblar tabla de evaluados
            /*listarCargosEvaluados();*/ 
            listarRolesEvaluados();      // Poblar select de roles
         
          }
        });

        $("#formularioDatosEvaluados").click(async function(){
          if (await verificarAccesoMenu('Cargos de Evaluados', 'formularioDatosEvaluados')) {
            // Cargar la vista evaluados.php
            mostrarVista('cargos_evaluados');
        
            listarUsuariosEvaluados();
            listarCargosEvaluados();
            listarDatosEvaluados();
          
           // listarEvaluado();
          }
        });

        $("#formularioEvaluacionAdministrativos").click(async function(){

          if (await verificarAccesoMenu('Evaluacion Administrativos', 'formularioEvaluacionAdministrativos')) {
            mostrarVista('evaluacion_administrativos');
            listarEvalAdmin();
            listarEvaluadosAdmin();
          }
        });

        
        
        $("#formularioUsuarios").click(async function(){
          if (await verificarAccesoMenu('Gestion de Usuarios', 'formularioUsuarios')) {
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

    function abrirPlanillaEditar(cedula){ 
      console.log("👉 abrirPlanillaEditar() recibió:", cedula);
      sessionStorage.setItem("cedula_planilla", cedula); 
  
      // Cargar la vista de edición de la planilla
      mostrarVista('planilla_editar'); 
  
      // Una vez cargada la vista, ejecutamos la carga de datos
      setTimeout(async () => { 
        
          await cargarPlanillaEditar();   // 👈 función en planilla_editar.js
          debugIdsEvaluacion(); // muestra ids en consola para debug
          cargarPeriodoEvaluacion(); // carga periodo evaluación
          await cargarTablasPlanillaEditar(); // inicializa tablas con rangos guardados
      }, 300); 
  }

    function abrirPlanillaReadonly(cedula){ 
      console.log("👉 abrirPlanillaReadonly() recibió:", cedula);
      sessionStorage.setItem("cedula_planilla", cedula); 
      
  
      // Cargar la vista de la planilla en modo lectura
      mostrarVista('planilla_comentario'); 
  
      // Una vez cargada la vista, ejecutamos la carga de datos
      setTimeout(async () => { 
          await cargarPlanillaReadonly(); // 👈 función definida en el JS de la vista readonly
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

// Evento para el botón de búsqueda en la vista de Evaluados
$(document).on('click', '#btn_buscar_cedula_eval', async function() {
  const valorCedula = $('#id_cedula_evaluado').val().trim();
  if (valorCedula.length > 0) {
    const datos = await obtenerDatosPorCedulaEvaluado(valorCedula);
    llenarFormularioEvaluado(datos);
  } else {
    llenarFormularioEvaluado(null);
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

// Delegación de eventos para boton Mostrar: funciona aunque cambies de vista
        
$(document).on('click', '#toggleClave', function() {
  const claveInput = document.getElementById('id_clave');
  const toggleBtn = document.getElementById('toggleClave');

  if (claveInput.type === 'password') {
    claveInput.type = 'text';
    toggleBtn.textContent = 'Ocultar';
  } else {
    claveInput.type = 'password';
    toggleBtn.textContent = 'Mostrar';
  }
});

// Detectar cierre de pestaña o navegador y cerrar sesión si es así
document.addEventListener("visibilitychange", () => {
  if (document.visibilityState === "visible") {
    const idUsuarioSesion = sessionStorage.getItem("id_usuario");
    if (!idUsuarioSesion) {
      // No hay sesión en el navegador → redirigir al login
      window.location.href = "login.html";
    }
  }
});

document.addEventListener('DOMContentLoaded', async () => {
 
  // 🔹 Al entrar a la sesión, verificar permisos y ocultar menús
  await verificarAccesoMenu('Gestion de Evaluadores', 'formularioEvaluadores');
  await verificarAccesoMenu('Gestion de Supervisores', 'formularioSupervisores');
  await verificarAccesoMenu('Gestion de Evaluados', 'formularioGestionEvaluados');
  await verificarAccesoMenu('Cargos de Evaluados', 'formularioDatosEvaluados');
  await verificarAccesoMenu('Evaluacion Administrativos', 'formularioEvaluacionAdministrativos');
  await verificarAccesoMenu('Evaluaciones', 'formularioEvaluacion');
  await verificarAccesoMenu('Comentarios', 'formularioComentarios');
  await verificarAccesoMenu('Gestion de Usuarios', 'formularioUsuarios');
});




