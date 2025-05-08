<?php
session_start();
require_once '../models/TareaProfesorModel.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$model = new TareaProfesorModel();
$tareas = $model->obtenerPorProfesor($_SESSION['ID_Profe']);
