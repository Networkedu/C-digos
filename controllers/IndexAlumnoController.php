<?php
session_start();
require_once '../models/DashboardAlumnoModel.php';

if (!isset($_SESSION['ID_Alumno'])) {
    header("Location: ../login.php");
    exit;
}

$model = new DashboardAlumnoModel();
$id_alumno = $_SESSION['ID_Alumno'];

$resumen = $model->obtenerResumenTareas($id_alumno);
$proxima = $model->obtenerProximaEntrega($id_alumno);

$nombre = $_SESSION['Nombre_Alumno'] ?? 'Alumno';

include '../views/Index_alumno.php';
