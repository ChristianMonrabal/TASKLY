@extends('Admin.layouts.app')

@section('title', 'Gestión de Usuarios')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}">
@endsection

@auth
<script>
  window.currentUser = {
    id: {{ Auth::id() }},
    rol_id: {{ Auth::user()->rol_id }}
  };
</script>
@endauth

@section('content')
<div class="outer-border">
  <h3>GESTIÓN DE USUARIOS</h3>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <!-- Filtros -->
  <div class="mb-3">
    <input type="text" id="filterNombre"    placeholder="Nombre"          class="form-control d-inline-block" style="width:18%" oninput="filterUsuarios(1)">
    <input type="text" id="filterApellidos" placeholder="Apellidos"       class="form-control d-inline-block" style="width:18%" oninput="filterUsuarios(1)">
    <input type="text" id="filterCorreo"    placeholder="Correo"          class="form-control d-inline-block" style="width:18%" oninput="filterUsuarios(1)">
    <input type="text" id="filterDni"       placeholder="DNI"             class="form-control d-inline-block" style="width:18%" oninput="filterUsuarios(1)">
  </div>

  <!-- Botón Crear -->
  <button class="btn btn-success mb-3" onclick="openCreateModal()">
    <i class="fa fa-plus"></i> Crear Usuario
  </button>

  <!-- Tabla -->
  <table class="table table-bordered table-usuarios">
    <thead>
      <tr>
        <th>Nombre</th><th>Apellidos</th><th>Correo</th><th>Teléfono</th>
        <th>Fecha Nac.</th><th>DNI</th><th>Rol</th><th>Descripción</th><th>Estado</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody id="usuarios-container"></tbody>
  </table>

  <nav aria-label="Paginación">
    <ul id="usuarios-pagination" class="pagination justify-content-center"></ul>
  </nav>
</div>

<!-- Modal Crear Usuario -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="createUsuarioForm" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crear Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <div id="createErrors" class="alert alert-danger d-none"><ul></ul></div>

          <div class="mb-3">
            <label for="createNombre" class="form-label">Nombre</label>
            <input type="text" id="createNombre" name="nombre" class="form-control"
                   onblur="validateCreateNonEmpty('createNombre','Nombre obligatorio')">
            <div id="errorCreateNombre" class="error-message"></div>
          </div>

          <div class="mb-3">
            <label for="createApellidos" class="form-label">Apellidos</label>
            <input type="text" id="createApellidos" name="apellidos" class="form-control"
                   onblur="validateCreateNonEmpty('createApellidos','Apellidos obligatorios')">
            <div id="errorCreateApellidos" class="error-message"></div>
          </div>

          <div class="mb-3">
            <label for="createEmail" class="form-label">Correo</label>
            <input type="email" id="createEmail" name="email" class="form-control"
                   onblur="validateCreateEmail('createEmail')">
            <div id="errorCreateEmail" class="error-message"></div>
          </div>

          <div class="mb-3">
            <label for="createTelefono" class="form-label">Teléfono</label>
            <input type="text" id="createTelefono" name="telefono" class="form-control"
                   onblur="validateCreateNonEmpty('createTelefono','Teléfono obligatorio')">
            <div id="errorCreateTelefono" class="error-message"></div>
          </div>

          <div class="mb-3">
            <label for="createCodigoPostal" class="form-label">Código Postal</label>
            <input type="text" id="createCodigoPostal" name="codigo_postal" class="form-control"
                   onblur="validateCreateNonEmpty('createCodigoPostal','Código Postal obligatorio')">
            <div id="errorCreateCodigoPostal" class="error-message"></div>
          </div>

          <div class="mb-3">
            <label for="createFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" id="createFechaNacimiento" name="fecha_nacimiento" class="form-control"
                   onblur="validateCreateNonEmpty('createFechaNacimiento','Fecha de Nacimiento obligatoria')">
            <div id="errorCreateFechaNacimiento" class="error-message"></div>
          </div>

          <div class="mb-3">
            <label for="createDni" class="form-label">DNI</label>
            <input type="text" id="createDni" name="dni" class="form-control"
                   onblur="validateCreateNonEmpty('createDni','DNI obligatorio')">
            <div id="errorCreateDni" class="error-message"></div>
          </div>

          <div class="mb-3">
            <label for="createDescripcion" class="form-label">Descripción</label>
            <textarea id="createDescripcion" name="descripcion" class="form-control" rows="3"
                      onblur="validateCreateNonEmpty('createDescripcion','Descripción obligatoria')"></textarea>
            <div id="errorCreateDescripcion" class="error-message"></div>
          </div>

          <div class="mb-3">
            <label for="createFotoPerfil" class="form-label">Foto de Perfil</label>
            <input type="file" id="createFotoPerfil" name="foto_perfil" class="form-control" accept="image/*">
          </div>

          <div class="mb-3">
            <label for="createRol" class="form-label">Rol</label>
            <select id="createRol" name="rol_id" class="form-control">
              @foreach($roles as $rol)
                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="createPassword" class="form-label">Contraseña</label>
            <input type="password" id="createPassword" name="password" class="form-control"
                   onblur="validateCreatePassword()">
            <div id="errorCreatePassword" class="error-message"></div>
          </div>

          <div class="mb-3">
            <label for="createPasswordConfirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" id="createPasswordConfirmation" name="password_confirmation" class="form-control"
                   onblur="validateCreatePassword()">
            <div id="errorCreatePasswordConfirmation" class="error-message"></div>
          </div>

        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="submitCreateUsuario()">Crear</button>
        </div>
      </div>
    </form>
  </div>
