<?php
session_start();
require_once '../models/ContraseñaModel.php';

if (!isset($_SESSION['ID_Alumno']) && !isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_SESSION['ID_Alumno'] ?? $_SESSION['ID_Profe'];
$tipo = isset($_SESSION['ID_Alumno']) ? 'alumno' : 'profe';

$actual = $_POST['actual'] ?? '';
$nueva = $_POST['nueva'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';

if (!$actual || !$nueva || !$confirmar) {
    header("Location: ../views/contraseña.php?mensaje=Completa todos los campos.");
    exit();
}

if ($nueva !== $confirmar) {
    header("Location: ../views/contraseña.php?mensaje=Las contraseñas no coinciden.");
    exit();
}

$model = new ContraseñaModel();
$hash_actual = $model->obtenerHashActual($id, $tipo);

if (!password_verify($actual, $hash_actual)) {
    header("Location: ../views/contraseña.php?mensaje=La contraseña actual no es válida.");
    exit();
}

$nuevo_hash = password_hash($nueva, PASSWORD_DEFAULT);
$model->actualizar($id, $tipo, $nuevo_hash);

header("Location: ../views/contraseña.php?mensaje=Contraseña actualizada con éxito.");
exit();
