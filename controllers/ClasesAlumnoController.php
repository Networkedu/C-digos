<?php
session_start();
require_once '../conexion.php';

if (!isset($_SESSION['ID_Alumno'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['ID_Alumno'];
$nombre_alumno = $_SESSION['Nombre_Alumno'];

// Obtener clases del alumno
$sql = "SELECT C.Nombre_Clase FROM Clase C
        JOIN Alumno_Clase AC ON C.ID_Clase = AC.ID_Clase
        WHERE AC.ID_Alumno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$clase_data = [];
while ($row = $result->fetch_assoc()) {
    $clase_data[] = $row;
}

// Mostrar vista
include '../vistas/Clase_Alumno.php';
