  //Recibe el parametro cedula de la sesión
async function cargarPerfil() {
  const cedula = sessionStorage.getItem("cedula_perfil");
  console.log("Cedula perfil:", cedula);
  if (!cedula) {
    alert("No se seleccionó perfil");
    return;
  }

  const formData = new FormData();
  formData.append("cedula_usuario", cedula);

  const resp = await microApi('controlador/?perfil', formData);
  console.log("Respuesta API perfil:", resp);

  // Asumiendo que resp es un arreglo anidado, acceder al primer objeto con datos
  const perfilDatos = Array.isArray(resp) && Array.isArray(resp[0]) ? resp[0][0] : null;

  if (!perfilDatos || !perfilDatos.cedula_usuario) {
    alert("Error cargando perfil");
    return;
  }

  // Usar directamente los datos recibidos para llenar el formulario
  document.getElementById("fullname").value = perfilDatos.nombre_completo || "No encontrado";
  document.getElementById("cedula_usuario").value = perfilDatos.cedula_usuario || "";
  document.getElementById("additional").value = perfilDatos.ubicacion_administrativa || "";
  const contenedorCargo = document.getElementById("campo-cargo");
  if (perfilDatos.rol === "Administrador") {
    contenedorCargo.style.display = "none";  // Oculta etiqueta + campo
  } else {
    contenedorCargo.style.display = "";
    document.getElementById("cargo").value = perfilDatos.cargo_usuario || "Sin cargo";
  }

  document.getElementById("rol").value = perfilDatos.rol || "";
}
