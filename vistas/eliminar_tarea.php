<?php
session_start();

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$id_tarea = $_GET['id'] ?? null;
if (!$id_tarea) {
    die("ID de tarea no especificado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar eliminación</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen text-gray-800">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-red-600 mb-4">¿Estás seguro?</h2>
        <p class="mb-6">Esta acción eliminará la tarea <strong>ID: <?= htmlspecialchars($id_tarea) ?></strong> y todos sus datos asociados (entregas y asignaciones). Esta acción no se puede deshacer.</p>
        <div class="flex justify-between">
            <a href="vistas/Panel_profesor.php" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">❌ Cancelar</a>
            <a href="../controllers/EliminarTareaController.php?id=<?= urlencode($id_tarea) ?>" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">🗑️ Eliminar definitivamente</a>
        </div>
    </div>
</body>
</html>
