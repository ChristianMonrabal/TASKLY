@extends('Admin.layouts.app')

@section('title', 'Gestión de Usuarios')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}">
@endsection

@section('content')

    <div class="outer-border">
        <h3>GESTIÓN DE USUARIOS</h3>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
            <!-- Sección de filtros -->
        <div class="mb-3">
            <input type="text" id="filterNombre" placeholder="Nombre" class="form-control d-inline-block" style="width: 18%;" oninput="filterUsuarios()">
            <input type="text" id="filterApellidos" placeholder="Apellidos" class="form-control d-inline-block" style="width: 18%;" oninput="filterUsuarios()">
            <input type="text" id="filterCorreo" placeholder="Correo" class="form-control d-inline-block" style="width: 18%;" oninput="filterUsuarios()">
            <input type="number" id="filterCodigoPostal" placeholder="Código Postal" class="form-control d-inline-block" style="width: 18%;" oninput="filterUsuarios()">
            <input type="text" id="filterDni" placeholder="DNI" class="form-control d-inline-block" style="width: 18%;" oninput="filterUsuarios()">
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Código Postal</th>
                    <th>Fecha Nacimiento</th>
                    <th>DNI</th>
                    <th>Rol</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="usuarios-container">
                <!-- Se llenará dinámicamente mediante JS -->
            </tbody>
        </table>
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
