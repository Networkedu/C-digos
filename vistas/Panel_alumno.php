<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Alumno'])) {
    header("Location: ../vistas/login.php");
    exit;
}

$nombre_alumno = $_SESSION['Nombre_Alumno'];
$id_alumno = $_SESSION['ID_Alumno'];

// Obtener tareas asignadas al alumno y separarlas según entrega
$sql = "
SELECT t.ID_Tarea, t.Nombre_Tarea, t.Descripcion, t.Fecha_Entrega, t.Archivo, p.Nombre_Profe,
       (SELECT COUNT(*) FROM entregas e WHERE e.ID_Tarea = t.ID_Tarea AND e.ID_Alumno = ?) AS entregada
FROM tareas t
JOIN tareas_asignadas ta ON t.ID_Tarea = ta.ID_Tarea
JOIN Profesor p ON t.ID_Profe = p.ID_Profe
WHERE ta.ID_Alumno = ?
ORDER BY t.Fecha_Entrega ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_alumno, $id_alumno);
$stmt->execute();
$resultado = $stmt->get_result();

$tareas_pendientes = [];
$tareas_entregadas = [];

while ($row = $resultado->fetch_assoc()) {
    if ($row['entregada']) {
        $tareas_entregadas[] = $row;
    } else {
        $tareas_pendientes[] = $row;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Alumno</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<header class="bg-white shadow py-4 px-6">
  <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between md:items-center gap-4">
    <h1 class="text-xl font-bold text-blue-700">NetWorkEdu - Alumno</h1>
    <div class="flex flex-col md:flex-row md:items-center gap-4 text-sm">
      <span>👤 <?= htmlspecialchars($nombre_alumno) ?></span>
      <nav class="flex gap-2">
        <a href="Index_alumno.php" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">🏠 Inicio</a>
        <a href="Panel_alumno.php" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">📚 Mis Tareas</a>
        <a href="Clase_alumno.php" class="px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition">🏫 Mis Clases</a>
      </nav>
      <a href="../controllers/logoutController.php" class="text-red-600 hover:underline">Cerrar sesión</a>
    </div>
  </div>
</header>

<main class="max-w-6xl mx-auto py-10 px-6 space-y-10">
  <!-- Tareas pendientes -->
  <section class="bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-3xl font-bold text-blue-700 mb-4">📌 Tareas Pendientes</h2>

    <?php if (empty($tareas_pendientes)): ?>
      <p class="text-gray-500 italic">No tienes tareas pendientes.</p>
    <?php else: ?>
      <div class="space-y-6">
        <?php foreach ($tareas_pendientes as $t): ?>
        <div class="p-6 rounded-lg shadow border border-blue-200 bg-blue-50">
          <h3 class="text-xl font-semibold text-blue-800 mb-1">
            <?= htmlspecialchars($t['Nombre_Tarea']) ?>
          </h3>
          <p class="text-sm text-gray-700 mb-2">🗓 Entrega: <strong><?= htmlspecialchars($t['Fecha_Entrega']) ?></strong></p>
          <p class="text-sm text-gray-600 mb-2">📝 <?= htmlspecialchars($t['Descripcion']) ?></p>
          <p class="text-sm text-gray-500 mb-2">👨‍🏫 Profesor: <?= htmlspecialchars($t['Nombre_Profe']) ?></p>

          <?php if ($t['Archivo']): ?>
            <a href="../uploads/<?= htmlspecialchars($t['Archivo']) ?>" target="_blank" class="inline-block text-blue-600 underline text-sm mb-2">📄 Ver archivo adjunto</a>
          <?php endif; ?>

          <form action="../controllers/EntregarTareaController.php" method="post" enctype="multipart/form-data" class="mt-4 space-y-2">
            <input type="hidden" name="id_tarea" value="<?= $t['ID_Tarea'] ?>">
            <label class="block text-sm font-medium text-gray-700">Sube tu archivo:</label>
            <input type="file" name="archivo" required class="w-full border border-gray-300 p-2 rounded">
            <button type="submit" class="mt-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
              📤 Entregar tarea
            </button>
          </form>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <!-- Tareas entregadas -->
  <section class="bg-green-100 p-8 rounded-xl shadow-md">
    <h2 class="text-3xl font-bold text-green-700 mb-4">✅ Tareas Entregadas</h2>

    <?php if (empty($tareas_entregadas)): ?>
      <p class="text-gray-600 italic">Aún no has entregado ninguna tarea.</p>
    <?php else: ?>
      <div class="space-y-6">
        <?php foreach ($tareas_entregadas as $t): ?>
        <div class="p-6 rounded-lg shadow border border-green-300 bg-white">
          <h3 class="text-xl font-semibold text-green-800 mb-1">
            <?= htmlspecialchars($t['Nombre_Tarea']) ?>
          </h3>
          <p class="text-sm text-gray-700 mb-2">🗓 Entrega: <strong><?= htmlspecialchars($t['Fecha_Entrega']) ?></strong></p>
          <p class="text-sm text-gray-600 mb-2">📝 <?= htmlspecialchars($t['Descripcion']) ?></p>
          <p class="text-sm text-gray-500">👨‍🏫 Profesor: <?= htmlspecialchars($t['Nombre_Profe']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>

</body>
</html>
