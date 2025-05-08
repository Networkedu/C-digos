<?php
session_start();
require_once '../models/EliminarTareaModel.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$id_tarea = $_GET['id'] ?? null;
if (!$id_tarea) {
    die("ID de tarea no especificado.");
}

$model = new EliminarTareaModel();
$model->eliminarEntregas($id_tarea);
$model->eliminarAsignaciones($id_tarea);
$model->eliminarTarea($id_tarea);

header("Location: ../vistas/Panel_profesor.php");
exit;
