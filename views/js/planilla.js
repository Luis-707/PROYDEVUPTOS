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









