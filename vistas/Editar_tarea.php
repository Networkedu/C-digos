<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$id_tarea = $_GET['id'] ?? null;
$id_profe = $_SESSION['ID_Profe'];

if (!$id_tarea) {
    die("❌ ID de tarea no proporcionado.");
}

$stmt = $conn->prepare("SELECT * FROM tareas WHERE ID_Tarea = ? AND ID_Profe = ?");
$stmt->bind_param("ii", $id_tarea, $id_profe);
$stmt->execute();
$result = $stmt->get_result();
$tarea = $result->fetch_assoc();

if (!$tarea) {
    die("⚠️ No se encontró la tarea o no tienes permiso para editarla.");
}

$alumnos_disponibles = [];
$res = $conn->query("SELECT ID_Alumno, Nombre FROM Alumno");
while ($row = $res->fetch_assoc()) {
    $alumnos_disponibles[] = $row;
}

$alumnos_asignados = [];
$asignados = $conn->prepare("SELECT ID_Alumno FROM tareas_asignadas WHERE ID_Tarea = ?");
$asignados->bind_param("i", $id_tarea);
$asignados->execute();
$res_asignados = $asignados->get_result();
while ($row = $res_asignados->fetch_assoc()) {
    $alumnos_asignados[] = $row['ID_Alumno'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Tarea</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-white to-gray-100 min-h-screen text-gray-800">

<!-- Header -->
<header class="bg-white shadow py-4 px-6">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between md:items-center gap-4">
    <h1 class="text-xl font-semibold text-blue-700">NetWorkEdu</h1>
    <nav class="flex gap-3 text-sm">
        <a href="Index_profesor.php" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">🏠 Inicio</a>
      <a href="Panel_profesor.php" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">📚 Tareas</a>
      <a href="Clase_Profesor.php" class="px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition">🏫 Clases</a>
    </nav>
    <div class="flex items-center gap-4 text-sm">
      <a href="../controllers/logoutController.php" class="text-red-600 hover:underline">Cerrar sesión</a>
    </div>
  </div>
</header>

<!-- Contenido -->
<main class="max-w-5xl mx-auto py-10 px-6 space-y-10">
  <h2 class="text-3xl font-extrabold text-blue-700 border-b pb-2">✏️ Editar Tarea: <span class="text-gray-700"><?= htmlspecialchars($tarea['Nombre_Tarea']) ?></span></h2>

  <form action="../controllers/ActualizarTareaController.php" method="post" enctype="multipart/form-data" class="space-y-8">
    <input type="hidden" name="id_tarea" value="<?= $tarea['ID_Tarea'] ?>">

    <div>
      <label class="block text-sm font-medium mb-1">Nombre de la tarea</label>
      <input type="text" name="nombre_tarea" value="<?= htmlspecialchars($tarea['Nombre_Tarea']) ?>"
             class="w-full border-b border-blue-500 focus:outline-none bg-transparent py-2 px-1 text-lg" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Descripción</label>
      <textarea name="descripcion" rows="4"
                class="w-full border border-gray-300 p-3 rounded bg-white"><?= htmlspecialchars($tarea['Descripcion']) ?></textarea>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Fecha de entrega</label>
      <input type="date" name="fecha_entrega" value="<?= $tarea['Fecha_Entrega'] ?>"
             class="w-full border border-gray-300 p-2 rounded bg-white" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Archivo actual</label>
      <?php if ($tarea['Archivo']): ?>
        <p class="text-blue-600 text-sm mb-2">
          📎 <a href="../uploads/<?= htmlspecialchars($tarea['Archivo']) ?>" target="_blank" class="underline hover:text-blue-800">Ver archivo actual</a>
        </p>
      <?php else: ?>
        <p class="text-gray-500 italic text-sm">Sin archivo adjunto</p>
      <?php endif; ?>

      <label class="block text-sm font-medium mt-2">Subir nuevo archivo (opcional)</label>
      <input type="file" name="archivo" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
             class="block w-full text-sm text-gray-700 mt-1">
    </div>

    <div>
      <label class="block text-sm font-medium mb-2">Asignar a alumnos</label>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-52 overflow-y-auto border p-3 rounded bg-gray-50">
        <?php foreach ($alumnos_disponibles as $alumno): ?>
          <label class="flex items-center gap-2 text-sm bg-white p-2 rounded shadow-sm hover:bg-gray-100">
            <input type="checkbox" name="alumnos[]" value="<?= $alumno['ID_Alumno'] ?>"
                   <?= in_array($alumno['ID_Alumno'], $alumnos_asignados) ? 'checked' : '' ?> class="accent-blue-600">
            <?= htmlspecialchars($alumno['Nombre']) ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="flex justify-between items-center pt-6">
      <a href="Panel_profesor.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition">⬅️ Cancelar</a>
      <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition">💾 Guardar Cambios</button>
    </div>
  </form>
</main>

</body>
</html>
