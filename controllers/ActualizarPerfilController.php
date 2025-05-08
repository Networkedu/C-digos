<?php
session_start();
require_once '../models/PerfilModel.php';

$id = $_SESSION['ID_Alumno'] ?? $_SESSION['ID_Profe'];
$tipo = isset($_SESSION['ID_Alumno']) ? 'alumno' : 'profe';
$nombre = trim($_POST['nombre'] ?? '');
$foto = $_FILES['foto'] ?? null;

if (!$id || !$nombre) {
    die('Error: faltan datos obligatorios.');
}

$ruta_foto = $_SESSION['FotoPerfil'] ?? null;

// Procesar imagen si se sube
if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
    $nombre_archivo = uniqid('perfil_') . '.' . $ext;
    $destino = '../uploads/perfiles/' . $nombre_archivo;

    if (!is_dir('../uploads/perfiles')) {
        mkdir('../uploads/perfiles', 0777, true);
    }

    if (move_uploaded_file($foto['tmp_name'], $destino)) {
        $ruta_foto = $destino;
    }
}

$model = new PerfilModel();
$model->actualizarPerfil($tipo, $nombre, $ruta_foto, $id);

// Actualizar sesión
$_SESSION['Nombre'] = $nombre;
$_SESSION['FotoPerfil'] = $ruta_foto;

// Redirigir al perfil
header("Location: ../controllers/PerfilViewController.php"); // o ajusta la ruta
exit();
?>
