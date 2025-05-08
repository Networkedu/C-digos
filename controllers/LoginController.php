<?php
session_start();

require_once '../config.php';
include '../conexion.php';

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';
$rol = $_POST['rol'] ?? '';

if (!$correo || !$password || !$rol) {
    die("⚠️ Error: Faltan datos obligatorios.");
}

if ($rol === 'profesor') {
    $sql = "SELECT ID_Profe, Nombre_Profe, Contraseña_Profe FROM Profesor WHERE Correo_Profe = ?";
} elseif ($rol === 'alumno') {
    $sql = "SELECT ID_Alumno, Nombre, Contraseña FROM Alumno WHERE Correo = ?";
} else {
    die("❌ Rol no válido.");
}

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ Error al preparar la consulta: " . $conn->error);
}
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    die("❌ Usuario no encontrado.");
}

$hash = ($rol === 'profesor') ? $usuario['Contraseña_Profe'] : $usuario['Contraseña'];

if (password_verify($password, $hash)) {
    if ($rol === 'profesor') {
        $_SESSION['ID_Profe'] = $usuario['ID_Profe'];
        $_SESSION['Nombre_Profe'] = $usuario['Nombre_Profe'];
        $_SESSION['rol'] = 'profesor';
        header("Location: ../vistas/Index_profesor.php");
    } else {
        $_SESSION['ID_Alumno'] = $usuario['ID_Alumno'];
        $_SESSION['Nombre_Alumno'] = $usuario['Nombre'];
        $_SESSION['rol'] = 'alumno';
        header("Location: ../vistas/Index_alumno.php");
    }
    exit;
} else {
    die("❌ Contraseña incorrecta.");
}
