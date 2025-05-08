<?php
require_once 'config.php';

class ClasesProfesorModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function crearClase($nombre_clase, $profe_id, $archivo, $icono, $color) {
        $stmt = $this->conn->prepare("INSERT INTO Clase (Nombre_Clase, ID_Profe, Archivo, Icono, Color) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $nombre_clase, $profe_id, $archivo, $icono, $color);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function asignarAlumnos($id_clase, $alumnos) {
        $stmt = $this->conn->prepare("INSERT INTO Alumno_Clase (ID_Clase, ID_Alumno) VALUES (?, ?)");
        foreach ($alumnos as $id_alumno) {
            $stmt->bind_param("ii", $id_clase, $id_alumno);
            $stmt->execute();
        }
    }

    public function obtenerClases($profe_id) {
        $stmt = $this->conn->prepare("SELECT ID_Clase, Nombre_Clase, Archivo, Icono, Color FROM Clase WHERE ID_Profe = ?");
        $stmt->bind_param("i", $profe_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerAlumnos() {
        $res = $this->conn->query("SELECT ID_Alumno, Nombre FROM Alumno");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerAlumnosDeClase($id_clase) {
        $stmt = $this->conn->prepare("SELECT A.Nombre FROM Alumno_Clase AC INNER JOIN Alumno A ON AC.ID_Alumno = A.ID_Alumno WHERE AC.ID_Clase = ?");
        $stmt->bind_param("i", $id_clase);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
