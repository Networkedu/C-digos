<?php
session_start();
require_once '../models/EditarTareaModel.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$id_tarea = $_GET['id'] ?? null;
if (!$id_tarea) {
    die("ID de tarea no proporcionado.");
}

$model = new EditarTareaModel();
$tarea = $model->obtenerTarea($id_tarea, $_SESSION['ID_Profe']);

include '../views/editar_tarea.php';
