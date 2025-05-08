<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Clases</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
<div class="max-w-5xl mx-auto p-8">
    <h2 class="text-3xl font-bold text-blue-700 mb-6">Clases asignadas a <?= htmlspecialchars($nombre) ?></h2>

    <!-- Formulario -->
    <div class="bg-white p-6 rounded-xl shadow-md mb-10">
        <h3 class="text-xl font-semibold mb-4">Crear Nueva Clase</h3>
        <form action="" method="post" enctype="multipart/form-data" class="space-y-4">
            <input type="text" name="nombre_clase" placeholder="Nombre de la clase" required
                   class="w-full border border-gray-300 p-3 rounded">

            <label class="block">Archivo adicional (PDF, DOC, etc):</label>
            <input type="file" name="archivo" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                   class="w-full border border-gray-300 p-3 rounded">

            <label class="block">Asignar alumnos:</label>
            <div class="grid grid-cols-2 gap-2">
                <?php while ($alumno = $alumnos_result->fetch_assoc()): ?>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="alumnos[]" value="<?= $alumno['ID_Alumno'] ?>">
                        <span><?= htmlspecialchars($alumno['Nombre']) ?></span>
                    </label>
                <?php endwhile; ?>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700 transition">
                Crear Clase
            </button>
        </form>
    </div>

    <!-- Listado de clases -->
    <?php if ($clases->num_rows > 0): ?>
        <ul class="space-y-4">
            <?php while ($clase = $clases->fetch_assoc()): ?>
                <li class="bg-white p-4 rounded shadow-md">
                    <strong class="text-lg">📘 <?= htmlspecialchars($clase['Nombre_Clase']) ?></strong>
                    <span class="block text-sm text-gray-500">ID Clase: <?= $clase['ID_Clase'] ?></span>
                    <?php if ($clase['Archivo']): ?>
                        <span class="block text-sm text-blue-600">
                            <a href="../uploads/<?= $clase['Archivo'] ?>" target="_blank">Ver archivo</a>
                        </span>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-600">No tienes clases asignadas todavía.</p>
    <?php endif; ?>
</div>
</body>
</html>
