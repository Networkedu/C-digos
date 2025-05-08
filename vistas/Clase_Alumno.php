<?php
session_start();
require_once '../conexion.php';

if (!isset($_SESSION['ID_Alumno'])) {
    header("Location: login.php");
    exit;
}

$id_alumno = $_SESSION['ID_Alumno'];
$nombre_alumno = $_SESSION['Nombre_Alumno'];

// Consulta clases con profesor
$sql = "SELECT C.ID_Clase, C.Nombre_Clase, C.Icono, C.Color, P.Nombre_Profe AS Nombre_Profesor
        FROM clases2 C
        INNER JOIN clase_alumno CA ON C.ID_Clase = CA.ID_Clase
        INNER JOIN profesor P ON C.ID_Profe = P.ID_Profe
        WHERE CA.ID_Alumno = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ Error al preparar consulta de clases: " . $conn->error);
}
$stmt->bind_param("i", $id_alumno);
$stmt->execute();
$clases = $stmt->get_result();

$clase_data = [];

while ($c = $clases->fetch_assoc()) {
    $id_clase = $c['ID_Clase'];

    // Obtener compañeros
    $compañeros = [];
    $a_stmt = $conn->prepare("SELECT A.Nombre FROM clase_alumno CA INNER JOIN alumno A ON CA.ID_Alumno = A.ID_Alumno WHERE CA.ID_Clase = ?");
    if (!$a_stmt) {
        die("❌ Error al preparar compañeros: " . $conn->error);
    }
    $a_stmt->bind_param("i", $id_clase);
    $a_stmt->execute();
    $a_res = $a_stmt->get_result();
    while ($a = $a_res->fetch_assoc()) {
        $compañeros[] = $a;
    }

    $clase_data[] = array_merge($c, ['alumnos' => $compañeros]);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Clases</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen">

<!-- Header -->
<header class="bg-white shadow py-4 px-6">
  <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between md:items-center gap-4">
    <h1 class="text-xl font-bold text-blue-700">NetWorkEdu - Alumno</h1>
    <div class="flex flex-col md:flex-row md:items-center gap-4 text-sm">
      <span>👨‍🎓 <?= htmlspecialchars($nombre_alumno) ?></span>
      <nav class="flex gap-2">
        <a href="Clase_Alumno.php" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">📘 Mis Clases</a>
        <a href="Index_alumno.php" class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">🏠 Inicio</a>
        <a href="Panel_alumno.php" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">📚 Mis Tareas</a>
      </nav>
      <a href="../controllers/logoutController.php" class="text-red-600 hover:underline">Cerrar sesión</a>
    </div>
  </div>
</header>

<main class="max-w-6xl mx-auto py-10 px-6 space-y-10">
  <h2 class="text-3xl font-extrabold text-blue-700 mb-6">📚 Clases asignadas</h2>

  <?php if (empty($clase_data)): ?>
    <p class="text-gray-600 italic">Aún no estás asignado a ninguna clase.</p>
  <?php endif; ?>

  <?php foreach ($clase_data as $clase): ?>
    <section class="p-6 rounded-xl shadow <?= $clase['Color'] ?> space-y-4">
      <h3 class="text-2xl font-bold"><?= $clase['Icono'] ?> <?= htmlspecialchars($clase['Nombre_Clase']) ?></h3>
      <p class="text-sm text-gray-700">👨‍🏫 Profesor: <?= htmlspecialchars($clase['Nombre_Profesor']) ?></p>

      <div>
        <h4 class="text-lg font-semibold mb-2">👥 Compañeros:</h4>
        <ul class="list-disc pl-5 text-sm text-gray-800">
          <?php foreach ($clase['alumnos'] as $a): ?>
            <li><?= htmlspecialchars($a['Nombre']) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>
  <?php endforeach; ?>
</main>

</body>
</html>
