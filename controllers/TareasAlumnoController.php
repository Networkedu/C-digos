<?php
session_start();
require_once '../models/TareasAlumnoModel.php';

if (!isset($_SESSION['ID_Alumno'])) {
    header("Location: ../login.php");
    exit();
}

$id_alumno = $_SESSION['ID_Alumno'];

$model = new TareasAlumnoModel();
$tareas_pendientes = $model->obtenerTareasPendientes($id_alumno);
$tareas_enviadas = $model->obtenerTareasEnviadas($id_alumno);

include '../views/tareas_alumno.php';
?>
