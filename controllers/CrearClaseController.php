<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$nombre_clase = $_POST['nombre_clase'] ?? '';
$icono = $_POST['icono'] ?? '📘';
$color = $_POST['color'] ?? 'bg-blue-100';
$id_profe = $_SESSION['ID_Profe'];
$alumnos = $_POST['alumnos'] ?? [];

$nombre_archivo = null;

if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
    $permitidos = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'];
    $extension = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $permitidos)) {
        die("❌ Tipo de archivo no permitido. Solo se permiten: pdf, doc, docx, png, jpg, jpeg.");
    }

    $nombre_archivo = uniqid('clase_') . '_' . basename($_FILES['archivo']['name']);
    $ruta_destino = __DIR__ . '/../uploads/' . $nombre_archivo;

    if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino)) {
        die("❌ Error al subir el archivo.");
    }
}

// 🧱 INSERTAR EN clases2
$sql_insert = "INSERT INTO clases2 (Nombre_Clase, Icono, Color, Archivo, ID_Profe) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert);

if (!$stmt) {
    die("❌ Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("ssssi", $nombre_clase, $icono, $color, $nombre_archivo, $id_profe);
$stmt->execute();
$id_clase = $stmt->insert_id;

// ✅ INSERTAR alumnos asignados en clase_alumno
if (!empty($alumnos)) {
    $insert = $conn->prepare("INSERT INTO clase_alumno (ID_Clase, ID_Alumno) VALUES (?, ?)");
    if (!$insert) {
        die("❌ Error en prepare de clase_alumno: " . $conn->error);
    }

    foreach ($alumnos as $id_alumno) {
        $insert->bind_param("ii", $id_clase, $id_alumno);
        $insert->execute();
    }
}

// 🔄 Redirigir con ID para mostrar clase destacada
header("Location: ../vistas/Clase_Profesor.php?nueva_clase=$id_clase");
exit;
