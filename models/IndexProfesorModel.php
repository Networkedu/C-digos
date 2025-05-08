<?php
require_once 'config.php';

class DashboardProfesorModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function obtenerResumenTareas($id_profe) {
        $sql = "SELECT 
                    COUNT(*) AS total,
                    SUM(
                        CASE WHEN 
                            (SELECT COUNT(*) FROM Tarea_Alumno TA WHERE TA.ID_Tarea = T.ID_Tarea) =
                            (SELECT COUNT(*) FROM Entrega_Tarea E WHERE E.ID_Tarea = T.ID_Tarea)
                        THEN 1 ELSE 0 END
                    ) AS completadas
                FROM Tarea T
                WHERE T.ID_Profe = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_profe);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerUltimaTarea($id_profe) {
        $stmt = $this->conn->prepare("SELECT Nombre_Tarea, Fecha_Entrega 
                                      FROM Tarea 
                                      WHERE ID_Profe = ? 
                                      ORDER BY Fecha_Entrega DESC 
                                      LIMIT 1");
        $stmt->bind_param("i", $id_profe);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
