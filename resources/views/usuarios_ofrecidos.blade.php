<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuarios Ofrecidos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .profile-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #ccc;
    }
    .star-rating {
      color: gold;
      font-size: 1.2rem;
    }
  </style>
</head>
<body>

<div class="container mt-4">
  <h1 class="text-center mb-4">Usuarios que se han ofrecido</h1>

  <!-- Lista de usuarios -->
  <div class="list-group">
    
    <div class="list-group-item d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Perfil Juan" class="profile-img me-3">
        <div>
          <h5 class="mb-1">Juan Pérez</h5>
          <div class="star-rating">
            ★★★★☆
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <small class="text-muted">01/04/2025</small>
        <a href="/chat" class="btn btn-primary btn-sm">Chatear</a>
      </div>
    </div>

    <div class="list-group-item d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Perfil María" class="profile-img me-3">
        <div>
          <h5 class="mb-1">María González</h5>
          <div class="star-rating">
            ★★★★★
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <small class="text-muted">02/04/2025</small>
        <a href="/chat" class="btn btn-primary btn-sm">Chatear</a>
      </div>
    </div>

    <div class="list-group-item d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="Perfil Carlos" class="profile-img me-3">
        <div>
          <h5 class="mb-1">Carlos Martínez</h5>
          <div class="star-rating">
            ★★★☆☆
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <small class="text-muted">03/04/2025</small>
        <a href="/chat" class="btn btn-primary btn-sm">Chatear</a>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
