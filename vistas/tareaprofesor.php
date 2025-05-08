<?php
require_once '../controllers/TareaProfesorController.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tareas del Profesor</title>
  <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-gray-100 text-gray-800 p-8">
  <h2 class="text-3xl font-bold text-blue-700 mb-6">📋 Lista de Tareas</h2>

  <?php if ($tareas->num_rows > 0): ?>
    <ul class="space-y-4">
      <?php while ($fila = $tareas->fetch_assoc()): ?>
        <li class="bg-white p-4 rounded shadow flex justify-between items-center">
          <div>
            <strong><?= htmlspecialchars($fila['Nombre_Tarea']) ?></strong> – Entrega: <?= $fila['Fecha_Entrega'] ?>
          </div>
          <div class="space-x-2">
            <a href="editar_tarea.php?id=<?= $fila['ID_Tarea'] ?>" class="text-yellow-600 hover:underline">✏️ Editar</a>
            <a href="eliminar_tarea.php?id=<?= $fila['ID_Tarea'] ?>" class="text-red-600 hover:underline">🗑️ Eliminar</a>
          </div>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>No hay tareas asignadas todavía.</p>
  <?php endif; ?>
</body>
</html>
