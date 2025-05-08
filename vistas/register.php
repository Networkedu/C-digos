<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Registro | NetWorkEdu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-white to-gray-100 min-h-screen text-gray-800">

<!-- Header común -->
<header class="bg-white shadow py-4 px-6">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between md:items-center gap-4">
    <h1 class="text-xl font-bold text-blue-700">NetWorkEdu</h1>
    <nav class="flex gap-3 text-sm">
      <a href="login.php" class="px-3 py-1 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 transition">🔐 Iniciar sesión</a>
      <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded">📝 Registro</span>
    </nav>
  </div>
</header>

<!-- Contenido -->
<main class="max-w-4xl mx-auto py-16 px-6 space-y-10">
  <h2 class="text-4xl font-extrabold text-blue-700">Crear Cuenta</h2>
  <p class="text-gray-600 mb-10 text-lg">Completa los siguientes datos para registrarte en la plataforma.</p>

  <form action="../controllers/register.php" method="POST" class="space-y-6 max-w-lg">

    <div>
      <label class="block text-sm font-medium mb-1">📧 Correo electrónico</label>
      <input type="email" name="correo" required
             class="w-full border-b border-blue-500 bg-transparent py-2 px-1 text-lg focus:outline-none">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">🔒 Contraseña</label>
      <input type="password" name="password" required
             class="w-full border-b border-blue-500 bg-transparent py-2 px-1 text-lg focus:outline-none">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">🧑 Nombre completo</label>
      <input type="text" name="nombre" required
             class="w-full border-b border-blue-500 bg-transparent py-2 px-1 text-lg focus:outline-none">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">🎓 Tipo de usuario</label>
      <select name="rol" required
              class="w-full border border-gray-300 p-2 rounded bg-white">
        <option value="">-- Selecciona un rol --</option>
        <option value="alumno">Alumno</option>
        <option value="profesor">Profesor</option>
      </select>
    </div>

    <div>
      <button type="submit"
              class="inline-block bg-blue-600 text-white font-semibold py-3 px-6 rounded-full hover:bg-blue-700 transition">
        ✅ Crear Cuenta
      </button>
    </div>

    <p class="text-center text-sm text-gray-600 mt-2">
      ¿Ya tienes una cuenta? 
      <a href="login.php" class="text-blue-600 hover:underline">Inicia sesión</a>
    </p>
  </form>
</main>

</body>
</html>
