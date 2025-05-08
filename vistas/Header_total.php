<?php
if (!isset($_SESSION)) session_start();

$nombre = $_SESSION['Nombre'] ?? 'Usuario';
$tipo = isset($_SESSION['ID_Alumno']) ? 'alumno' : 'profe';
$foto = $_SESSION['FotoPerfil'] ?? null;
$inicial = strtoupper(mb_substr($nombre, 0, 1));
?>

<style>
  .profile-menu {
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-family: 'Inter', sans-serif;
    z-index: 200;
  }
  .profile-pic {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background-color: #e5e7eb;
    color: #4f46e5;
    font-weight: bold;
    font-size: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
    transition: transform 0.2s ease;
  }
  .profile-pic:hover {
    transform: scale(1.05);
  }
  .dropdown {
    display: none;
    position: absolute;
    right: 0;
    margin-top: 0.5rem;
    background: white;
    border: 1px solid #ddd;
    border-radius: 0.5rem;
    box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    min-width: 180px;
    z-index: 300;
  }
  .dropdown a {
    display: block;
    padding: 0.9rem 1.2rem;
    color: #333;
    text-decoration: none;
    font-size: 1rem;
    border-bottom: 1px solid #eee;
  }
  .dropdown a:last-child {
    border-bottom: none;
  }
  .dropdown a:hover {
    background-color: #f3f4f6;
  }
</style>

<div class="profile-menu">
  <div class="profile-pic" onclick="toggleDropdown()">
    <?php if ($foto): ?>
      <img src="<?= htmlspecialchars($foto) ?>" alt="Perfil" style="width:100%; height:100%; object-fit: cover;">
    <?php else: ?>
      <?= $inicial ?>
    <?php endif; ?>
  </div>
  <div class="dropdown" id="dropdownMenu">
    <a href="../views/seguridad.php">🔐 Seguridad</a>
    <a href="../views/perfil.php">👤 Perfil</a>
    <a href="../logout.php">🚪 Cerrar sesión</a>
  </div>
</div>

<script>
  function toggleDropdown() {
    const menu = document.getElementById('dropdownMenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
  }

  document.addEventListener('click', function(e) {
    if (!e.target.closest('.profile-menu')) {
      document.getElementById('dropdownMenu').style.display = 'none';
    }
  });
</script>
