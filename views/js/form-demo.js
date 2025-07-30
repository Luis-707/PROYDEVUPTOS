$(document).ready(function(){
    // $('#list_persona').DataTable()
    listar();
})      


async function listar(){
    await peticion("controlador/?persona", {accion: "list"}, "POST", (data) => {

        let resp = data[0];
        
        if ( $.fn.DataTable.isDataTable( '#list_persona' ) ) {
            $('#list_persona').DataTable().destroy();
        }

        $('#list_persona').DataTable( {
            data: resp,
            columns: [
                { data: "cedula", title: "CEDULA" },
                { data: "nombre_apellido", title: "NOMBRE COMPLETO" },
                { data: "email", title: "CORREO ELECTRÓNICO" },
                { data: "telefono", title: "TELÉFONO" },
                { data: "accion", title: "ACCIÓN", className: "text-center", "render": function ( data, type, row ) {
                    return `
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                        <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);" onclick="editar('${row.id_persona}')"><i class="icon-base bx bx-edit-alt me-1"></i> Editar</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="eliminar('${row.id_persona}')"><i class="icon-base bx bx-trash me-1"></i> Eliminar</a>
                        </div>
                    </div>`
                }},
            ],
        } );

    });
}

async function guardar(){

    let datos = {
        'cedula' : $("#cedula").val(),
        'nombre_apellido' : $("#nombre_apellido").val(),
        'email' : $("#email").val(),
        'telefono' : $("#telefono").val(),
        'id_persona' : $("#id_persona").val(),
        'accion': 'create'
    }

    if($("#id_persona").val() != ""){
        datos.accion = 'update';
    }

    await peticion("controlador/?persona", datos, "POST", (data) => {

        listar();
        $("#form_persona")[0].reset();

    });

}

async function editar(id){

    await peticion("controlador/?persona", {accion: "search", id_persona: id}, "POST", (data) => {

        let dat = data[0][0];

        $("#cedula").val(dat.cedula);
        $("#nombre_apellido").val(dat.nombre_apellido);
        $("#email").val(dat.email);
        $("#telefono").val(dat.telefono);
        $("#id_persona").val(dat.id_persona);

    });

}

async function eliminar(id){

    await peticion("controlador/?persona", {accion: "delete", id_persona: id}, "POST", (data) => {

        listar();

    });

}