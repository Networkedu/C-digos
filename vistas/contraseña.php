<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cambiar Contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">🔐 Cambiar Contraseña</h2>

    <?php if (isset($_GET['mensaje'])): ?>
      <p class="bg-blue-100 text-blue-800 px-4 py-2 mb-4 rounded">
        <?= htmlspecialchars($_GET['mensaje']) ?>
      </p>
    <?php endif; ?>

    <form action="../controllers/ContraseñaController.php" method="POST" class="space-y-4">
      <input type="password" name="actual" placeholder="Contraseña actual" required
             class="w-full border border-gray-300 p-3 rounded">
      <input type="password" name="nueva" placeholder="Nueva contraseña" required
             class="w-full border border-gray-300 p-3 rounded">
      <input type="password" name="confirmar" placeholder="Confirmar nueva contraseña" required
             class="w-full border border-gray-300 p-3 rounded">

      <button type="submit"
              class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700 transition">
        Cambiar Contraseña
      </button>
    </form>
  </div>
</body>
</html>
