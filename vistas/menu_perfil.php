<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detectar rol
$rol = $_SESSION['rol'] ?? null;
$nombre = $rol === 'profesor'
    ? ($_SESSION['Nombre_Profe'] ?? 'Profesor')
    : ($_SESSION['Nombre_Alumno'] ?? 'Alumno');
?>

<nav class="bg-white p-4 shadow mb-4 rounded-lg">
    <div class="flex justify-between items-center">
        <div class="text-xl font-bold text-blue-600">NetWorkEdu</div>
        <div class="text-sm text-gray-600">
            👋 Hola, <?= htmlspecialchars($nombre) ?>
        </div>
    </div>
    <ul class="mt-4 flex flex-wrap gap-4 justify-end text-blue-600 font-medium">
        <li><a href="<?= $rol === 'profesor' ? 'indexProfesor.php' : 'indexAlumno.php' ?>" class="hover:underline">🏠 Inicio</a></li>
        <?php if ($rol === 'profesor'): ?>
            <li><a href="profesor.php" class="hover:underline">📚 Tareas</a></li>
            <li><a href="chat.php" class="hover:underline">💬 Chats</a></li>
            <li><a href="clases.php" class="hover:underline">📖 Clases</a></li>
        <?php elseif ($rol === 'alumno'): ?>
            <li><a href="tareas.php" class="hover:underline">📚 Mis Tareas</a></li>
            <li><a href="clases.php" class="hover:underline">📖 Clases</a></li>
        <?php endif; ?>
        <li><a href="../logout.php" class="text-red-500 hover:underline">🚪 Cerrar sesión</a></li>
    </ul>
</nav>
