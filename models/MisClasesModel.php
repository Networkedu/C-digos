<?php
require_once 'config.php';

class MisClasesModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function crearClase($nombre, $archivo, $id_profe) {
        $stmt = $this->conn->prepare("INSERT INTO Clase (Nombre_Clase, ID_Profe, Archivo) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $nombre, $id_profe, $archivo);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    public function asignarAlumnos($id_clase, $alumnos) {
        $stmt = $this->conn->prepare("INSERT INTO Alumno_Clase (ID_Clase, ID_Alumno) VALUES (?, ?)");
        foreach ($alumnos as $id_alumno) {
            $stmt->bind_param("ii", $id_clase, $id_alumno);
            $stmt->execute();
        }
    }

    public function obtenerClasesDelProfesor($id_profe) {
        $stmt = $this->conn->prepare("SELECT ID_Clase, Nombre_Clase, Archivo FROM Clase WHERE ID_Profe = ?");
        $stmt->bind_param("i", $id_profe);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function obtenerAlumnos() {
        return $this->conn->query("SELECT ID_Alumno, Nombre FROM Alumno");
    }
}
?>
