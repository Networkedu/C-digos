<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$nombre_profe = $_SESSION['Nombre_Profe'] ?? 'Profesor';
$id_profe = $_SESSION['ID_Profe'];

// Obtener tareas creadas
$tareas_creadas = [];
$sql = "SELECT ID_Tarea, Nombre_Tarea, Fecha_Entrega, Descripcion, Archivo FROM tareas WHERE ID_Profe = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_profe);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $id_tarea = $row['ID_Tarea'];

    $res1 = $conn->prepare("SELECT COUNT(*) AS total FROM tareas_asignadas WHERE ID_Tarea = ?");
    $res1->bind_param("i", $id_tarea);
    $res1->execute();
    $asignados = $res1->get_result()->fetch_assoc()['total'] ?? 0;

    $res2 = $conn->prepare("SELECT COUNT(*) AS entregados FROM entregas WHERE ID_Tarea = ?");
    $res2->bind_param("i", $id_tarea);
    $res2->execute();
    $entregados = $res2->get_result()->fetch_assoc()['entregados'] ?? 0;

    $estudiantes = [];
    $res3 = $conn->prepare("SELECT a.Nombre, a.ID_Alumno, IF(e.ID_Entrega IS NULL, 0, 1) AS entregado
        FROM tareas_asignadas ta
        JOIN Alumno a ON ta.ID_Alumno = a.ID_Alumno
        LEFT JOIN entregas e ON e.ID_Alumno = a.ID_Alumno AND e.ID_Tarea = ta.ID_Tarea
        WHERE ta.ID_Tarea = ?");
    $res3->bind_param("i", $id_tarea);
    $res3->execute();
    $res = $res3->get_result();
    while ($a = $res->fetch_assoc()) {
        $estudiantes[] = $a;
    }

    $row['total_asignados'] = $asignados;
    $row['total_entregados'] = $entregados;
    $row['completada'] = ($asignados > 0 && $entregados >= $asignados);
    $row['estudiantes'] = $estudiantes;

    $tareas_creadas[] = $row;
}

$alumnos_disponibles = [];
$result_alumnos = $conn->query("SELECT ID_Alumno, Nombre FROM Alumno");
while ($row = $result_alumnos->fetch_assoc()) {
    $alumnos_disponibles[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Profesor</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
<header class="bg-white shadow py-4 px-6">
  <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between md:items-center gap-4">
    <h1 class="text-xl font-bold text-blue-700">NetWorkEdu - Profesor</h1>
    <div class="flex flex-col md:flex-row md:items-center gap-4 text-sm">
      <span>👨‍🏫 <?= htmlspecialchars($nombre_profe) ?></span>
      <nav class="flex gap-2">
        <a href="Index_profesor.php" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">🏠 Inicio</a>
        <a href="Panel_profesor.php" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">📚 Mis Tareas</a>
        <a href="Clase_Profesor.php" class="px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition">🏫 Mis Clases</a>
      </nav>
      <a href="../controllers/logoutController.php" class="text-red-600 hover:underline">Cerrar sesión</a>
    </div>
  </div>
</header>

<main class="max-w-6xl mx-auto py-10 px-6 space-y-12">

  <!-- FORMULARIO CREAR TAREA -->
  <section class="space-y-6 border-t border-blue-200 pt-10">
    <h2 class="text-3xl font-extrabold text-blue-700">➕ Crear Nueva Tarea</h2>

    <form action="../controllers/CrearTareaController.php" method="post" enctype="multipart/form-data" class="space-y-6">

      <div>
        <label class="block text-sm font-medium mb-1">Nombre de la tarea</label>
        <input type="text" name="nombre_tarea" placeholder="Nombre de la tarea"
               class="w-full border-b border-blue-500 focus:outline-none bg-transparent py-2 px-1 text-lg" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Descripción</label>
        <textarea name="descripcion" rows="4" placeholder="Escribe los detalles de la tarea..."
                  class="w-full border border-gray-300 p-3 rounded bg-white"></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Fecha de entrega</label>
        <input type="date" name="fecha_entrega"
               class="w-full border border-gray-300 p-2 rounded bg-white" required>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Archivo adjunto (opcional)</label>
        <input type="file" name="archivo" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
               class="block w-full text-sm text-gray-700">
      </div>

      <div>
        <label class="block text-sm font-medium mb-2">Asignar a alumnos</label>
        <div class="grid grid-cols-2 md:grid-cols-2 gap-2 max-h-52 overflow-y-auto border p-2 rounded-lg bg-gray-50">
          <?php foreach ($alumnos_disponibles as $alumno): ?>
            <label class="flex items-center gap-2 text-sm bg-white p-2 rounded shadow-sm hover:bg-gray-100">
              <input type="checkbox" name="alumnos[]" value="<?= $alumno['ID_Alumno'] ?>" class="accent-blue-600">
              <?= htmlspecialchars($alumno['Nombre']) ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div>
        <button type="submit" class="inline-block bg-blue-600 text-white font-semibold py-3 px-6 rounded-full hover:bg-blue-700 transition">
          🚀 Crear Tarea
        </button>
      </div>
    </form>
  </section>

  <!-- TAREAS CREADAS -->
  <section>
    <h2 class="text-2xl font-bold text-blue-700 mb-6">📋 Tareas Creadas</h2>
    <?php foreach ($tareas_creadas as $t): ?>
    <div class="<?= $t['completada'] ? 'bg-green-100' : 'bg-white' ?> p-6 rounded-xl shadow mb-6">
      <div class="flex justify-between items-start mb-3">
        <div>
          <h3 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($t['Nombre_Tarea']) ?></h3>
          <p class="text-sm text-gray-700">Entrega: <?= htmlspecialchars($t['Fecha_Entrega']) ?> | <?= $t['total_entregados'] ?> / <?= $t['total_asignados'] ?> entregadas</p>
        </div>
        <div class="flex gap-2">
          <a href="Editar_tarea.php?id=<?= $t['ID_Tarea'] ?>" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">✏️ Editar</a>
          <a href="eliminar_tarea.php?id=<?= $t['ID_Tarea'] ?>" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">🗑️ Eliminar</a>
        </div>
      </div>

      <p class="text-sm text-gray-600 mb-4">Descripción: <?= htmlspecialchars($t['Descripcion']) ?></p>

      <?php if ($t['Archivo']): ?>
        <p class="mb-2 text-sm">📎 <a href="../uploads/<?= htmlspecialchars($t['Archivo']) ?>" target="_blank" class="text-blue-600 underline">Ver archivo</a></p>
      <?php endif; ?>

      <h4 class="text-lg font-medium text-gray-800 mt-4 mb-2">📌 Estado por alumno:</h4>
      <ul class="list-disc ml-6 text-sm">
        <?php foreach ($t['estudiantes'] as $e): ?>
          <li class="<?= $e['entregado'] ? 'text-green-700' : 'text-red-600' ?>">
            <?= htmlspecialchars($e['Nombre']) ?> - <?= $e['entregado'] ? 'Entregado ✅' : 'Pendiente ⏳' ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endforeach; ?>
  </section>
</main>
</body>
</html>
