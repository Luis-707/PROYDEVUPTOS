let graficaRangos = null;
let registrosGlobales = [];

/* ============================================================
   FUNCIÓN PRINCIPAL PARA INICIALIZAR LA VISTA DE GRÁFICAS
============================================================ */
async function inicializarGraficas() {

    console.log("Inicializando gráficas…");

    const resp = await obtenerDatosPersonalesGraficas();

    console.log("Respuesta del backend:", resp);

    if (!resp) {
        console.error("Respuesta vacía:", resp);
        return;
    }

    // Normalizar estructura del backend
if (resp.data && Array.isArray(resp.data)) {

    // Caso: data = [ [ {…}, {…} ] ]
    if (Array.isArray(resp.data[0])) {
        registrosGlobales = resp.data[0];

    // Caso: data = [ {…}, {…} ]
    } else {
        registrosGlobales = resp.data;
    }

} else {
    registrosGlobales = [];
}
    

    console.log("Datos normalizados:", registrosGlobales);

    console.log("resp.data:", resp.data);
console.log("resp.data[0]:", resp.data[0]);
console.log("resp.data[0][0]:", resp.data[0][0]);

    cargarFiltros();
    construirGrafica();
}

/* ============================================================
   CARGAR FILTROS (AÑO + PERÍODO)
============================================================ */
function cargarFiltros() {

    const filtroAnio = document.getElementById("filtroAnio");
    const filtroPeriodo = document.getElementById("filtroPeriodo");

    if (!filtroAnio || !filtroPeriodo) {
        console.warn("⚠️ La vista aún no está lista para cargar filtros.");
        return;
    }

    console.log("Cargando filtros…");

    // Años únicos
    const anios = [...new Set(registrosGlobales.map(r => r.anio_inicio))];

    console.log("Años detectados:", anios);

    filtroAnio.innerHTML = `<option value="todos">Todos los años</option>`;
    anios.forEach(a => {
        if (a) filtroAnio.innerHTML += `<option value="${a}">${a}</option>`;
    });

    // Períodos únicos
    const periodos = [...new Set(registrosGlobales.map(r => r.periodo_evaluado))];

    console.log("Períodos detectados:", periodos);

    filtroPeriodo.innerHTML = `<option value="todos">Todos los períodos</option>`;
    periodos.forEach(p => {
        if (p) filtroPeriodo.innerHTML += `<option value="${p}">${p}</option>`;
    });

    filtroAnio.addEventListener("change", () => {
    construirGrafica();
    
});

filtroPeriodo.addEventListener("change", () => {
    construirGrafica();
    
});
}

/* ============================================================
   CONSTRUIR GRÁFICA CON FILTROS
============================================================ */
function construirGrafica() {

    const filtroAnio = document.getElementById("filtroAnio");
    const filtroPeriodo = document.getElementById("filtroPeriodo");
    const canvas = document.getElementById("graficaRangos");

    if (!filtroAnio || !filtroPeriodo || !canvas) {
        console.warn("⚠️ La vista aún no está lista para construir la gráfica.");
        return;
    }

    const anioSel = filtroAnio.value;
    const periodoSel = filtroPeriodo.value;

    console.log("Filtrando por:", anioSel, periodoSel);

    // Filtrar por año
    let filtrados = registrosGlobales;
    if (anioSel !== "todos") {
        filtrados = filtrados.filter(r => r.anio_inicio == anioSel);
    }

    // Filtrar por período
    if (periodoSel !== "todos") {
        filtrados = filtrados.filter(r => r.periodo_evaluado == periodoSel);
    }

    console.log("Filtrados:", filtrados);

    // Contar rangos
    const conteo = {};
    filtrados.forEach(r => {
        const rango = r.rango_actuacion || "Sin rango";
        conteo[rango] = (conteo[rango] || 0) + 1;
    });

    const labels = Object.keys(conteo);
    const valores = Object.values(conteo);

    console.log("Conteo:", conteo);

    // Destruir gráfica previa
    if (graficaRangos) graficaRangos.destroy();

    graficaRangos = new Chart(canvas, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Cantidad de evaluados",
                data: valores,
                backgroundColor: "#ffeb55"
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

/* ============================================================
   SERVICIO BACKEND
============================================================ */
async function obtenerDatosPersonalesGraficas() {
    try {
        return await microApi('controlador/?lista_resultados_graficas');
    } catch (error) {
        console.error("Error al obtener datos administrativos:", error);
        return null;
    }
}