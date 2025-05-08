
<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../vistas/login.php");
    exit;
}

$nombre = $_POST['nombre_tarea'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fecha = $_POST['fecha_entrega'] ?? '';
$id_profe = $_SESSION['ID_Profe'];
$alumnos = $_POST['alumnos'] ?? [];
$nombre_archivo = null;

// Subida de archivo
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
    $nombre_archivo = uniqid('tarea_') . '_' . basename($_FILES['archivo']['name']);
    $ruta = __DIR__ . '/../uploads/' . $nombre_archivo;
    move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta);
}

// 1. Insertar la tarea
$stmt = $conn->prepare("INSERT INTO tareas (Nombre_Tarea, Descripcion, Fecha_Entrega, ID_Profe, Archivo) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssis", $nombre, $descripcion, $fecha, $id_profe, $nombre_archivo);
$stmt->execute();
$id_tarea = $stmt->insert_id;
$stmt->close();

// 2. Asignar tarea a alumnos seleccionados
if (!empty($alumnos)) {
    $asignar_stmt = $conn->prepare("INSERT INTO tareas_asignadas (ID_Tarea, ID_Alumno) VALUES (?, ?)");
    foreach ($alumnos as $id_alumno) {
        $asignar_stmt->bind_param("ii", $id_tarea, $id_alumno);
        $asignar_stmt->execute();
    }
    $asignar_stmt->close();
}

// 3. Redirigir después de crear y asignar tarea
header("Location: ../vistas/Panel_profesor.php?creada=ok");
exit;