</div>

    <!-- Modal: Editar Usuario -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
               <form id="editUsuarioForm" enctype="multipart/form-data">
                  <input type="hidden" id="editUsuarioId">
                  <div class="mb-3">
                      <label for="editNombre" class="form-label">Nombre</label>
                      <input type="text" name="nombre" id="editNombre" class="form-control" onblur="validateNonEmpty('editNombre', 'El campo Nombre es obligatorio')">
                      <div id="errorEditNombre" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editApellidos" class="form-label">Apellidos</label>
                      <input type="text" name="apellidos" id="editApellidos" class="form-control" onblur="validateNonEmpty('editApellidos', 'El campo Apellidos es obligatorio')">
                      <div id="errorEditApellidos" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editEmail" class="form-label">Correo</label>
                      <input type="email" name="email" id="editEmail" class="form-control" onblur="validateEmail('editEmail')">
                      <div id="errorEditEmail" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editTelefono" class="form-label">Teléfono</label>
                      <input type="text" name="telefono" id="editTelefono" class="form-control">
                      <div id="errorEditTelefono" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editCodigoPostal" class="form-label">Código Postal</label>
                      <input type="text" name="codigo_postal" id="editCodigoPostal" class="form-control">
                      <div id="errorEditCodigoPostal" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editFechaNacimiento" class="form-label">Fecha Nacimiento</label>
                      <input type="date" name="fecha_nacimiento" id="editFechaNacimiento" class="form-control">
                      <div id="errorEditFechaNacimiento" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editDni" class="form-label">DNI</label>
                      <input type="text" name="dni" id="editDni" class="form-control">
                      <div id="errorEditDni" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editDescripcion" class="form-label">Descripción</label>
                      <textarea name="descripcion" id="editDescripcion" class="form-control" rows="3" onblur="validateNonEmpty('editDescripcion', 'La descripción es obligatoria (si se requiere)')"></textarea>
                      <div id="errorEditDescripcion" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editFotoPerfil" class="form-label">Foto de Perfil</label>
                      <input type="file" name="foto_perfil" id="editFotoPerfil" class="form-control">
                      <div id="errorEditFotoPerfil" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editPassword" class="form-label">Nueva Contraseña</label>
                      <input type="password" name="password" id="editPassword" class="form-control" placeholder="Deje en blanco para mantener la actual" onblur="validateOptionalPassword()">
                      <div id="errorEditPassword" class="error-message"></div>
                  </div>
                  <div class="mb-3">
                      <label for="editPasswordConfirmation" class="form-label">Confirmar Nueva Contraseña</label>
                      <input type="password" name="password_confirmation" id="editPasswordConfirmation" class="form-control" placeholder="Deje en blanco para mantener la actual" onblur="validateOptionalPassword()">
                      <div id="errorEditPasswordConfirmation" class="error-message"></div>
                  </div>
               </form>
               <div id="editErrors" class="alert alert-danger d-none">
                   <ul></ul>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
               <button type="button" class="btn btn-primary" onclick="submitEditUsuario()">Actualizar</button>
            </div>
         </div>
      </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin-usuarios.js') }}"></script>
@endsection
