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

        $("#formularioComentariosObreros").click(async function(){
          if (await verificarAccesoMenu('Comentarios Obreros', 'formularioComentariosObreros')) {
            mostrarVista('comentarios_obreros');
            listarEvaluadosComentariosObreros();
          }
        });

        


        $("#formularioGestionEvaluados").click(async function(){
          if (await verificarAccesoMenu('Evaluados', 'formularioGestionEvaluados')) {
           //Variable de control para identificar vista actual
           view = 'gestion_evaluados';
            // Cargar la vista evaluados.php
            mostrarVista('gestion_evaluados');
        
            // Inicializar funciones específicas de la vista
            listarGestionEvaluados();        // Poblar tabla de evaluados
            listarCargosSub();
            listarUbicacionesFisicas();
            /*listarCargosEvaluados();*/ 
           // listarRolesEvaluados();      // Poblar select de roles
          }
        });

        $("#formularioEvalAdministrativos").click(async function(){
          if (await verificarAccesoMenu('Registros Administrativos', 'formularioEvalAdministrativos')) {
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

       // Hook de menú (event_menu.js)
       $("#formularioRegistroObreros").click(async function(){
        if (await verificarAccesoMenu('Registros Obreros', 'formularioRegistroObreros')) {

     // Variable de control para identificar vista actual
       view = 'registro_obreros';

     // Cargar la vista
        mostrarVista('registro_obreros');

         // Inicializar funciones específicas del módulo
          listarEvalObrero();        // Poblar tabla de evaluaciones obreros
          listarObrerosSelect();   // Poblar select de evaluados
       }
      });
        
       $("#formularioUsuarios").click(async function(){
        if (await verificarAccesoMenu('Usuarios', 'formularioUsuarios')) {
          //Variable de control para identificar vista actual
          view = 'usuarios';
          mostrarVista('usuarios');
          listarUsuario();
          listarCargos();
          listarUbicacionesFisicas();
          //listarRolesSistemaModal();
        }
      });

        $("#formularioObjetivos").click(async function(){
          if (await verificarAccesoMenu('Gestion de Objetivos', 'formularioObjetivos')) {
            mostrarVista('gestion_objetivos');
            listarObjetivos();
          }
        });

        $("#formularioCompetencias").click(async function(){
          if (await verificarAccesoMenu('Gestion de Competencias', 'formularioCompetencias')) {
            mostrarVista('gestion_competencias');
            listarGCompetencias();
          }
        });

        $("#formularioReportesDesemp").click(async function(){
          if (await verificarAccesoMenu('Reportes', 'formularioReportesDesemp')) {
            mostrarVista('reportes_despempeno');
            listarReporte();
          }
        });

        $("#formularioReportesAdmin").click(async function(){
          if (await verificarAccesoMenu('Reportes Administrativos', 'formularioReportesAdmin')) {
            mostrarVista('reportes_administrativos');   // carga la vista reportes.php
            listarReportesAdministrativos();           // función definida en reportes.js
          }
        });

        $("#formularioReportesObreros").click(async function(){
          if (await verificarAccesoMenu('Reportes Obreros', 'formularioReportesObreros')) {
            mostrarVista('reportes_obreros');   // carga la vista reportes_obreros.php
            listarReportesObreros();            // función definida en reportes_obreros.js
          }
        });

        $("#formularioResultados").click(async function(){
          if (await verificarAccesoMenu('Resultados', 'formularioResultados')) {
            mostrarVista('resultados');
            listarEvaluadosResultados();
          }
        });

  
$("#formularioGraficas").click(async function(){
  if (await verificarAccesoMenu('Graficas', 'formularioGraficas')) {

    mostrarVista('graficas');

    const esperarVista = setInterval(async () => {

      const filtroAnio = document.getElementById("filtroAnio");
      const filtroPeriodo = document.getElementById("filtroPeriodo");
      const canvasRangos = document.getElementById("graficaRangos");

      const filtroAnioComp = document.getElementById("filtroAnioComparativo");
      const filtroPeriodoComp = document.getElementById("filtroPeriodoComparativo");
      const canvasComparativa = document.getElementById("graficaComparativa");

      if (filtroAnio && filtroPeriodo && canvasRangos &&
          filtroAnioComp && filtroPeriodoComp && canvasComparativa) {

        clearInterval(esperarVista);

        // Gráfica de rangos
        await inicializarGraficas();

        // Gráfica comparativa
        llenarAniosComparativo();
        await cargarComparativo();

        filtroAnioComp.addEventListener("change", cargarComparativo);
        filtroPeriodoComp.addEventListener("change", cargarComparativo);
      }

    }, 50);
  }
});
        $("#formularioResultadosObreros").click(async function(){
          if (await verificarAccesoMenu('Resultados Obreros', 'formularioResultadosObreros')) {
            mostrarVista('resultados_obreros');
            listarEvaluadosResultadosObreros();
          }
        });

        $("#formularioIndicadores").click(async function(){
          if (await verificarAccesoMenu('Indicadores', 'formularioIndicadores')) {
            mostrarVista('gestion_indicadores');
            listarIndicadores();
          }
        });

               //funcion abrirPlanilla
function abrirPlanilla(cedula, evaluado_id, idEvalAdmin, cantidad_objetivos, suma_pesos) {
  console.log("👉 abrirPlanilla() recibió:", cedula, evaluado_id, idEvalAdmin, cantidad_objetivos, suma_pesos);
  
  // Validaciones con SweetAlert
  if (cantidad_objetivos < 4) {
      Swal.fire({
          icon: 'error',
          title: '¡Error!',
          text: `La evaluación debe tener al menos 4 objetivos. Actualmente tiene ${cantidad_objetivos}.`,
          confirmButtonText: 'Aceptar'
      });
      return; // Detiene la ejecución
  }
  
  if (suma_pesos > 50) {
      Swal.fire({
          icon: 'error',
          title: '¡Error!',
          text: `La suma de pesos no puede superar 50. Total actual: ${suma_pesos}.`,
          confirmButtonText: 'Aceptar'
      });
      return; // Detiene la ejecución
  }
  
  // Si pasa validaciones, guardar en sessionStorage y continuar
  sessionStorage.setItem("evaluado_id", evaluado_id);
  sessionStorage.setItem("id_eval_admin", idEvalAdmin);
  sessionStorage.setItem("cedula_planilla", cedula);
  mostrarVista('planilla');
  
  setTimeout(async () => {
      await cargarPlanilla();
      await cargarPeriodoEval();
      await cargarTablasPlanilla();
  }, 300);
}
  
      function abrirPlanillaReadonly(cedula, idEvalAdmin){ 
        console.log("👉 abrirPlanillaReadonly() recibió:", cedula);
        sessionStorage.setItem("cedula_planilla", cedula); 
        sessionStorage.setItem("id_eval_admin", idEvalAdmin);
        
    
        // Cargar la vista de la planilla en modo lectura
        mostrarVista('planilla_comentario'); 
    
        // Una vez cargada la vista, ejecutamos la carga de datos
        setTimeout(async () => { 
            await cargarPlanillaReadonly(); // 👈 función definida en el JS de la vista readonly
        }, 300); 
    }

    function abrirPlanillaResultados(cedula, idEvalAdmin){ 
      console.log("👉 abrirPlanillaResultados() recibió:", cedula);
      sessionStorage.setItem("cedula_planilla", cedula); 
      sessionStorage.setItem("id_eval_admin", idEvalAdmin);
      
  
      // Cargar la vista de la planilla en modo lectura
      mostrarVista('planilla_resultados'); 
  
      // Una vez cargada la vista, ejecutamos la carga de datos
      setTimeout(async () => { 
          await cargarPlanillaResultados(); // 👈 función definida en el JS de la vista readonly
      }, 300); 
  }


  // =============================
// Abrir planilla readonly obrera
// =============================
function abrirPlanillaResultadosObrero(cedula, idEvalObrero) {
  sessionStorage.setItem("cedula_planilla_obrero", cedula);
  sessionStorage.setItem("id_eval_obreros", idEvalObrero);
  mostrarVista('planilla_resultados_obrero');

  setTimeout(async () => {
    await cargarPlanillaResultadosObrero();
  }, 300);
}
  
function abrirPlanillaEditar(cedula, evaluado_id, idEvalAdmin){
  sessionStorage.setItem("cedula_planilla", cedula);
  sessionStorage.setItem("evaluado_id", evaluado_id);
  sessionStorage.setItem("id_eval_admin", idEvalAdmin);

  mostrarVista('planilla_editar');

  setTimeout(async () => {
      await cargarPlanillaEditar();
      await cargarPeriodoEvaluacion();
      await cargarTablasPlanillaEditar();
  }, 300);
}

  function abrirPlanillaExcepcional(cedula, idEvalAdmin){ 
    console.log("👉 abrirPlanillaExcepcional() recibió:", cedula);
    sessionStorage.setItem("cedula_planilla", cedula); 
    sessionStorage.setItem("id_eval_admin", idEvalAdmin);
  
    // Cargar la vista de la planilla excepcional
    mostrarVista('planilla_excepcional'); 
  
    // Una vez cargada la vista, ejecutamos la carga de datos
    setTimeout(async () => { 
      await inicializarPlanillaExcepcional(); // función definida en planilla_excepcional.js
    }, 300); 
  }

  // Hook de menú (event_menu.js)
$("#formularioEvaluacionObreros").click(async function(){
  if (await verificarAccesoMenu('Evaluaciones Obreros', 'formularioEvaluacionObreros')) {
    mostrarVista('evaluacion_obreros');
    listarEvaluacionesObreros();
  }
});

// Abrir planilla de obrero
function abrirPlanillaObrero(cedula, idEvalOb) {
  console.log("👉 abrirPlanillaObrero() recibió:", cedula);
  sessionStorage.setItem("cedula_planilla_obrero", cedula);
  sessionStorage.setItem("id_eval_obreros", idEvalOb);
  mostrarVista('planilla_obrero');
  setTimeout(async () => {
    await cargarPlanillaObrero();
    await cargarFactoresYCriteriosObrero();
    //actualizarCalificacionObrero(); // inicializa suma y rango
  }, 300);
}

function abrirPlanillaObreroReadonly(cedula, idEvalObrero) {
  console.log("👉 abrirPlanillaObreroReadonly() recibió:", cedula, idEvalObrero);

  sessionStorage.setItem("cedula_planilla_obrero", cedula);
  sessionStorage.setItem("id_eval_obreros", idEvalObrero);

  mostrarVista('planilla_comentario_obrero');

  setTimeout(async () => {
      await cargarPlanillaObreroReadonly();
  }, 300);
}


function abrirPlanillaObreroEditar(cedula, idEvalObreros) {

  salirDeVista = false;

  // Guardar datos en sessionStorage
  sessionStorage.setItem("cedula_planilla_obrero", cedula);
  sessionStorage.setItem("id_eval_obreros", idEvalObreros);

  // Mostrar vista de edición
  mostrarVista("planilla_obreros_editar");

  // Cargar datos después de que la vista esté visible
  setTimeout(async () => {
      await cargarPlanillaObreroEditar();
      await cargarFactoresObreroEditar();
  }, 300);
}



//======================================================//
//Cargar la vista de perfil

function abrirPerfilUsuario(cedula) { 
  // Guardamos la cédula seleccionada 
  console.log("👉 abrirPerfilUsuario() recibió:", cedula);
  sessionStorage.setItem("cedula_perfil", cedula); 
  // Cargamos la vista de perfilUsuario en el cuerpo
  mostrarVista('perfilUsuario'); 
  // Una vez cargada la vista, ejecutamos la carga de datos
  setTimeout(async () => { 
    await cargarPerfil();  // Función que obtiene el perfil y llama a listarPerfilUsuario
  }, 300); 
}

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
      
      $(document).on('keypress', '#nombre_objetivo, #nombre_competencia, #nombre_competencia_modal, #nombre_objetivo_modal', function(event) {
        const charCode = event.which ? event.which : event.keyCode;
      
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
      
      
      $(document).on('keypress', '#peso_objetivo, #peso_objetivo_modal, #peso_competencia, #peso_competencia_modal', function(event) {
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
      
      // Delegación de eventos: funciona aunque cambies de vista o recargues la tabla
      $(document).on('click', '#btn_buscar_reportes', function() {
        filtrarTabla();
      });
      
      $(document).on('click', '#btn_limpiar_reportes', function() {
        // Limpiar todos los campos
        $('#filtroCedula').val('');
        $('#filtroNombre').val('');
        $('#filtroAnio').val('');
        $('#filtroPeriodo').val('');
        
        // Mostrar todas las filas
        filtrarTabla();
      });
      
      // Delegación de eventos para reportes obreros
      $(document).on('click', '#btn_buscar_reportes_obrero', function() {
        filtrarTablaObreros();
      });
      
      $(document).on('click', '#btn_limpiar_reportes_obrero', function() {
        // Limpiar todos los campos obreros
        $('#filtroCedulaObrero').val('');
        $('#filtroNombreObrero').val('');
        $('#filtroAnioObrero').val('');
        $('#filtroPeriodoObrero').val('');
        
        // Mostrar todas las filas obreras
        filtrarTablaObreros();
      });
      
      //validacion para campos numericos de los filtros
      $(document).on('keypress', '#filtroAnio, #filtroCedula, #filtroAnioObrero, #filtroCedulaObrero', function(event) {
        const charCode = event.which ? event.which : event.keyCode;
        const $campo = $(this);
        const valorActual = $campo.val();
        const campoId = $campo.attr('id');
      
        // Permitir solo números, backspace, flechas izquierda y derecha
        if (
          (charCode < 48 || charCode > 57) && // no es número
          charCode !== 8 && // backspace
          charCode !== 37 && // flecha izquierda
          charCode !== 39   // flecha derecha
        ) {
          event.preventDefault();
          return;
        }
      
        // Validación de longitud específica por campo
        if ((campoId === 'filtroAnio' || campoId === 'filtroAnioObrero') && valorActual.length >= 4) {
          event.preventDefault();
        }
        else if ((campoId === 'filtroCedula' || campoId === 'filtroCedulaObrero') && valorActual.length >= 9) {
          event.preventDefault();
        }
      });
      
      //validacion para campos de caracteres en los filtros de planillas
      $(document).on('keypress', '#filtroNombre, #filtroPeriodo, #filtroNombreObrero, #filtroPeriodoObrero', function(event) {
        const charCode = event.which ? event.which : event.keyCode;
      
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

  await verificarAccesoMenu('Evaluados', 'formularioGestionEvaluados');
  await verificarAccesoMenu('Evaluaciones', 'formularioEvaluacion');
  await verificarAccesoMenu('Evaluaciones Obreros', 'formularioEvaluacionObreros');
  await verificarAccesoMenu('Comentarios', 'formularioComentarios');
  await verificarAccesoMenu('Comentarios Obreros', 'formularioComentariosObreros');
  await verificarAccesoMenu('Usuarios', 'formularioUsuarios');
  await verificarAccesoMenu('Registros Administrativos', 'formularioEvalAdministrativos');
  await verificarAccesoMenu('Gestion de Objetivos', 'formularioObjetivos');
  await verificarAccesoMenu('Gestion de Competencias', 'formularioCompetencias');
  await verificarAccesoMenu('Reportes', 'formularioReportesDesemp');
  await verificarAccesoMenu('Resultados', 'formularioResultados');
  await verificarAccesoMenu('Resultados Obreros', 'formularioResultadosObreros');
  await verificarAccesoMenu('Reportes Administrativos', 'formularioReportesAdmin');
  await verificarAccesoMenu('Reportes Obreros', 'formularioReportesObreros');
  await verificarAccesoMenu('Registros Obreros', 'formularioRegistroObreros');
  await verificarAccesoMenu('Indicadores', 'formularioIndicadores');
  await verificarAccesoMenu('Graficas', 'formularioGraficas');

});




