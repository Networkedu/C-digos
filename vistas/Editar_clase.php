<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$id_clase = $_GET['id'] ?? null;
if (!$id_clase) {
    die("Clase no especificada.");
}

$stmt = $conn->prepare("SELECT * FROM clases2 WHERE ID_Clase = ? AND ID_Profe = ?");
$stmt->bind_param("ii", $id_clase, $_SESSION['ID_Profe']);
$stmt->execute();
$res = $stmt->get_result();
$clase = $res->fetch_assoc();

if (!$clase) {
    die("Clase no encontrada o no tienes permiso.");
}

$alumnos = [];
$result = $conn->query("SELECT ID_Alumno, Nombre FROM Alumno");
while ($row = $result->fetch_assoc()) {
    $alumnos[] = $row;
}

$asignados = [];
$stmt2 = $conn->prepare("SELECT ID_Alumno FROM clase_alumno WHERE ID_Clase = ?");
$stmt2->bind_param("i", $id_clase);
$stmt2->execute();
$res2 = $stmt2->get_result();
while ($row = $res2->fetch_assoc()) {
    $asignados[] = $row['ID_Alumno'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Clase</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-white to-gray-100 min-h-screen text-gray-800">

<!-- Encabezado -->
<header class="bg-white shadow py-4 px-6">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between md:items-center gap-4">
    <h1 class="text-xl font-semibold text-blue-700">NetWorkEdu</h1>
    <nav class="flex gap-3 text-sm">
      <a href="Panel_profesor.php" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">📚 Tareas</a>
      <a href="Clase_Profesor.php" class="px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition">🏫 Clases</a>
    </nav>
    <div class="flex items-center gap-4 text-sm">
      <a href="../controllers/logoutController.php" class="text-red-600 hover:underline">Cerrar sesión</a>
    </div>
  </div>
</header>

<!-- Contenido principal -->
<main class="max-w-5xl mx-auto py-10 px-6">
  <h2 class="text-3xl font-extrabold text-blue-700 border-b pb-2 mb-8">✏️ Editar Clase: <span class="text-gray-700"><?= htmlspecialchars($clase['Nombre_Clase']) ?></span></h2>

  <form action="../controllers/ActualizarClaseController.php" method="post" enctype="multipart/form-data" class="space-y-8">
    <input type="hidden" name="id_clase" value="<?= $clase['ID_Clase'] ?>">

    <!-- Nombre -->
    <div>
      <label class="block text-sm font-medium mb-1">Nombre de la clase</label>
      <input type="text" name="nombre_clase" value="<?= htmlspecialchars($clase['Nombre_Clase']) ?>"
             class="w-full border-b border-blue-500 focus:outline-none bg-transparent py-2 px-1 text-lg" required>
    </div>

    <!-- Archivo -->
    <div>
      <label class="block text-sm font-medium mb-1">Archivo (opcional)</label>
      <input type="file" name="archivo" class="block w-full text-sm text-gray-600">
      <?php if (!empty($clase['Archivo'])): ?>
        <p class="mt-2 text-sm text-blue-600">
          Archivo actual: <a href="../uploads/<?= $clase['Archivo'] ?>" target="_blank" class="underline hover:text-blue-800">📎 Ver archivo</a>
        </p>
      <?php endif; ?>
    </div>

    <!-- Alumnos -->
    <div>
      <label class="block text-sm font-medium mb-2">Alumnos asignados</label>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-64 overflow-y-auto">
        <?php foreach ($alumnos as $a): ?>
          <label class="flex items-center gap-2 text-sm border border-gray-300 p-2 rounded hover:bg-gray-50">
            <input type="checkbox" name="alumnos[]" value="<?= $a['ID_Alumno'] ?>"
              <?= in_array($a['ID_Alumno'], $asignados) ? 'checked' : '' ?> class="accent-blue-600">
            <?= htmlspecialchars($a['Nombre']) ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Botón -->
    <div>
      <button type="submit"
              class="inline-block bg-blue-600 text-white font-semibold py-3 px-6 rounded-full hover:bg-blue-700 transition">
        💾 Guardar Cambios
      </button>
    </div>
  </form>
</main>

</body>
</html>

