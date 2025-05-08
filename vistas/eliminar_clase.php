<?php
session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$id_clase = $_GET['id'] ?? null;
$id_profe = $_SESSION['ID_Profe'];

if (!$id_clase) {
    die("❌ ID de clase no proporcionado.");
}

// Verificar que la clase pertenezca al profesor
$stmt = $conn->prepare("SELECT * FROM clases2 WHERE ID_Clase = ? AND ID_Profe = ?");
if (!$stmt) {
    die("❌ Error al preparar verificación de clase: " . $conn->error);
}

$stmt->bind_param("ii", $id_clase, $id_profe);
$stmt->execute();
$res = $stmt->get_result();
$clase = $res->fetch_assoc();

if (!$clase) {
    die("❌ Clase no encontrada o no tienes permiso para eliminarla.");
}

// Eliminar archivos físicos si existen (de tabla clase_archivo)
$stmt_archivos = $conn->prepare("SELECT Nombre_Archivo FROM clase_archivo WHERE ID_Clase = ?");
if ($stmt_archivos) {
    $stmt_archivos->bind_param("i", $id_clase);
    $stmt_archivos->execute();
    $res_archivos = $stmt_archivos->get_result();

    while ($archivo = $res_archivos->fetch_assoc()) {
        $ruta = __DIR__ . '/../uploads/' . $archivo['Nombre_Archivo'];
        if (file_exists($ruta)) {
            unlink($ruta);  // Elimina el archivo del disco
        }
    }

    // Eliminar registros de la tabla clase_archivo
    $del_arch = $conn->prepare("DELETE FROM clase_archivo WHERE ID_Clase = ?");
    if ($del_arch) {
        $del_arch->bind_param("i", $id_clase);
        $del_arch->execute();
    }
}

// Eliminar relaciones con alumnos
$stmtAlum = $conn->prepare("DELETE FROM clase_alumno WHERE ID_Clase = ?");
if ($stmtAlum) {
    $stmtAlum->bind_param("i", $id_clase);
    $stmtAlum->execute();
}

// Eliminar la clase principal
$stmtDel = $conn->prepare("DELETE FROM clases2 WHERE ID_Clase = ?");
if ($stmtDel) {
    $stmtDel->bind_param("i", $id_clase);
    $stmtDel->execute();
}

header("Location: Clase_Profesor.php");
exit;
