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
          if (await verificarAccesoMenu('Gestion de evaluadores', 'formularioEvaluadores')) {
            mostrarVista('evaluadores');
            listarUsuariosEvaluador();
            listarCargosEvaluadores();
            listarSupervisoresCargos();
            listarEvaluadores();
          }
        });
        
        $("#formularioSupervisores").click(async function(){
          if (await verificarAccesoMenu('Gestion de supervisores', 'formularioSupervisores')) {
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
          if (await verificarAccesoMenu('Gestion de evaluados', 'formularioGestionEvaluados')) {
           //Variable de control para identificar vista actual
           view = 'gestion_evaluados';
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

        $("#formularioEvalAdministrativos").click(async function(){
          if (await verificarAccesoMenu('Evaluacion administrativos', 'formularioEvalAdministrativos')) {
            //Variable de control para identificar vista actual
            view = 'evaluacion_administrativos';
            // Cargar la vista evaluados.php
            mostrarVista('evaluacion_administrativos');
            listarEvalAdmin();
            listarEvaluadosAdmin();
            //listarUsuariosEvaluados();
            //listarCargosEvaluados();    // Poblar select de cargos
          }
        });
        
        $("#formularioUsuarios").click(async function(){
          if (await verificarAccesoMenu('Gestion de usuarios', 'formularioUsuarios')) {
            //Variable de control para identificar vista actual
            view = 'usuarios';
            mostrarVista('usuarios');
            listarUsuario();
            listarRolesSistema();
            listarRolesSistemaModal();
          }
        });

        $("#formularioObjetivos").click(async function(){
          if (await verificarAccesoMenu('Gestion de objetivos', 'formularioObjetivos')) {
            mostrarVista('gestion_objetivos');
            listarObjetivos();
          }
        });

        $("#formularioCompetencias").click(async function(){
          if (await verificarAccesoMenu('Gestion de competencias', 'formularioCompetencias')) {
            mostrarVista('gestion_competencias');
            listarGCompetencias();
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
            cargarPeriodoEval(); // carga periodo evaluación

            // Crea las tablas para objetivos y competencias
            await cargarTablasPlanilla();

           

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

  //====================================
  function abrirPlanillaEditar(cedula){ 
    console.log("👉 abrirPlanillaEditar() recibió:", cedula);
    sessionStorage.setItem("cedula_planilla", cedula); 

    // Cargar la vista de edición de la planilla
    mostrarVista('planilla_editar'); 

    // Una vez cargada la vista, ejecutamos la carga de datos
    setTimeout(async () => { 
      
        await cargarPlanillaEditar();   // 👈 función en planilla_editar.js
        //debugIdsEvaluacion(); // muestra ids en consola para debug
        cargarPeriodoEvaluacion(); // carga periodo evaluación
        await cargarTablasPlanillaEditar(); // inicializa tablas con rangos guardados
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

    /*
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
    */

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

//Validacion del campo cedula

$(document).on('keypress', '#id_cedula_usuario', function(event) {
  const charCode = event.which ? event.which : event.keyCode;
  const valorActual = $(this).val();

  if (
    (charCode < 48 || charCode > 57) && // No es número
    charCode !== 8 && // No es backspace
    charCode !== 37 && // No es flecha izquierda
    charCode !== 39   // No es flecha derecha
  ) {
    event.preventDefault();
  }

  // Impide ingresar más de 8 caracteres numéricos
  if (valorActual.length >= 8 && 
      charCode >= 48 && charCode <= 57) { 
    event.preventDefault();
  }
});


// Delegación de eventos para boton Mostrar: funciona aunque cambies de vista
        
$(document).on('click', '#toggleClave', function() {
  const claveInput = document.getElementById('id_clave');
  const toggleBtn = document.getElementById('toggleClave');
  const mensajeSeguridad = document.getElementById('mensajeSeguridad');

  claveInput.maxLength = 16;

  if (claveInput.type === 'password') {
    claveInput.type = 'text';
    toggleBtn.textContent = 'Ocultar';
  } else {
    claveInput.type = 'password';
    toggleBtn.textContent = 'Mostrar';
  }

  claveInput.addEventListener('input', () => {
    const clave = claveInput.value;
    let mensaje = '';
    let color = 'red';

    const longitud = clave.length;
    const tieneMayuscula = /[A-Z]/.test(clave);
    const tieneMinuscula = /[a-z]/.test(clave);
    const tieneNumero = /\d/.test(clave);
    const tieneEspecial = /[^A-Za-z0-9]/.test(clave);

    if (longitud < 8) {
      mensaje = 'Débil: menos de 8 caracteres';
      color = 'red';
    } else if (longitud >= 8 && longitud <= 9) {
      // Mínimo 8, máximo 9 caracteres
      if (tieneMayuscula && tieneMinuscula && (tieneNumero || tieneEspecial)) {
        mensaje = 'Segura';
        color = 'orange';
      } else {
        mensaje = 'Débil';
        color = 'red';
      }
    } else if (longitud >= 10 && longitud <= 12) {
      // Entre 10 y 12 caracteres
      if ((tieneMayuscula && tieneMinuscula) && (tieneNumero || tieneEspecial)) {
        mensaje = 'Muy segura';
        color = 'green';
      } else {
        mensaje = 'Segura';
        color = 'orange';
      }
    } else {
      // Más de 12 caracteres (opcional extra)
      mensaje = 'Muy segura';
      color = 'green';
    }
    mensajeSeguridad.textContent = mensaje;
    mensajeSeguridad.style.color = color;
  });
});

//Validaciones para formulario de objetivos

$(document).on('keypress', '#nombre_objetivo', function(event) {
  const charCode = event.which ? event.which : event.keyCode;
  const valorActual = $(this).val();

  // Permitir solo letras (mayúsculas y minúsculas), espacios, backspace, flechas izquierda y derecha
  if (
    !(charCode >= 65 && charCode <= 90) &&  // A-Z
    !(charCode >= 97 && charCode <= 122) && // a-z
    charCode !== 32 &&  // espacio
    charCode !== 8 &&   // backspace
    charCode !== 37 &&  // flecha izquierda
    charCode !== 39     // flecha derecha
  ) {
    event.preventDefault();
  }
});

$(document).on('keypress', '#peso_objetivo', function(event) {
  const charCode = event.which ? event.which : event.keyCode;
  const valorActual = $(this).val();

  // Permitir solo números, backspace, flechas izquierda y derecha
  if (
    (charCode < 48 || charCode > 57) && // no es número
    charCode !== 8 && // backspace
    charCode !== 37 && // flecha izquierda
    charCode !== 39   // flecha derecha
  ) {
    event.preventDefault();
  }

  // Limitar a máximo 2 dígitos
  if (valorActual.length >= 2 && charCode >= 48 && charCode <= 57) { 
    event.preventDefault();
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
  await verificarAccesoMenu('Gestion de evaluadores', 'formularioEvaluadores');
  await verificarAccesoMenu('Gestion de supervisores', 'formularioSupervisores');
  await verificarAccesoMenu('Gestion de evaluados', 'formularioGestionEvaluados');
  await verificarAccesoMenu('Cargos de Evaluados', 'formularioDatosEvaluados');
  await verificarAccesoMenu('Evaluaciones', 'formularioEvaluacion');
  await verificarAccesoMenu('Comentarios', 'formularioComentarios');
  await verificarAccesoMenu('Gestion de usuarios', 'formularioUsuarios');
  await verificarAccesoMenu('Evaluacion administrativos', 'formularioEvalAdministrativos');
  await verificarAccesoMenu('Gestion de objetivos', 'formularioObjetivos');
  await verificarAccesoMenu('Gestion de competencias', 'formularioCompetencias');
});




