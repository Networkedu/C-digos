<?php
session_start();
require_once '../models/EliminarClaseModel.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$id_clase = $_GET['id'] ?? null;
if (!$id_clase) {
    die("ID de clase no especificado.");
}

$model = new EliminarClaseModel();
$model->eliminarAsignaciones($id_clase);
$model->eliminarClase($id_clase);

header("Location: ../views/clases.php");
exit;
