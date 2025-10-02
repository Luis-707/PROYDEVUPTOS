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

     //click menu Prueba de servicios
     $("#formularioUsuarios").click(function(){
        mostrarVista('usuarios');
        listarUsuario();
        listarRolesSistema();
        listarRolesSistemaModal();
        
        })

    //click gestion de evaluadores
    $("#formularioEvaluadores").click(function(){
        mostrarVista('evaluadores');
        listarUsuariosEvaluador();
        listarCargosEvaluadores();
        listarSupervisoresCargos();
        /*listarCargosEval();*/
        listarEvaluadores();
        /*listarUsuariosEvaluadorModal();*/
        /*listarCargosEvaluadoresModal();*/
    })
    //click gestion de supervisores
    $("#formularioSupervisores").click(function(){
        mostrarVista('supervisores');
        listarUsuariosSupervisor();
        listarCargosSupervisores();
        /*listarCargosEval();*/
        listarSupervisores();
        listarSupervisor();
        /*listarCheckEvaluadores();*/
        /*listarUsuariosEvaluadorModal();*/
        /*listarCargosEvaluadoresModal();*/
    })
    

    //click menu evaluacion
    $("#formularioEvaluacion").click(function(){
        mostrarVista('evaluacion');
        listarEvaluados();
        /*listarEvaluaciones();*/

    })

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
    $("#formularioUsuario").click(function(){
        mostrarVista('usuario');
        listarUsuario();
    })

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


