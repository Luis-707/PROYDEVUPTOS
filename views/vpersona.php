<style>
        /* Estilo para el borde derecho de la columna 1 */
        #columna-izquierda {
            position: relative;
            border-right: 2px solid #ccc;
            width: 50%; /* Ancho inicial de la columna */
        }

        /* Área de arrastre en el borde derecho */
        #drag-area {
            position: absolute;
            right: -5px; /* Posición en el borde */
            top: 0;
            width: 10px; /* Ancho del área de arrastre */
            height: 100%;
            cursor: ew-resize; /* Cursor de redimensionamiento horizontal */
            z-index: 1000;
            background-color: rgba(0, 0, 0, 0.1); /* Color de fondo para visualización */
        }

        /* Estilo para la columna derecha */
        #columna-derecha {
            flex: 1; /* Ocupa el espacio restante */
        }
    </style>

<div class="container mt-5">
        <div class="row" class="col-md-6">
          <table style="width: 500px;position: fixed;">
               <tr>
                    <td onclick='guardarPersona()'>G</td>
                    <td>B</td>
                    <td>A</td>
                    <td>E</td>
               </tr>
          </table>
        </div>
        <br>
        <div class="row">
            <!-- Primera columna: Formulario -->
            <div class="col-md-6" id="columna-izquierda">
                <!-- Área de arrastre -->
                <div id="drag-area"></div>
                <div id="contenedor">
                    <div id="id_persona">
                        <h2 class="mb-4">DATOS PERSONALES</h2>
                        <form id="formulario_persona">
                            <div class="mb-3">
                                <label for="id_cedula" class="form-label">Cédula:</label>
                                <input type="text" class="form-control" name="cedula" id="id_cedula" required>
                            </div>
                            <div class="mb-3">
                                <label for="id_apellidos" class="form-label">Apellidos:</label>
                                <input type="text" class="form-control" name="apellidos" id="id_apellidos" required>
                            </div>
                            <div class="mb-3">
                                <label for="id_nombres" class="form-label">Nombres:</label>
                                <input type="text" class="form-control" name="nombres" id="id_nombres" required>
                            </div>
                            <div class="mb-3">
                                <label for="id_fecha_nac" class="form-label">Fecha de Nacimiento:</label>
                                <input type="date" class="form-control" name="fecha_nac" id="id_fecha_nac" required>
                            </div>
                            <div class="mb-3">
                                <label for="id_sexo" class="form-label">Sexo:</label>
                                <select class="form-select" name="sexo" id="id_sexo" required>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="id_estado_ci" class="form-label">Estado Civil:</label>
                                <select class="form-select" name="estado_ci" id="id_estado_ci" required>
                                    <option value="...">Elegir</option>
                                    <option value="Soltero">Soltero</option>
                                    <option value="Soltera">Soltera</option>
                                    <option value="Casado">Casado</option>
                                    <option value="Casada">Casada</option>
                                    <option value="Divorciado">Divorciado</option>
                                    <option value="Divorciada">Divorciada</option>
                                    <option value="Concubinato">Concubinato</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nacionalidad:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="nacionalidad" id="id_ve" value="VE" checked>
                                    <label class="form-check-label" for="id_ve">Venezolana</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="nacionalidad" id="id_ex" value="EX">
                                    <label class="form-check-label" for="id_ex">Extranjero</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="id_ciudad" class="form-label">Ciudad de Nacimiento:</label>
                                <input type="text" class="form-control" name="ciudad" id="id_ciudad">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dirección:</label>
                                <div class="mb-3">
                                    <label for="id_estado" class="form-label">Estado:</label>
                                    <select class="form-select" name="estado" id="id_estado">
                                        <option value="...">Elegir</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="id_municipio" class="form-label">Municipio:</label>
                                    <select class="form-select" name="municipio" id="id_municipio">
                                        <option value="...">Elegir</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="id_parroquia" class="form-label">Parroquia:</label>
                                    <select class="form-select" name="parroquia" id="id_parroquia">
                                        <option value="...">Elegir</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="id_comunidad" class="form-label">Comunidad:</label>
                                    <select class="form-select" name="comunidad" id="id_comunidad">
                                        <option value="...">Elegir</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="id_sector" class="form-label">Sector:</label>
                                    <select class="form-select" name="sector" id="id_sector">
                                        <option value="...">Elegir</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="id_correo" class="form-label">Correo Electrónico:</label>
                                <input type="email" class="form-control" name="correo" id="id_correo">
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono:</label>
                                <input type="text" class="form-control" name="telefono" id="telefono">
                            </div>
                            <div class="mb-3">
                                <label for="id_datos" class="form-label">Otros Datos:</label>
                                <textarea class="form-control" name="datos" id="id_datos" rows="5">Ingrese la información de interés</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Segunda columna: Vacía -->
            <div class="col-md-6" id="columna-derecha">
                <!-- Esta columna está vacía -->
            </div>
        </div>
    </div>

    <!-- JavaScript Puro -->
    <script>
        const dragArea = document.getElementById('drag-area');
        const columnaIzquierda = document.getElementById('columna-izquierda');
        let isDragging = false;
        let startX, startWidth;

        // Evento para iniciar el arrastre
        dragArea.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.clientX; // Posición inicial del mouse en X
            startWidth = columnaIzquierda.offsetWidth; // Ancho inicial de la columna
            document.body.style.userSelect = 'none'; // Evitar selección de texto durante el arrastre
        });

        // Evento para detener el arrastre
        document.addEventListener('mouseup', () => {
            isDragging = false;
            document.body.style.userSelect = 'auto'; // Restaurar selección de texto
        });

        // Evento para mover el mouse
        document.addEventListener('mousemove', (e) => {
            if (isDragging) {
                const deltaX = e.clientX - startX; // Diferencia en la posición del mouse
                const newWidth = startWidth + deltaX; // Nuevo ancho de la columna

                // Limitar el ancho mínimo y máximo
                const minWidth = 200; // Ancho mínimo de la columna
                const maxWidth = window.innerWidth * 0.8; // Ancho máximo (80% del ancho de la ventana)

                if (newWidth >= minWidth && newWidth <= maxWidth) {
                    columnaIzquierda.style.width = `${newWidth}px`; // Actualizar el ancho de la columna
                }
            }
        });

/*
        function guardarPersona(){
          alert('funciona');
          let datosPersona = capturarValoresFormulario('formulario_persona');

          var resp = microApi('controlador/?g_persona',datosPersona);
        }
*/

    </script>