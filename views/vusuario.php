
<div class="nav-align-top nav-tabs-shadow">
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <button
        type="button"
        class="nav-link active"
        role="tab"
        data-bs-toggle="tab"
        data-bs-target="#navs-top-home"
        aria-controls="navs-top-home"
        aria-selected="true">
        Home
      </button>
    </li>
    <li class="nav-item">
      <button
        type="button"
        class="nav-link"
        role="tab"
        data-bs-toggle="tab"
        data-bs-target="#navs-top-profile"
        aria-controls="navs-top-profile"
        aria-selected="false">
        Profile
      </button>
    </li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
        <!-- <p>PANEL 1</p> -------------------------------------------------------------------------------------- -->
        <div id="contenedor-usuario">
            <div class="row">
                <table style="width: 500px;">
                    <tr>
                            <td onclick="validar_form(1)"><img src="img/iconos/guardar.png" style="cursor:pointer;width: 40px;"></td>
                            <td onclick="listarUsuario()"><img src="img/iconos/listar.png" style="cursor:pointer;width: 40px;"></td>
                            <td onclick="validar_form(2)"><img src="img/iconos/actualizar.png" style="cursor:pointer;width: 40px;"></td>
                            <!--<td><img src="img/iconos/eliminar.png" style="cursor:pointer;width: 60px;"></td> -->
                    </tr>
                </table>
            </div>

            <div id="id_usuario">
                <h3 class="mb-4">DATOS USUARIOS</h3>
                <form id="formulario_usuario">
                    <!-- Campo Usuario -->
                    <div class="mb-3">
                        <label for="id_login" class="form-label">Usuario:</label>
                        <input type="text" class="form-control" name="login" id="id_login" required>
                    </div>

                    <!-- Campo Clave -->
                    <div class="mb-3">
                        <label for="id_clave" class="form-label">Clave:</label>
                        <input type="password" onchange="pa(this.value)" class="form-control" name="passw" id="id_clave" required>
                    </div>

                    <!-- Campo Cédula -->
                    <div class="mb-3">
                        <label for="id_cedula" class="form-label">Cédula:</label>
                        <input type="text" class="form-control" name="cedula" id="id_cedula" maxlength="9" placeholder="Ej: 123456789" required>
                    </div>

                    <!-- Campo Nombres -->
                    <div class="mb-3">
                        <label for="id_nombres" class="form-label">Nombres:</label>
                        <input type="text" class="form-control" name="nombres" id="id_nombres" maxlength="35" required>
                    </div>

                    <!-- Campo Apellidos -->
                    <div class="mb-3">
                        <label for="id_apellidos" class="form-label">Apellidos:</label>
                        <input type="text" class="form-control" name="apellidos" id="id_apellidos" maxlength="35" required>
                    </div>

                    <!-- Campo Correo -->
                    <div class="mb-3">
                        <label for="id_mail" class="form-label">Correo:</label>
                        <input type="email" class="form-control" name="correo" id="id_mail" maxlength="35" required>
                    </div>

                    <!-- Campo Foto -->
                    <div class="mb-3">
                        <label for="id_foto" class="form-label">Foto (URL):</label>
                        <input type="url" class="form-control" name="foto" id="id_foto" maxlength="254" placeholder="https://ejemplo.com/foto.jpg">
                    </div>

                    <!-- Campo Estatus -->
                    <div class="mb-3">
                        <label for="id_estatus" class="form-label">Estatus:</label>
                        <select class="form-select" name="estatus" id="id_estatus" required>
                            <option value="" disabled selected>Selecciona un estatus</option>
                            <option value="ACTIVO">Activo</option>
                            <option value="INACTIVO">Inactivo</option>
                            
                        </select>
                    </div>

                    <!-- Botón de envío 
                    <button type="submit" class="btn btn-primary">Enviar</button> -->
                </form>
            </div>
        </div>
      <!-- ----------------------------------------------------------------------------------------------------- -->
    </div>

    <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
      <!-- <p>PANEL 2</p> -------------------------------------------------------------------------------------- -->
        <div id="contenedor-tabla-usuario">
            <!-- Esta columna está vacía -->
            <table id="tabla-usuarios" class="table table-striped"> 
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
      <!-- ----------------------------------------------------------------------------------------------------- -->
    </div>
  </div>
</div>