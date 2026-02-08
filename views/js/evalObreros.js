/* ============================================================
   VALIDACIONES BÁSICAS
============================================================ */

function validarCadena(cadena){
    var regex = /^[aA-zZàèìòùÁÉÍÓÚ\s]+$/;
    return regex.test(cadena);
  }
  
  function validarnumero(numero){
    var regex = /^[0-9]+$/;
    return regex.test(numero);
  }
  
  function validarcaracter(cadena){
    var regex = /^[0-9aA-zZàèìòùÁÉÍÓÚ_.-]+$/;
    return regex.test(cadena);
  }
  
  /* ============================================================
     VALIDAR FORMULARIO PRINCIPAL
  ============================================================ */
  
  function validar_formEvalObrero(opc) {
  
    var formulario = document.getElementById('formulario_EvalObrero');
    var Data = new FormData(formulario);
    let isValid = true;
  
    for (var [key, valor] of Data.entries()) {
  
        switch (key) {
  
            case 'fecha_inicio':
                if (valor === "") {
                    alert("El campo Fecha de Inicio no puede estar vacío.");
                    isValid = false;
                }
                break;
  
            case 'fecha_cierre':
                if (valor === "") {
                    alert("El campo Fecha de Cierre no puede estar vacío.");
                    isValid = false;
                }
                break;
  
            case 'periodo_evaluacion':
                if (!validarcaracter(valor)) {
                    alert("Opción inválida.");
                    isValid = false;
                }
                break;
        }
  
        if (!isValid) break;
    }
  
    if (isValid) {
        if (opc == 1) {
            guardarPeriodoEvaluacionObrero();
        } else {
            actualizarPeriodoEvaluacionObrero();
        }
    }
  }
  
  /* ============================================================
     AJUSTAR FECHAS SEGÚN PERIODO
  ============================================================ */
  
  function ajustarFechasPeriodoObrero(periodo) {
    const inicioInput = document.getElementById('fecha_inicio_obrero');
    const cierreInput = document.getElementById('fecha_cierre_obrero');
    const year = new Date().getFullYear();
  
    if (periodo === 'Enero-Junio') {
      inicioInput.value = `${year}-01-01`;
      cierreInput.value = `${year}-06-01`;
    } 
    else if (periodo === 'Julio-Diciembre') {
      inicioInput.value = `${year}-07-01`;
      cierreInput.value = `${year}-12-01`;
    } 
    else {
      inicioInput.value = '';
      cierreInput.value = '';
    }
  }
  
  /* ============================================================
     GUARDAR EVALUACIÓN OBRERO
  ============================================================ */
  
  async function guardarPeriodoEvaluacionObrero() {
    try {
      let datos = capturarValoresFormulario('formulario_EvalObrero');
  
      let idEvaluado = document.getElementById('id_evaluado_obrero').value;
      if (!idEvaluado) {
        Swal.fire({
          icon: 'warning',
          title: 'Falta seleccionar evaluado',
          text: 'Debe seleccionar un usuario evaluado antes de guardar.'
        });
        return;
      }
      datos.append('id_evaluado', idEvaluado);
  
      const periodo = document.getElementById('periodo_evaluacion').value;
      const fechaInicio = document.getElementById('fecha_inicio_obrero').value;
      const fechaCierre = document.getElementById('fecha_cierre_obrero').value;
  
      if (!validarFechasPeriodoObrero(fechaInicio, fechaCierre, periodo)) {
        Swal.fire({
          icon: 'error',
          title: 'Fechas inválidas',
          text: 'Las fechas no corresponden al periodo seleccionado.'
        });
        return;
      }
  
      const resp = await microApi('controlador/?g_evalObrero', datos);
  
      if (resp.success) {
        Swal.fire({
          icon: 'success',
          title: 'Registro guardado',
          text: resp.message
        });
  
        listarEvalObrero();
        valorFormEvalObrero();
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: resp.message
        });
      }
  
    } catch (err) {
      console.error("Error en guardarPeriodoEvaluacionObrero:", err);
      Swal.fire({
        icon: 'error',
        title: 'Error inesperado',
        text: 'Ocurrió un error al guardar el periodo'
      });
    }
  }
  
  /* ============================================================
     VALIDAR FECHAS SEGÚN PERIODO
  ============================================================ */
  
  function validarFechasPeriodoObrero(fi, fc, periodo) {
  
    if (periodo === "Enero-Junio" && fi.endsWith("-01-01") && fc.endsWith("-06-01")) {
      return true;
    }
    if (periodo === "Julio-Diciembre" && fi.endsWith("-07-01") && fc.endsWith("-12-01")) {
      return true;
    }
    return false;
  }
  
  /* ============================================================
     LISTAR EVALUACIONES OBREROS
  ============================================================ */
  
  async function listarEvalObrero(){
      var resp = await microApi('controlador/?listar_evalObreros');
      listarTablaEvalObrero(resp);
  }
  
  /* ============================================================
     RENDERIZAR TABLA
  ============================================================ */
  
  async function listarTablaEvalObrero(datos) {
    const tbody = document.querySelector("#tabla-EvalObrero tbody");
    tbody.innerHTML = "";
  
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
  
    if ($.fn.DataTable.isDataTable('#tabla-EvalObrero')) {
        $('#tabla-EvalObrero').DataTable().destroy();
    }
  
    const tableData = registros.map(item => {
        const cedula = String(item.cedula_usuario).trim();
        const fullname = item.nombre_completo || "No encontrado";
        const ubicacion = item.ubicacion_administrativa || "Sin ubicación";
        const cargoTexto = item.cargo_evaluado || "Sin cargo";
        const periodo = item.periodo_evaluacion || "";
        const anioInicio = item.anio_inicio;
        const estado = item.estado_eval_obreros;
  
        const acciones = `
            <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);" onclick="abrirModalEditarPeriodoObrero(${item.id_eval_obreros})">
                        <i class="icon-base bx bx-edit-alt me-1"></i>Editar
                    </a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="cambiarEstadoEvalObrero(${item.id_eval_obreros},'${item.estado_eval_obreros}')">
                        <i class="icon-base bx bx-toggle-right me-1"></i>Cambiar estado
                    </a>
                    <a class="dropdown-item" href="javascript:void(0);" onclick="abrirPlanillaObrero('${cedula}', ${item.id_eval_obreros})">
                        <i class="icon-base bx bx-file me-1"></i>Planilla
                    </a>
                </div>
            </div>
        `;
  
        return [
            cedula,
            fullname,
            ubicacion,
            cargoTexto,
            anioInicio,
            periodo,
            estado,
            acciones
        ];
    });
  
    $('#tabla-EvalObrero').DataTable({
        data: tableData,
        columns: [
            { title: "Cédula", width: "120px" },
            { title: "Nombre Completo" },
            { title: "Ubicación", width: "150px" },
            { title: "Cargo" },
            { title: "Año", width: "80px" },
            { title: "Período", width: "100px" },
            { title: "Estado", width: "100px" },
            { 
                title: "Acciones", 
                width: "120px",
                orderable: false,
                searchable: false
            }
        ],
        pageLength: 25,
        responsive: true,
        order: [[0, 'asc']],
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros por página",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            emptyTable: "No hay datos disponibles en la tabla",
            zeroRecords: "No se encontraron registros coincidentes",
            paginate: {
                previous: "Anterior",
                next: "Siguiente"
            }
        }
    });
  }
  
  /* ============================================================
     EDITAR PERIODO
  ============================================================ */
  
  function abrirModalEditarPeriodoObrero(idEvalObrero) {
  
    document.getElementById("form-modal-editar-periodo-obrero").reset();
    document.getElementById("id_eval_obreros_modal").value = idEvalObrero;
  
    $("#modalEditarPeriodoObrero").modal("show");
  }
  
  function ajustarFechasPeriodoModalObrero(periodo) {
    const yearInicio = document.getElementById("fecha_inicio_modal_obrero").value || new Date().getFullYear();
    const yearCierre = document.getElementById("fecha_cierre_modal_obrero").value || new Date().getFullYear();
  
    document.getElementById("fecha_inicio_modal_obrero").value = yearInicio;
    document.getElementById("fecha_cierre_modal_obrero").value = yearCierre;
  }
  
  async function actualizarPeriodoEvaluacionObrero() {
  
    let datos = capturarValoresFormulario('form-modal-editar-periodo-obrero');
  
    const periodo = datos.get("periodo_evaluacion");
    const yearInicio = datos.get("fecha_inicio");
    const yearCierre = datos.get("fecha_cierre");
  
    if (periodo === "Enero-Junio") {
      datos.set("fecha_inicio", yearInicio + "-01-01");
      datos.set("fecha_cierre", yearCierre + "-06-30");
    } 
    else if (periodo === "Julio-Diciembre") {
      datos.set("fecha_inicio", yearInicio + "-07-01");
      datos.set("fecha_cierre", yearCierre + "-12-31");
    }
  
    try {
      const resp = await microApi('controlador/?a_periodo_obrero', datos);
  
      listarEvalObrero();
      valorFormEvalObrero();
  
      Swal.fire({
        icon: 'success',
        title: 'Periodo actualizado',
        text: 'Las fechas y el periodo se actualizaron con éxito'
      });
  
      $("#modalEditarPeriodoObrero").modal("hide");
  
    } catch (err) {
      console.error("Error actualizando periodo:", err);
      Swal.fire({
        icon: 'error',
        title: 'Error inesperado',
        text: 'Ocurrió un error al actualizar el periodo'
      });
    }
  }
  
  /* ============================================================
     CAMBIAR ESTADO
  ============================================================ */
  
  async function cambiarEstadoEvalObrero(id_eval_obreros, estado_actual) {
  
    const nuevoEstado = estado_actual === 'Iniciada' ? 'Finalizada' : 'Iniciada';
  
    const result = await Swal.fire({
      title: `¿Cambiar estado a "${nuevoEstado}"?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, cambiar',
      cancelButtonText: 'Cancelar'
    });
  
    if (result.isConfirmed) {
  
      const datos = new FormData();
      datos.append('id_eval_obreros', id_eval_obreros);
      datos.append('estado_eval_obreros', nuevoEstado);
  
      try {
        const resp = await microApi('controlador/?cambiar_estadoEvalObrero', datos);
  
        listarEvalObrero();
  
        Swal.fire({
          icon: 'success',
          title: 'Estado cambiado',
          text: typeof resp === 'string' ? resp : 'El estado fue cambiado correctamente'
        });
  
      } catch (err) {
        console.error("Error al cambiar estado:", err);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Ocurrió un error al cambiar el estado'
        });
      }
    }
  }
  
  /* ============================================================
     LIMPIAR FORMULARIO
  ============================================================ */
  
  function valorFormEvalObrero(evaluado=''){
    document.getElementById('id_evaluado_obrero').value = evaluado;
  }
  
  /* ============================================================
     LISTAR EVALUADOS PARA SELECT
  ============================================================ */
  
  async function listarEvaluadosObrero() {
    try {
      const resp = await microApi('controlador/?listar_datos');
      llenarSelectEvaluadosObrero(resp);
    } catch (err) {
      console.error('Error al listar evaluados obreros:', err);
    }
  }
  
  function llenarSelectEvaluadosObrero(datos) {
    const select = document.getElementById('id_evaluado_obrero');
    if (!select) return;
  
    select.innerHTML = '<option value="">Seleccione a un usuario</option>';
  
    const registros = Array.isArray(datos[0]) ? datos.flat() : datos;
    const evaluados = registros.filter(item => item.rol === 'Evaluado');
  
    evaluados.forEach(item => {
      const fullname = item.nombre_completo || item.cedula_usuario;
  
      const opcion = document.createElement('option');
      opcion.value = item.id_evaluado;
      opcion.textContent = fullname;
      select.appendChild(opcion);
    });
  }
  