<?php
session_start();
require_once '../models/PanelAlumnoModel.php';

if (!isset($_SESSION['ID_Alumno'])) {
    header("Location: ../login.php");
    exit;
}

$alumno_id = $_SESSION['ID_Alumno'];
$nombre_alumno = $_SESSION['Nombre_Alumno'];

$model = new PanelAlumnoModel();
$tareas = $model->obtenerTareasConEntrega($alumno_id);

$pendientes = array_filter($tareas, fn($t) => !$t['Archivo_Respuesta']);
$entregadas = array_filter($tareas, fn($t) => $t['Archivo_Respuesta']);

include '../views/panel_alumno.php';
