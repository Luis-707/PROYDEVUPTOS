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
    
    // evento click a menu persona
    $("#menuPersons").click(function(){
        //mostrarVista('persona');
        $("#cuerpo").empty().load("views/vpersona.php");
    })

    //click menu seguridad usuario
    $("#formularioUsuario").click(function(){
        mostrarVista('usuario');
        listarUsuario();
    })