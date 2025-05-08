<?php
session_start();
require_once '../models/PanelProfesorModel.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$model = new PanelProfesorModel();
$profe_id = $_SESSION['ID_Profe'];
$nombre_profe = $_SESSION['Nombre_Profe'];

$tareas = $model->obtenerTareasPorProfesor($profe_id);
$tareas_pendientes = [];
$tareas_completadas = [];

foreach ($tareas as &$tarea) {
    $tarea['alumnos_entregaron'] = $model->obtenerAlumnosQueEntregaron($tarea['ID_Tarea']);
    if ($tarea['total_asignados'] > 0 && $tarea['total_entregados'] == $tarea['total_asignados']) {
        $tareas_completadas[] = $tarea;
    } else {
        $tareas_pendientes[] = $tarea;
    }
}

$alumnos_disponibles = $model->obtenerTodosLosAlumnos();

include '../views/panel_profesor.php';
