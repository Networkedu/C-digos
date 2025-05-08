<?php
session_start();
require_once '../models/DashboardProfesorModel.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$model = new DashboardProfesorModel();
$id_profe = $_SESSION['ID_Profe'];

$resumen = $model->obtenerResumenTareas($id_profe);
$ultima = $model->obtenerUltimaTarea($id_profe);
$nombre = $_SESSION['Nombre_Profe'] ?? 'Profesor';

include '../views/Index_profesor.php';

