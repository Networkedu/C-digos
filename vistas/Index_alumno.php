<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Alumno'])) {
    header('Location: ../vistas/login.php');
    exit;
}

$nombre = $_SESSION['Nombre_Alumno'];
$id_alumno = $_SESSION['ID_Alumno'];

$resumen = ['total' => 0, 'entregadas' => 0, 'pendientes' => 0];
$proxima = null;

// Tareas asignadas
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM tareas_asignadas WHERE ID_Alumno = ?");
$stmt->bind_param("i", $id_alumno);
$stmt->execute();
$resumen['total'] = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

// Tareas entregadas
$stmt2 = $conn->prepare("
  SELECT COUNT(*) AS entregadas
  FROM tareas_asignadas a
  INNER JOIN entregas e ON a.ID_Tarea = e.ID_Tarea AND a.ID_Alumno = e.ID_Alumno
  WHERE a.ID_Alumno = ?
");
$stmt2->bind_param("i", $id_alumno);
$stmt2->execute();
$resumen['entregadas'] = $stmt2->get_result()->fetch_assoc()['entregadas'] ?? 0;

// Pendientes
$resumen['pendientes'] = $resumen['total'] - $resumen['entregadas'];

// Próxima entrega
$stmt3 = $conn->prepare("
  SELECT t.Nombre_Tarea, t.Fecha_Entrega
  FROM tareas t
  INNER JOIN tareas_asignadas a ON t.ID_Tarea = a.ID_Tarea
  WHERE a.ID_Alumno = ? AND t.Fecha_Entrega >= CURDATE()
  ORDER BY t.Fecha_Entrega ASC
  LIMIT 1
");
$stmt3->bind_param("i", $id_alumno);
$stmt3->execute();
$proxima = $stmt3->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel del Alumno | NetWorkEdu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

  <!-- Encabezado -->
  <header class="bg-white shadow py-4 px-6">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <h1 class="text-xl font-semibold text-blue-700">NetWorkEdu</h1>
      <div class="flex items-center gap-4 text-sm">
        <span>🎓 <?= htmlspecialchars($nombre) ?></span>
        <a href="../controllers/logoutController.php" class="text-red-600 hover:underline">Cerrar sesión</a>
      </div>
    </div>
  </header>

  <!-- Contenido principal -->
  <main class="max-w-6xl mx-auto px-6 py-10 space-y-12">

    <!-- Introducción -->
    <section class="text-center">
      <h2 class="text-3xl font-bold text-gray-800">Panel de control</h2>
      <p class="text-gray-500 mt-2">Aquí puedes ver tu progreso y acceder a tus herramientas de estudio.</p>
    </section>

    <!-- Resumen -->
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
      <div class="bg-white rounded-xl p-6 shadow hover:shadow-md transition">
        <h3 class="text-sm text-gray-500 mb-1">Tareas asignadas</h3>
        <div class="text-4xl font-bold text-blue-600"><?= $resumen['total'] ?></div>
      </div>
      <div class="bg-white rounded-xl p-6 shadow hover:shadow-md transition">
        <h3 class="text-sm text-gray-500 mb-1">Tareas entregadas</h3>
        <div class="text-4xl font-bold text-green-600"><?= $resumen['entregadas'] ?></div>
      </div>
      <div class="bg-white rounded-xl p-6 shadow hover:shadow-md transition">
        <h3 class="text-sm text-gray-500 mb-1">Tareas pendientes</h3>
        <div class="text-4xl font-bold text-red-600"><?= $resumen['pendientes'] ?></div>
      </div>
    </section>

   

    <!-- Accesos directos -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
      <a href="Panel_alumno.php" class="block bg-blue-600 text-white p-6 rounded-xl text-center shadow hover:bg-blue-700 transition">
        <h3 class="text-xl font-semibold mb-2">📚 Mis Tareas</h3>
        <p>Consulta y entrega tus tareas asignadas.</p>
      </a>
      <a href="Clase_alumno.php" class="block bg-purple-600 text-white p-6 rounded-xl text-center shadow hover:bg-purple-700 transition">
        <h3 class="text-xl font-semibold mb-2">🏫 Mis Clases</h3>
        <p>Revisa clases disponibles y sus contenidos.</p>
      </a>
      
    </section>

  </main>

  <!-- Pie de página -->
  <footer class="text-center text-xs text-gray-400 py-8">
    &copy; <?= date("Y") ?> NetWorkEdu. Todos los derechos reservados.
  </footer>

</body>
</html>

