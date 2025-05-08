<?php
require_once 'config.php';

class EditarClaseModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function obtenerClasePorId($id_clase) {
        $stmt = $this->conn->prepare("SELECT * FROM Clase WHERE ID_Clase = ?");
        $stmt->bind_param("i", $id_clase);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerTodosAlumnos() {
        return $this->conn->query("SELECT ID_Alumno, Nombre FROM Alumno")->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerAlumnosAsignados($id_clase) {
        $stmt = $this->conn->prepare("SELECT ID_Alumno FROM Alumno_Clase WHERE ID_Clase = ?");
        $stmt->bind_param("i", $id_clase);
        $stmt->execute();
        return array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'ID_Alumno');
    }

    public function actualizarClase($id_clase, $nombre, $archivo) {
        $stmt = $this->conn->prepare("UPDATE Clase SET Nombre_Clase = ?, Archivo = ? WHERE ID_Clase = ?");
        $stmt->bind_param("ssi", $nombre, $archivo, $id_clase);
        return $stmt->execute();
    }

    public function actualizarAlumnosAsignados($id_clase, $alumnos) {
        $this->conn->query("DELETE FROM Alumno_Clase WHERE ID_Clase = $id_clase");
        $stmt = $this->conn->prepare("INSERT INTO Alumno_Clase (ID_Clase, ID_Alumno) VALUES (?, ?)");
        foreach ($alumnos as $id_alumno) {
            $stmt->bind_param("ii", $id_clase, $id_alumno);
            $stmt->execute();
        }
    }
}
?>
