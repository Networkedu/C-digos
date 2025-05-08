<?php
session_start();
require_once '../models/BorradorTareaModel.php';
require_once '../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$model = new BorradorTareaModel();
$borradores = $model->obtenerPorProfesor($_SESSION['ID_Profe']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Borradores de Tareas</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 p-8">
  <h1 class="text-3xl font-bold text-blue-700 mb-6">📚 Tus Borradores de Tareas</h1>

  <?php if (isset($_GET['mensaje'])): ?>
    <p class="mb-4 text-green-700 bg-green-100 px-4 py-2 rounded">
      <?= $_GET['mensaje'] === 'guardado' ? '✔️ Borrador guardado correctamente.' : '🗑️ Borrador eliminado.' ?>
    </p>
  <?php endif; ?>

  <!-- Listado de borradores -->
  <ul class="space-y-4 mb-10">
    <?php while ($b = $borradores->fetch_assoc()): ?>
      <li class="bg-white p-4 rounded shadow flex justify-between items-center">
        <div>
          <strong><?= htmlspecialchars($b['Nombre']) ?></strong> – <?= htmlspecialchars($b['Asunto']) ?><br>
          <small>📅 <?= $b['Fecha_Entrega'] ?> | 👥 <?= htmlspecialchars($b['Alumnos']) ?></small>
        </div>
        <a href="../controllers/BorradorTareaController.php?eliminar=<?= $b['ID_Borrador'] ?>" class="text-red-600 hover:underline">Eliminar</a>
      </li>
    <?php endwhile; ?>
  </ul>

  <!-- Formulario de creación -->
  <form action="../controllers/BorradorTareaController.php" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <input type="hidden" name="accion" value="guardar">
    <input name="nombre" placeholder="Nombre de la tarea" class="p-4 border rounded" required>
    <input type="date" name="fecha" class="p-4 border rounded" required>
    <input name="alumnos" placeholder="Alumno/s (ej: Juan, María)" class="p-4 border rounded" required>
    <input name="asunto" placeholder="Asunto de la tarea" class="p-4 border rounded" required>
    <button class="col-span-2 bg-blue-600 text-white py-3 rounded hover:bg-blue-700">Guardar Borrador</button>
  </form>
</body>
</html>
