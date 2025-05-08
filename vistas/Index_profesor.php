<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header('Location: ../vistas/login.php');
    exit;
}

$nombre = $_SESSION['Nombre_Profe'];
$id_profe = $_SESSION['ID_Profe'];

$resumen = ['total' => 0, 'completadas' => 0, 'clases' => 0];

// Total tareas creadas
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM tareas WHERE ID_Profe = ?");
$stmt->bind_param("i", $id_profe);
$stmt->execute();
$resumen['total'] = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

// Tareas completadas
$sql = "
  SELECT COUNT(*) AS completadas
  FROM tareas t
  WHERE t.ID_Profe = ?
    AND EXISTS (SELECT 1 FROM tareas_asignadas a WHERE a.ID_Tarea = t.ID_Tarea)
    AND NOT EXISTS (
      SELECT 1 FROM tareas_asignadas a
      WHERE a.ID_Tarea = t.ID_Tarea
      AND NOT EXISTS (
        SELECT 1 FROM entregas e
        WHERE e.ID_Tarea = a.ID_Tarea AND e.ID_Alumno = a.ID_Alumno
      )
    )
";
$stmt2 = $conn->prepare($sql);
$stmt2->bind_param("i", $id_profe);
$stmt2->execute();
$resumen['completadas'] = $stmt2->get_result()->fetch_assoc()['completadas'] ?? 0;

// Clases creadas
$stmt3 = $conn->prepare("SELECT COUNT(*) AS clases FROM clases WHERE ID_Profe = ?");
$stmt3->bind_param("i", $id_profe);
$stmt3->execute();
$resumen['clases'] = $stmt3->get_result()->fetch_assoc()['clases'] ?? 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel del Profesor | NetWorkEdu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

  <!-- Encabezado -->
  <header class="bg-white shadow py-4 px-6">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <h1 class="text-xl font-semibold text-blue-700">NetWorkEdu</h1>
      <div class="flex items-center gap-4 text-sm">
        <span>👨‍🏫 <?= htmlspecialchars($nombre) ?></span>
        <a href="../controllers/logoutController.php" class="text-red-600 hover:underline">Cerrar sesión</a>
      </div>
    </div>
  </header>

  <!-- Contenido principal -->
  <main class="max-w-6xl mx-auto px-6 py-10 space-y-12">

    <!-- Introducción -->
    <section class="text-center">
      <h2 class="text-3xl font-bold text-gray-800">Panel de control</h2>
      <p class="text-gray-500 mt-2">Aquí puedes visualizar el resumen de tus actividades como profesor.</p>
    </section>

    <!-- Resumen -->
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
      <div class="bg-white rounded-xl p-6 shadow hover:shadow-md transition">
        <h3 class="text-sm text-gray-500 mb-1">Tareas creadas</h3>
        <div class="text-4xl font-bold text-blue-600"><?= $resumen['total'] ?></div>
      </div>
      <div class="bg-white rounded-xl p-6 shadow hover:shadow-md transition">
        <h3 class="text-sm text-gray-500 mb-1">Tareas completadas</h3>
        <div class="text-4xl font-bold text-green-600"><?= $resumen['completadas'] ?></div>
      </div>
      <div class="bg-white rounded-xl p-6 shadow hover:shadow-md transition">
        <h3 class="text-sm text-gray-500 mb-1">Clases creadas</h3>
        <div class="text-4xl font-bold text-yellow-600"><?= $resumen['clases'] ?></div>
      </div>
    </section>

    <!-- Accesos directos -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
      <a href="Panel_profesor.php" class="block bg-blue-600 text-white p-6 rounded-xl text-center shadow hover:bg-blue-700 transition">
        <h3 class="text-xl font-semibold mb-2">📚 Gestionar Tareas</h3>
        <p>Crea, edita y revisa tareas asignadas a tus alumnos.</p>
      </a>
      <a href="Clase_Profesor.php" class="block bg-purple-600 text-white p-6 rounded-xl text-center shadow hover:bg-purple-700 transition">
        <h3 class="text-xl font-semibold mb-2">📖 Ver Clases</h3>
        <p>Administra tus clases, materiales y alumnos asignados.</p>
      </a>
    </section>

  </main>

  <footer class="text-center text-xs text-gray-400 py-8">
    &copy; <?= date("Y") ?> NetWorkEdu. Todos los derechos reservados.
  </footer>

</body>
</html>
