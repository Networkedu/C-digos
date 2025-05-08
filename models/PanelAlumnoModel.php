<?php
require_once 'config.php';

class PanelAlumnoModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function obtenerTareasConEntrega($id_alumno) {
        $sql = "SELECT T.*, E.Archivo_Respuesta
                FROM Tarea T
                INNER JOIN Tarea_Alumno TA ON T.ID_Tarea = TA.ID_Tarea
                LEFT JOIN Entrega_Tarea E ON T.ID_Tarea = E.ID_Tarea AND E.ID_Alumno = ?
                WHERE TA.ID_Alumno = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_alumno, $id_alumno);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
