<!-- Formulario demo -->
<link rel="stylesheet" href="assets/vendor/libs/pickr/pickr-themes.css" />
<link rel="stylesheet" href="assets/vendor/css/core.css" />
<link rel="stylesheet" href="assets/css/demo.css" />
<link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />
<link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />

<div class="row mb-4 g-6">
    <div class="col-md-12 col-lg-12">
        <h6 class="mt-2 text-body-secondary">Formulario DEMO</h6>
        <div class="card mb-6">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-1 me-2">Registro de persona</h5>
                    <p class="card-subtitle">Ingrese los datos solicitados en el siguiente formulario</p>
                </div>
            </div>
            <div class="card-body">
                <form id="form_persona">
                    <input type="hidden" id="id_persona">
                    <div class="row">
                        <div class="mb-4 col-md-6">
                            <label class="form-label" for="cedula">Documento de identidad</label>
                            <input type="text" class="form-control" id="cedula" placeholder="" />
                        </div>
                        <div class="mb-4 col-md-6">
                            <label class="form-label" for="nombre_apellido">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre_apellido" placeholder="" />
                        </div>
                        <div class="mb-4 col-md-6">
                            <label class="form-label" for="email">Correo electrónico</label>
                            <input type="text" class="form-control" id="email" placeholder="" />
                        </div>
                        <div class="mb-4 col-md-6">
                            <label class="form-label" for="telefono">Teléfono</label>
                            <input type="text" id="telefono" class="form-control"
                                placeholder="04120000000" />
                        </div>
                        
                        <div style="text-align: right;">
                            <button type="button" class="btn btn-primary" onclick="guardar()">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div>

    <div class="card">
        <h5 class="card-header">Personas registradas</h5>
        <div class="table-responsive text-nowrap">
          <table class="table" id="list_persona"></table>
          <!-- <table class="table" id="list_persona">
            <thead>
              <tr>
                <th>Cédula</th>
                <th>Nombre completo</th>
                <th>Correo electrónico</th>
                <th>Teléfono</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              <tr>
                <td>19238298</td>
                <td>Gustavo Savignac</td>
                <td>gustavo.savignac@gmail.com</td>
                <td>04248164621</td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base bx bx-edit-alt me-1"></i> Editar</a>
                      <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base bx bx-trash me-1"></i> Eliminar</a>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table> -->
        </div>
      </div>
</div>

<!-- Vendors JS -->
<script src="assets/js/funciones.js"></script>
<script src="views/js/form-demo.js"></script>