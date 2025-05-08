<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$id_profe = $_SESSION['ID_Profe'];
$nombre = $_POST['nombre_tarea'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$fecha = $_POST['fecha_entrega'] ?? '';
$alumnos = $_POST['alumnos'] ?? [];

if (!$nombre || !$fecha) {
    die("❌ Datos obligatorios faltantes.");
}

// Subida de archivo (opcional)
$archivo = null;
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $archivo = basename($_FILES['archivo']['name']);
    $ruta = __DIR__ . '/../uploads/' . $archivo;
    move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta);
}

// Crear tarea en la base de datos
$stmt = $conn->prepare("INSERT INTO tareas (Nombre_Tarea, Descripcion, Fecha_Entrega, Archivo, ID_Profe) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $nombre, $descripcion, $fecha, $archivo, $id_profe);
$stmt->execute();
$id_tarea = $conn->insert_id;

// Asignar la tarea a alumnos seleccionados
if (!empty($alumnos)) {
    $stmt2 = $conn->prepare("INSERT INTO tareas_asignadas (ID_Tarea, ID_Alumno) VALUES (?, ?)");
    foreach ($alumnos as $id_alumno) {
        $stmt2->bind_param("ii", $id_tarea, $id_alumno);
        $stmt2->execute();
    }
}

header("Location: ../vistas/Panel_profesor.php");
exit;
?>
