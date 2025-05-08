<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión | NetWorkEdu</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-white to-gray-100 min-h-screen text-gray-800">

<!-- Header común -->
<header class="bg-white shadow py-4 px-6">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between md:items-center gap-4">
    <h1 class="text-xl font-bold text-blue-700">NetWorkEdu</h1>
    <nav class="flex gap-3 text-sm">
      <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded">🔐 Login</span>
    </nav>
  </div>
</header>

<!-- Contenido -->
<main class="max-w-4xl mx-auto py-16 px-6 space-y-10">
  <h2 class="text-4xl font-extrabold text-blue-700">Bienvenido</h2>
  <p class="text-gray-600 mb-10 text-lg">Por favor, inicia sesión para acceder a tu panel de profesor o alumno.</p>

  <form action="../controllers/LoginController.php" method="POST" class="space-y-8 max-w-lg">

    <div>
      <label for="correo" class="block text-sm font-medium mb-1">Correo electrónico</label>
      <input type="email" id="correo" name="correo" required
             class="w-full border-b border-blue-500 bg-transparent py-2 px-1 text-lg focus:outline-none">
    </div>

    <div>
      <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
      <input type="password" id="password" name="password" required
             class="w-full border-b border-blue-500 bg-transparent py-2 px-1 text-lg focus:outline-none">
    </div>

    <div>
      <label for="rol" class="block text-sm font-medium mb-1">Selecciona tu rol</label>
      <select id="rol" name="rol" required
              class="w-full border border-gray-300 p-2 rounded bg-white">
        <option value="">-- Elige un rol --</option>
        <option value="profesor">Profesor</option>
        <option value="alumno">Alumno</option>
      </select>
    </div>

    <div>
      <button type="submit"
              class="inline-block bg-blue-600 text-white font-semibold py-3 px-6 rounded-full hover:bg-blue-700 transition">
        🚀 Entrar
      </button>
    </div>
  </form>
</main>

</body>
</html>
