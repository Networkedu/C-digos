<?php
session_start();
require_once '../models/PerfilModel.php';

if (!isset($_SESSION['ID_Alumno']) && !isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_SESSION['ID_Alumno'] ?? $_SESSION['ID_Profe'];
$tipo = isset($_SESSION['ID_Alumno']) ? 'alumno' : 'profe';

$model = new PerfilModel();

$datos = $model->obtenerDatos($id, $tipo);
$clases = $model->obtenerClases($id, $tipo);
$tareas = $model->contarTareas($id, $tipo);
$foto = $_SESSION['FotoPerfil'] ?? null;

include '../views/perfil.php';
