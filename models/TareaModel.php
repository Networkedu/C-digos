<?php
require_once __DIR__ . '/../conexion.php';

class TareaModel {
    public function actualizarTarea($id, $nombre, $descripcion, $fecha_entrega, $archivo = null) {
        global $conn;

        if ($archivo) {
            $sql = "UPDATE tareas SET Nombre_Tarea=?, Descripcion=?, Fecha_Entrega=?, Archivo=? WHERE ID_Tarea=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $nombre, $descripcion, $fecha_entrega, $archivo, $id);
        } else {
            $sql = "UPDATE tareas SET Nombre_Tarea=?, Descripcion=?, Fecha_Entrega=? WHERE ID_Tarea=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nombre, $descripcion, $fecha_entrega, $id);
        }

        return $stmt->execute();
    }

    public function actualizarAsignaciones($id_tarea, $alumnos) {
        global $conn;

        // Eliminar asignaciones actuales
        $stmt_delete = $conn->prepare("DELETE FROM tareas_asignadas WHERE ID_Tarea = ?");
        $stmt_delete->bind_param("i", $id_tarea);
        $stmt_delete->execute();

        // Insertar nuevas asignaciones
        $stmt_insert = $conn->prepare("INSERT INTO tareas_asignadas (ID_Tarea, ID_Alumno) VALUES (?, ?)");
        foreach ($alumnos as $id_alumno) {
            $stmt_insert->bind_param("ii", $id_tarea, $id_alumno);
            $stmt_insert->execute();
        }
    }
}
