<?php
require_once 'config.php';

class DashboardAlumnoModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function obtenerResumenTareas($id_alumno) {
        $sql = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN E.Archivo_Respuesta IS NOT NULL THEN 1 ELSE 0 END) AS entregadas,
                    SUM(CASE WHEN E.Archivo_Respuesta IS NULL THEN 1 ELSE 0 END) AS pendientes
                FROM Tarea_Alumno TA
                LEFT JOIN Entrega_Tarea E ON TA.ID_Tarea = E.ID_Tarea AND E.ID_Alumno = TA.ID_Alumno
                WHERE TA.ID_Alumno = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_alumno);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerProximaEntrega($id_alumno) {
        $sql = "SELECT T.Nombre_Tarea, T.Fecha_Entrega 
                FROM Tarea T
                INNER JOIN Tarea_Alumno TA ON T.ID_Tarea = TA.ID_Tarea
                LEFT JOIN Entrega_Tarea E ON T.ID_Tarea = E.ID_Tarea AND E.ID_Alumno = ?
                WHERE TA.ID_Alumno = ? AND E.ID_Alumno IS NULL
                ORDER BY T.Fecha_Entrega ASC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_alumno, $id_alumno);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
