<?php
session_start();
require_once '../models/TareaModel.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.html");
    exit();
}

$id_tarea = $_POST['id_tarea'] ?? null;
$nombre_tarea = $_POST['nombre_tarea'] ?? null;
$fecha_entrega = $_POST['fecha_entrega'] ?? null;

if (!$id_tarea || !$nombre_tarea || !$fecha_entrega) {
    echo "❌ Faltan datos.";
    exit();
}

$model = new TareaModel();
$ok = $model->actualizarTarea($id_tarea, $nombre_tarea, $fecha_entrega, $_SESSION['ID_Profe']);

if ($ok) {
    header("Location: ../vistas/Panel_profesor.php"); // o tu ruta MVC real
    exit();
} else {
    echo "❌ Error al actualizar la tarea.";
}
?>
