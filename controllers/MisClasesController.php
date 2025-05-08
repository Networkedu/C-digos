<?php
session_start();
require_once '../models/MisClasesModel.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$model = new MisClasesModel();
$profe_id = $_SESSION['ID_Profe'];
$nombre = $_SESSION['Nombre_Profe'];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_clase'])) {
    $nombre_clase = $_POST['nombre_clase'];
    $archivo_nombre = '';

    if (!empty($_FILES['archivo']['name'])) {
        $archivo = $_FILES['archivo'];
        $archivo_nombre = time() . '_' . basename($archivo['name']);
        move_uploaded_file($archivo['tmp_name'], __DIR__ . '/../uploads/' . $archivo_nombre);
    }

    $id_clase = $model->crearClase($nombre_clase, $archivo_nombre, $profe_id);

    if (!empty($_POST['alumnos'])) {
        $alumnos = array_map('intval', $_POST['alumnos']);
        $model->asignarAlumnos($id_clase, $alumnos);
    }

    // Redirigir para evitar reenvíos
    header("Location: MisClasesController.php");
    exit;
}

$clases = $model->obtenerClasesDelProfesor($profe_id);
$alumnos_result = $model->obtenerAlumnos();

include '../views/mis_clases.php';
