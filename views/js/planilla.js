async function cargarPlanilla() {
  const cedula = sessionStorage.getItem("cedula_planilla");
  console.log("📦 Cédula recuperada de sessionStorage:", cedula);
  if (!cedula) {
    alert("No se seleccionó evaluado");
    return;
  }

  // 1) JSON de empleados
  const empleadosResp = await microApi('views/js/datos_empleado.json');
  const empleados = Array.isArray(empleadosResp)
    ? (empleadosResp[0]?.data || empleadosResp[0] || [])
    : (empleadosResp?.data || []);

  // 2) Servicio PHP
  const formData = new FormData();
  formData.append("cedula_usuario", cedula);

  const resp = await microApi('controlador/?planilla', formData);

  if (!resp?.success) {
    alert(resp?.message || "Error cargando planilla");
    return;
  }

  const registro = resp.data;

  // 3) Evaluado
  const empEval = empleados.find(emp => emp.pin === registro.cedula_usuario || emp.pin_str === registro.cedula_usuario);
  document.getElementById("evaluado_fullname").textContent = empEval?.fullname || "N/D";
  document.getElementById("evaluado_cedula").textContent = registro.cedula_usuario || "N/D";
  document.getElementById("evaluado_cargo").textContent = registro.cargo_evaluado || "Sin cargo";
  document.getElementById("evaluado_ubicacion").textContent = empEval?.additional || "N/D";

  // 4) Evaluador
  const empEv = empleados.find(emp => emp.pin === registro.cedula_evaluador || emp.pin_str === registro.cedula_evaluador);
  document.getElementById("evaluador_fullname").textContent = empEv?.fullname || "N/D";
  document.getElementById("evaluador_cedula").textContent = registro.cedula_evaluador || "N/D";
  document.getElementById("evaluador_cargo").textContent = registro.cargo_evaluador || "Sin cargo";
  document.getElementById("evaluador_ubicacion").textContent = empEv?.additional || "N/D";

  // 5) Supervisor
  const empSup = empleados.find(emp => emp.pin === registro.cedula_supervisor || emp.pin_str === registro.cedula_supervisor);
  document.getElementById("supervisor_fullname").textContent = empSup?.fullname || "N/D";
  document.getElementById("supervisor_cedula").textContent = registro.cedula_supervisor || "N/D";
  document.getElementById("supervisor_cargo").textContent = registro.cargo_supervisor || "Sin cargo";
}


async function cargarTablasPlanilla() {
  
    // Objetivos
    const objResp = await microApi('controlador/?l_objetivos');
    const objetivos = Array.isArray(objResp[0]) ? objResp[0] : objResp;
    renderTablaDinamica(objetivos, "tabla-objetivos", "total-objetivos", "peso_objetivo", "nombre_objetivo");
  
    // Competencias
    const compResp = await microApi('controlador/?l_competencias');
    const competencias = Array.isArray(compResp[0]) ? compResp[0] : compResp;
    renderTablaDinamica(competencias, "tabla-competencias", "total-competencias", "peso_competencia", "nombre_competencia");
  

  // Inicializar cálculo de total general
  actualizarTotalGeneral();
}

function renderTablaDinamica(datos, idTabla, idTotal, campoPeso, campoNombre) {
  const tbody = document.querySelector(`#${idTabla} tbody`);
  tbody.innerHTML = "";

  datos.forEach(item => {
    const tr = document.createElement("tr");

    // Nombre
    const tdNombre = document.createElement("td");
    tdNombre.textContent = item[campoNombre];
    tr.appendChild(tdNombre);

    // Peso
    const tdPeso = document.createElement("td");
    tdPeso.textContent = item[campoPeso];
    tr.appendChild(tdPeso);

    // Rango (select 1-5)
    const tdRango = document.createElement("td");
    const select = document.createElement("select");
    for (let i = 1; i <= 5; i++) {
      const opt = document.createElement("option");
      opt.value = i;
      opt.textContent = i;
      select.appendChild(opt);
    }
    select.addEventListener("change", () => {
      const peso = parseFloat(item[campoPeso]);
      const rango = parseInt(select.value);
      tdPxR.textContent = peso * rango;
      actualizarTotales(idTabla, idTotal);
    });
    tdRango.appendChild(select);
    tr.appendChild(tdRango);

    // Peso x Rango
    const tdPxR = document.createElement("td");
    tdPxR.textContent = "0";
    tr.appendChild(tdPxR);

    tbody.appendChild(tr);
  });

  actualizarTotales(idTabla, idTotal);
}

function actualizarTotales(idTabla, idTotal) {
  const tbody = document.querySelector(`#${idTabla} tbody`);
  let total = 0;
  tbody.querySelectorAll("tr").forEach(tr => {
    const val = parseFloat(tr.children[3].textContent) || 0;
    total += val;
  });
  document.getElementById(idTotal).textContent = total;
  actualizarTotalGeneral();
}

function actualizarTotalGeneral() {
  const totalObj = parseFloat(document.getElementById("total-objetivos").textContent) || 0;
  const totalComp = parseFloat(document.getElementById("total-competencias").textContent) || 0;
  document.getElementById("total-general").textContent = totalObj + totalComp;
}





