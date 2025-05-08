<?php
require_once 'config.php';

class ClasesAlumnoModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function obtenerClases($id_alumno) {
        $sql = "SELECT C.ID_Clase, C.Nombre_Clase, C.Icono, C.Color
                FROM Clase C
                INNER JOIN Alumno_Clase AC ON C.ID_Clase = AC.ID_Clase
                WHERE AC.ID_Alumno = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_alumno);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTareasDeClase($id_clase) {
        $stmt = $this->conn->prepare("SELECT * FROM Tarea WHERE ID_Clase = ?");
        $stmt->bind_param("i", $id_clase);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function tareaEntregada($id_alumno, $id_tarea) {
        $stmt = $this->conn->prepare("SELECT * FROM Entrega_Tarea WHERE ID_Alumno = ? AND ID_Tarea = ?");
        $stmt->bind_param("ii", $id_alumno, $id_tarea);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerAlumnosDeClase($id_clase) {
        $stmt = $this->conn->prepare("SELECT A.Nombre FROM Alumno_Clase AC
                                      INNER JOIN Alumno A ON A.ID_Alumno = AC.ID_Alumno
                                      WHERE AC.ID_Clase = ?");
        $stmt->bind_param("i", $id_clase);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
