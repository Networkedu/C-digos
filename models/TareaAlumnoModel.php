<?php
require_once 'config.php';

class TareasAlumnoModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function obtenerTareasPendientes($id_alumno) {
        $sql = "SELECT t.ID_Tarea, t.Nombre_Tarea AS nombre, t.Fecha_Entrega AS fecha, 
                       p.Nombre_Profe AS profesor, t.Asignatura AS asunto
                FROM Tarea t
                JOIN Profesor p ON t.ID_Profe = p.ID_Profe
                JOIN Clase c ON t.ID_Clase = c.ID_Clase
                JOIN Alumno_Clase ac ON c.ID_Clase = ac.ID_Clase
                WHERE ac.ID_Alumno = ?
                AND t.ID_Tarea NOT IN (
                    SELECT ID_Tarea FROM Tarea_Entregada WHERE ID_Alumno = ?
                )";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_alumno, $id_alumno);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTareasEnviadas($id_alumno) {
        $sql = "SELECT t.Nombre_Tarea AS nombre, te.Fecha_Entrega AS fecha
                FROM Tarea_Entregada te
                JOIN Tarea t ON te.ID_Tarea = t.ID_Tarea
                WHERE te.ID_Alumno = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_alumno);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>