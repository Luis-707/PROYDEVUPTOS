// eventos del menu

/*  $("#demo").click(function(){
            $("#cuerpo").empty().load("views/form-demo.php");
        })*/
       //-------- evento menu llamado a Formulario demo -----------------
       $("#formularioDemo").click(function(){
        $("#cuerpo").empty().load("views/form-demo.php");
    })

    $("#formularioDemo2").click(function(){
        mostrarVista('vistaDemo');
    })

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
        /*listarCargosEval();*/
        listarEvaluadores();
        /*listarUsuariosEvaluadorModal();*/
        /*listarCargosEvaluadoresModal();*/
    })
    

    //click menu seguridad usuario
    $("#formularioUsuario").click(function(){
        mostrarVista('usuario');
        listarUsuario();
    })
