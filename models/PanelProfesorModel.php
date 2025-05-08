<?php
require_once 'config.php';

class PanelProfesorModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function obtenerTareasPorProfesor($id_profe) {
        $sql = "SELECT T.*, 
                       (SELECT COUNT(*) FROM Tarea_Alumno TA WHERE TA.ID_Tarea = T.ID_Tarea) AS total_asignados,
                       (SELECT COUNT(*) FROM Entrega_Tarea E WHERE E.ID_Tarea = T.ID_Tarea) AS total_entregados
                FROM Tarea T
                WHERE T.ID_Profe = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_profe);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerAlumnosQueEntregaron($id_tarea) {
        $stmt = $this->conn->prepare("SELECT A.Nombre FROM Entrega_Tarea E INNER JOIN Alumno A ON A.ID_Alumno = E.ID_Alumno WHERE E.ID_Tarea = ?");
        $stmt->bind_param("i", $id_tarea);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTodosLosAlumnos() {
        $res = $this->conn->query("SELECT ID_Alumno, Nombre FROM Alumno");
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
?>
