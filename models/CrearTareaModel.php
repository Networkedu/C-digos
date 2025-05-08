


<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../config.php';

class CrearTareaModel {
    public function crearTarea($nombre, $descripcion, $fecha_entrega, $archivo, $id_profe) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO tareas (Nombre_Tarea, Descripcion, Fecha_Entrega, Archivo, ID_Profe) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nombre, $descripcion, $fecha_entrega, $archivo, $id_profe);
        $stmt->execute();
        return $conn->insert_id;
    }

    public function asignarAlumnos($id_tarea, $alumnos) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO tareas_asignadas (ID_Tarea, ID_Alumno) VALUES (?, ?)");
        foreach ($alumnos as $id_alumno) {
            $stmt->bind_param("ii", $id_tarea, $id_alumno);
            $stmt->execute();
        }
    }
}


?>
