<style>
        /* Estilo para el borde derecho de la columna 1 */
        #columna-izquierda-estado {
            position: relative;
            border-right: 2px solid #ccc;
            width: 30%; /* Ancho inicial de la columna */
            overflow-y:auto;
        }

        /* Área de arrastre en el borde derecho */
        #drag-area-estado {
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
        #columna-derecha-estado {
            flex: 1; /* Ocupa el espacio restante */
        }
    </style>

<div class="container mt-5">
        <div class="row" class="col-md-6">
          <table style="width: 500px;">
               <tr>
                    <td onclick="guardarEstado()"><img src="img/iconos/guardar.png" style="cursor:pointer;width: 40px;"></td>
                    <td onclick="listarEstado()"><img src="img/iconos/listar.png" style="cursor:pointer;width: 40px;"></td>
                    <td onclick="actualizarEstado()"><img src="img/iconos/actualizar.png" style="cursor:pointer;width: 40px;"></td>
                    <!--<td><img src="img/iconos/eliminar.png" style="cursor:pointer;width: 60px;"></td> -->
               </tr>
          </table>
        </div>
      
        <div class="row">
            <!-- Primera columna: Formulario -->
            <div class="col-md-6" id="columna-izquierda-estado">
                <!-- Área de arrastre -->
                <div id="drag-area-esuario"></div>
              
                <div id="contenedor-estado">
    <div id="id_estado">
        <h3 class="mb-4">DATOS USUARIOS</h3>
        <form id="formulario_estado">
            <!-- Campo Usuario -->
            <div class="mb-3">
                <label for="id_login" class="form-label">Usuario:</label>
                <input type="text" class="form-control" name="login" id="id_login" required>
            </div>

         
        </form>
    </div>
</div>
            </div>

            <!-- Segunda columna: Vacía -->
            <div class="col-md-6" id="columna-derecha-estado">
                <!-- Esta columna está vacía -->
                <table id="tabla-estado" class="table table-striped"> 
                 <!--   <thead  class="table-dark">
                        <tr>
                            <th>Usuario</th>
                            <th>Clave</th>
                            <th>Correo</th>
                            <th></th>
                        </tr>
                    </thead>  -->
                    <thead class="table-dark">
                        <tr>
                            <th>Usuario</th>
                            <th>Clave</th>
                            <th>Cédula</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Correo</th>
                            <th>Foto</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <!-- Aquí se insertarán las filas dinámica -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript Puro -->
    <script>
        var idDragArea='drag-area-estado';
        var idColumnaIzquierda='columna-izquierda-estado';
        desplazamientoVista(idDragArea,idColumnaIzquierda);
    </script> 