<?php
require_once '../conexion.php'; // Asegúrate de que este archivo se conecta bien
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $rol = $_POST['rol'] ?? '';

    if (!$correo || !$password || !$nombre || !$rol) {
        die("Error: Faltan datos obligatorios.");
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if ($rol === 'profesor') {
        $sql = "INSERT INTO Profesor (Nombre_Profe, Correo_Profe, Contraseña_Profe) VALUES (?, ?, ?)";
    } elseif ($rol === 'alumno') {
        $sql = "INSERT INTO Alumno (Nombre, Correo, Contraseña) VALUES (?, ?, ?)";
    } else {
        die("Error: Rol no válido.");
    }

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("sss", $nombre, $correo, $password_hash);

    if ($stmt->execute()) {
        header("Location: ../vistas/login.php?registro=exito");
        exit();
    } else {
        echo "Error al registrar: " . $conn->error;
    }
} else {
    header("Location: ../vistas/registro.php");
    exit();
}
