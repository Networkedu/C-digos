<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$nombre = $_SESSION['Nombre_Profe'];
$id_profe = $_SESSION['ID_Profe'];

// Obtener todos los alumnos
$alumnos = [];
$res = $conn->query("SELECT ID_Alumno, Nombre FROM Alumno");
while ($row = $res->fetch_assoc()) {
    $alumnos[] = $row;
}

// Obtener clases del profesor
$clases_completas = [];
$sql = "SELECT * FROM clases2 WHERE ID_Profe = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ Error al preparar la consulta de clases2: " . $conn->error);
}
$stmt->bind_param("i", $id_profe);
$stmt->execute();
$result = $stmt->get_result();

// Recorrer clases y obtener alumnos por clase
while ($clase = $result->fetch_assoc()) {
    $id_clase = $clase['ID_Clase'];

    $subquery_sql = "SELECT a.Nombre FROM clase_alumno ca JOIN Alumno a ON ca.ID_Alumno = a.ID_Alumno WHERE ca.ID_Clase = ?";
    $subquery = $conn->prepare($subquery_sql);
    if (!$subquery) {
        die("❌ Error al preparar subconsulta: " . $conn->error);
    }

    $subquery->bind_param("i", $id_clase);
    $subquery->execute();
    $res_sub = $subquery->get_result();

    $clase['alumnos'] = [];
    while ($al = $res_sub->fetch_assoc()) {
        $clase['alumnos'][] = $al;
    }

    $clase['resaltada'] = isset($_GET['nueva_clase']) && $_GET['nueva_clase'] == $clase['ID_Clase'];
    $clases_completas[] = $clase;
}

// Ordenar para mostrar la clase reciente al final
usort($clases_completas, function($a, $b) {
    return ($a['resaltada'] ?? false) ? 1 : -1;
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Clases del Profesor</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Header -->
<header class="bg-white shadow py-4 px-6">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between md:items-center gap-4">
    <h1 class="text-xl font-semibold text-blue-700">NetWorkEdu</h1>
    <nav class="flex gap-3 text-sm">
      <a href="Panel_profesor.php" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">📚 Tareas</a>
      <a href="Index_profesor.php" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">🏠 Inicio</a>
      <a href="Clase_Profesor.php" class="px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition">🏫 Clases</a>
    </nav>
    <div class="flex items-center gap-4 text-sm">
      <a href="../controllers/logoutController.php" class="text-red-600 hover:underline">Cerrar sesión</a>
    </div>
  </div>
</header>

<!-- Main content -->
<main class="max-w-6xl mx-auto p-6 space-y-12">
  <h2 class="text-3xl font-bold text-blue-700 mb-4">📖 Clases de <?= htmlspecialchars($nombre) ?></h2>

  <!-- FORMULARIO CREAR CLASE -->
  <section class="space-y-6 border-t border-blue-200 pt-10">
    <h3 class="text-3xl font-extrabold text-blue-700">➕ Crear Nueva Clase</h3>

    <form action="../controllers/CrearClaseController.php" method="post" enctype="multipart/form-data" class="space-y-6">
      <!-- Nombre -->
      <div>
        <label class="block text-sm font-medium mb-1">Nombre de la clase</label>
        <input type="text" name="nombre_clase" placeholder="Nombre de la clase"
               class="w-full border-b border-blue-500 focus:outline-none bg-transparent py-2 px-1 text-lg" required>
      </div>

      <!-- Archivo -->
      <div>
        <label class="block text-sm font-medium mb-1">Archivo (opcional)</label>
        <input type="file" name="archivo" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
               class="block w-full text-sm text-gray-700">
      </div>

      <!-- Icono y color -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium mb-1">Icono de la clase</label>
          <select name="icono" class="w-full border border-gray-300 p-2 rounded bg-white">
            <option value="📘">📘 Libro</option>
            <option value="🧪">🧪 Ciencia</option>
            <option value="📐">📐 Matemáticas</option>
            <option value="🎨">🎨 Arte</option>
            <option value="💻">💻 Informática</option>
            <option value="🌍">🌍 Geografía</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Color de clase</label>
          <select name="color" class="w-full border border-gray-300 p-2 rounded bg-white">
            <option value="bg-blue-100">Azul</option>
            <option value="bg-green-100">Verde</option>
            <option value="bg-yellow-100">Amarillo</option>
            <option value="bg-red-100">Rojo</option>
            <option value="bg-purple-100">Morado</option>
            <option value="bg-gray-100">Gris</option>
          </select>
        </div>
      </div>

      <!-- Asignar alumnos -->
      <div>
        <p class="font-medium text-sm mb-2">Asignar alumnos</p>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-52 overflow-y-auto border p-2 rounded-lg bg-gray-50">
          <?php foreach ($alumnos as $alumno): ?>
            <label class="flex items-center gap-2 text-sm bg-white p-2 rounded shadow-sm hover:bg-gray-100">
              <input type="checkbox" name="alumnos[]" value="<?= $alumno['ID_Alumno'] ?>" class="accent-blue-600">
              <?= htmlspecialchars($alumno['Nombre']) ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Botón -->
      <div>
        <button type="submit" class="inline-block bg-blue-600 text-white font-semibold py-3 px-6 rounded-full hover:bg-blue-700 transition">
          🚀 Crear Clase
        </button>
      </div>
    </form>
  </section>

  <!-- LISTADO DE CLASES -->
  <section>
    <h3 class="text-xl font-semibold mb-4">📋 Clases Creadas</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <?php foreach ($clases_completas as $clase): ?>
        <div class="p-5 rounded-xl shadow <?= $clase['Color'] ?> <?= $clase['resaltada'] ? 'ring-4 ring-green-400' : '' ?>">
          <details class="w-full">
            <summary class="flex justify-between items-center cursor-pointer">
              <div class="text-lg font-bold">
                <?= $clase['Icono'] ?> <?= htmlspecialchars($clase['Nombre_Clase']) ?>
              </div>
              <span class="text-sm text-gray-600">ID: <?= $clase['ID_Clase'] ?></span>
            </summary>

            <?php if (!empty($clase['Archivo'])): ?>
              <p class="mt-2 text-sm text-blue-700">
                <a href="../uploads/<?= $clase['Archivo'] ?>" target="_blank" class="underline">📎 Ver archivo</a>
              </p>
            <?php endif; ?>

            <?php if (!empty($clase['alumnos'])): ?>
              <ul class="mt-2 list-disc pl-5 text-sm text-gray-800">
                <?php foreach ($clase['alumnos'] as $al): ?>
                  <li><?= htmlspecialchars($al['Nombre']) ?></li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p class="text-sm italic text-gray-500 mt-2">No hay alumnos asignados.</p>
            <?php endif; ?>

            <div class="mt-4 flex gap-2">
              <a href="editar_clase.php?id=<?= $clase['ID_Clase'] ?>" class="text-sm bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">✏️ Editar</a>
              <a href="eliminar_clase.php?id=<?= $clase['ID_Clase'] ?>" onclick="return confirm('¿Seguro que deseas eliminar esta clase?');" class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">🗑️ Eliminar</a>
            </div>
          </details>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>
</body>
</html>
