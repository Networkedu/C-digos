<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Alumno'])) {
    die("Error: No autenticado.");
}

$id_alumno = $_SESSION['ID_Alumno'];
$id_tarea = $_POST['id_tarea'] ?? null;

if (!$id_tarea || !isset($_FILES['archivo'])) {
    die("Faltan datos o archivo.");
}

$archivo = null;
if ($_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $archivo = basename($_FILES['archivo']['name']);
    $ruta_destino = __DIR__ . '/../uploads/' . $archivo;
    move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino);
}

// Guardar entrega
$stmt = $conn->prepare("INSERT INTO entregas (ID_Tarea, ID_Alumno, Archivo) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $id_tarea, $id_alumno, $archivo);
$stmt->execute();

header("Location: ../vistas/Panel_alumno.php");
exit;
?>
