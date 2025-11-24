async function cargarTablasPlanilla() {
    const cedula = sessionStorage.getItem("cedula_planilla");
    if (!cedula) {
      alert("No se seleccionó evaluado");
      return;
    }
  
    // Enviar cédula para obtener objetivos filtrados
    const formData = new FormData();
    formData.append("cedula_usuario", cedula);
  
    // Petición a endpoint que entrega objetivos filtrados
    const objResp = await microApi('controlador/?l_objetivos', formData);
    const objetivos = Array.isArray(objResp[0]) ? objResp[0] : objResp;
    renderTablaDinamica(objetivos, "tabla-objetivos", "total-objetivos", "peso_objetivo", "nombre_objetivo");
  
    // Competencias siguen normales sin filtro
    const compResp = await microApi('controlador/?l_competencias');
    const competencias = Array.isArray(compResp[0]) ? compResp[0] : compResp;
    renderTablaDinamica(competencias, "tabla-competencias", "total-competencias", "peso_competencia", "nombre_competencia");
  
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
  