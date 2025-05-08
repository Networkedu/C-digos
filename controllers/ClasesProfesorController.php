<?php
session_start();
require_once '../models/ClasesProfesorModel.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$profe_id = $_SESSION['ID_Profe'];
$nombre = $_SESSION['Nombre_Profe'];

$model = new ClasesProfesorModel();

// Crear nueva clase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_clase'])) {
    $nombre_clase = $_POST['nombre_clase'];
    $archivo_nombre = '';
    $icono = $_POST['icono'] ?? '📘';
    $color = $_POST['color'] ?? 'bg-white';

    if (!empty($_FILES['archivo']['name'])) {
        $archivo = $_FILES['archivo'];
        $archivo_nombre = time() . '_' . basename($archivo['name']);
        $ruta_destino = '../uploads/' . $archivo_nombre;
        move_uploaded_file($archivo['tmp_name'], $ruta_destino);
    }

    $id_clase = $model->crearClase($nombre_clase, $profe_id, $archivo_nombre, $icono, $color);

    if (!empty($_POST['alumnos'])) {
        $model->asignarAlumnos($id_clase, $_POST['alumnos']);
    }
}

// Obtener clases y alumnos
$clases = $model->obtenerClases($profe_id);
$alumnos = $model->obtenerAlumnos();

// Obtener alumnos de cada clase
$clases_completas = [];
foreach ($clases as $clase) {
    $clase['alumnos'] = $model->obtenerAlumnosDeClase($clase['ID_Clase']);
    $clases_completas[] = $clase;
}

include '../views/clases_profesor.php';
?>
