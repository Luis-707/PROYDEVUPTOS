let datosComparativo = [];
let graficaComparativa = null;

/* ============================================================
   FUNCIÓN DE NORMALIZACIÓN (evita problemas de tildes, espacios, mayúsculas)
============================================================ */
function normalizar(texto) {
    return texto
        ?.toString()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .trim()
        .toLowerCase();
}

/* ============================================================
   LLENAR SELECT DE AÑOS
============================================================ */
function llenarAniosComparativo() {
    const select = document.getElementById("filtroAnioComparativo");
    const anioActual = new Date().getFullYear();

    select.innerHTML = "";

    for (let a = anioActual; a >= anioActual - 5; a--) {
        select.innerHTML += `<option value="${a}">${a}</option>`;
    }
}

/* ============================================================
   CARGAR DATOS DEL BACKEND
============================================================ */
async function cargarComparativo() {

    const anioActual = document.getElementById("filtroAnioComparativo").value;
    const periodo = document.getElementById("filtroPeriodoComparativo").value;

    const datos = new FormData();
    datos.append("anio_actual", anioActual);
    datos.append("periodo", periodo);

    try {
        const resp = await microApi('controlador/?lista_comparativo_rangos', datos);

        console.log("Respuesta del backend:", resp);
        console.log("Datos comparativo:", resp?.data);

        if (!resp || resp.success !== true) {
            console.warn("Error en respuesta del backend:", resp?.message);
            return;
        }

        // 🔥 APLANAR ARRAY SI VIENE ANIDADO
        datosComparativo = Array.isArray(resp.data[0]) ? resp.data[0] : resp.data;

        // 🔥 Convertir tipos correctamente
        datosComparativo = datosComparativo.map(r => ({
            anio: Number(r.anio),
            periodo: Number(r.periodo),
            rango: r.rango,
            porcentaje: Number(r.porcentaje)
        }));

        construirGraficaComparativa();
        calcularDestacados();

    } catch (error) {
        console.error("Error al cargar comparativo:", error);
    }
}

/* ============================================================
   CONSTRUIR GRÁFICA COMPARATIVA
============================================================ */
function construirGraficaComparativa() {

    const canvas = document.getElementById("graficaComparativa");
    if (!canvas) return;

    const anioActual = Number(document.getElementById("filtroAnioComparativo").value);
    const anioAnterior = anioActual - 1;
    const periodo = Number(document.getElementById("filtroPeriodoComparativo").value);

    const datosPeriodo = datosComparativo.filter(r => r.periodo === periodo);

    // RANGOS EXACTOS COMO ESTÁN EN TU BD
    const rangos = [
        "Actuacion muy por debajo de lo esperado",
        "Actuacion por debajo de lo esperado",
        "Actuacion dentro de lo esperado",
        "Actuacion sobre lo esperado",
        "Desempeño excepcional"
    ];

    const serieAnterior = rangos.map(r =>
        datosPeriodo.find(x => normalizar(x.rango) === normalizar(r) && x.anio === anioAnterior)?.porcentaje || 0
    );

    const serieActual = rangos.map(r =>
        datosPeriodo.find(x => normalizar(x.rango) === normalizar(r) && x.anio === anioActual)?.porcentaje || 0
    );

    if (graficaComparativa) graficaComparativa.destroy();

    graficaComparativa = new Chart(canvas, {
        type: "bar",
        data: {
            labels: rangos,
            datasets: [
                {
                    label: `Año ${anioAnterior}`,
                    data: serieAnterior,
                    backgroundColor: "rgba(54, 162, 235, 0.7)"
                },
                {
                    label: `Año ${anioActual}`,
                    data: serieActual,
                    backgroundColor: "rgba(75, 192, 192, 0.7)"
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

/* ============================================================
   DESTACADOS DEL PERÍODO
============================================================ */
function calcularDestacados() {

    const anioActual = Number(document.getElementById("filtroAnioComparativo").value);
    const anioAnterior = anioActual - 1;
    const periodo = Number(document.getElementById("filtroPeriodoComparativo").value);

    const datosPeriodo = datosComparativo.filter(r => r.periodo === periodo);

    const rangos = [
        "Actuacion muy por debajo de lo esperado",
        "Actuacion por debajo de lo esperado",
        "Actuacion dentro de lo esperado",
        "Actuacion sobre lo esperado",
        "Desempeño excepcional"
    ];

    let mayorInc = { rango: "-", valor: -999 };
    let mayorDesc = { rango: "-", valor: 999 };
    let totalActual = 0;

    rangos.forEach(r => {
        const anterior = datosPeriodo.find(x => normalizar(x.rango) === normalizar(r) && x.anio === anioAnterior)?.porcentaje || 0;
        const actual = datosPeriodo.find(x => normalizar(x.rango) === normalizar(r) && x.anio === anioActual)?.porcentaje || 0;

        const delta = actual - anterior;

        if (delta > mayorInc.valor) mayorInc = { rango: r, valor: delta };
        if (delta < mayorDesc.valor) mayorDesc = { rango: r, valor: delta };

        totalActual += actual;
    });

    document.getElementById("destMayorIncremento").innerText =
        `${mayorInc.rango} (${mayorInc.valor.toFixed(1)}%)`;

    document.getElementById("destMayorDescenso").innerText =
        `${mayorDesc.rango} (${mayorDesc.valor.toFixed(1)}%)`;

    document.getElementById("destParticipacion").innerText =
        `${totalActual.toFixed(1)}%`;
}