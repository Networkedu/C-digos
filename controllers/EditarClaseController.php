<?php
session_start();
require_once '../models/EditarClaseModel.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$model = new EditarClaseModel();

$id_clase = $_GET['id'] ?? null;
if (!$id_clase) {
    die("ID de clase no proporcionado.");
}

$clase = $model->obtenerClasePorId($id_clase);
if (!$clase) {
    die("Clase no encontrada.");
}

$alumnos = $model->obtenerTodosAlumnos();
$asignados = $model->obtenerAlumnosAsignados($id_clase);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_nombre = $_POST['nombre_clase'];
    $archivo_nombre = $clase['Archivo'];

    if (!empty($_FILES['archivo']['name'])) {
        $archivo_nombre = time() . '_' . basename($_FILES['archivo']['name']);
        move_uploaded_file($_FILES['archivo']['tmp_name'], "../uploads/" . $archivo_nombre);
    }

    $model->actualizarClase($id_clase, $nuevo_nombre, $archivo_nombre);

    $alumnos_seleccionados = $_POST['alumnos'] ?? [];
    $model->actualizarAlumnosAsignados($id_clase, $alumnos_seleccionados);

    header("Location: clases.php");
    exit;
}

include '../views/editar_clase.php';
