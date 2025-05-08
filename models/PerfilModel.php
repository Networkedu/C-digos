<?php
require_once 'config.php';

class PerfilModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function obtenerDatos($id, $tipo) {
        if ($tipo === 'alumno') {
            $sql = "SELECT Nombre, Descripcion, Fecha_Alta, FotoPerfil FROM Alumno WHERE ID_Alumno = ?";
        } else {
            $sql = "SELECT Nombre_Profe as Nombre, Descripcion, Fecha_Alta, FotoPerfil FROM Profesor WHERE ID_Profe = ?";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerClases($id, $tipo) {
        if ($tipo === 'alumno') {
            $sql = "SELECT C.Nombre_Clase FROM Clase C JOIN Alumno_Clase CA ON C.ID_Clase = CA.ID_Clase WHERE CA.ID_Alumno = ?";
        } else {
            $sql = "SELECT Nombre_Clase FROM Clase WHERE ID_Profe = ?";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function contarTareas($id, $tipo) {
        if ($tipo === 'alumno') {
            $sql = "SELECT COUNT(*) as total FROM Entrega_Tarea WHERE ID_Alumno = ?";
        } else {
            $sql = "SELECT COUNT(*) as total FROM Tarea WHERE ID_Profe = ?";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
}
?>
