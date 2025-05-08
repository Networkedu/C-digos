<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../conexion.php';

class EliminarTareaModel {
    public function eliminarEntregas($id_tarea) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM entregas WHERE ID_Tarea = ?");
        $stmt->bind_param("i", $id_tarea);
        $stmt->execute();
    }

    public function eliminarAsignaciones($id_tarea) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM tareas_asignadas WHERE ID_Tarea = ?");
        $stmt->bind_param("i", $id_tarea);
        $stmt->execute();
    }

    public function eliminarTarea($id_tarea) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM tareas WHERE ID_Tarea = ?");
        $stmt->bind_param("i", $id_tarea);
        $stmt->execute();
    }
}
