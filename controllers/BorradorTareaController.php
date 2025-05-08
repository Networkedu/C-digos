<?php
session_start();
require_once '../models/BorradorTareaModel.php';
require_once '../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$model = new BorradorTareaModel();
$idProfe = $_SESSION['ID_Profe'];

// Guardar nuevo borrador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'guardar') {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $alumnos = $_POST['alumnos'];
    $asunto = $_POST['asunto'];
    $model->guardar($nombre, $fecha, $alumnos, $asunto, $idProfe);
    header("Location: ../views/borradorTarea.php?mensaje=guardado");
    exit;
}

// Eliminar borrador
if (isset($_GET['eliminar'])) {
    $id = (int) $_GET['eliminar'];
    $model->eliminar($id, $idProfe);
    header("Location: ../views/borradorTarea.php?mensaje=eliminado");
    exit;
}
