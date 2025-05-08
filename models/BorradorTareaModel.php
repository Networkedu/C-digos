<?php
require_once 'conexion.php';

class BorradorTareaModel {
    private $conn;

    public function __construct() {
        $this->conn = $GLOBALS['conn'];
    }

    public function obtenerPorProfesor($idProfe) {
        $stmt = $this->conn->prepare("SELECT * FROM Tarea_Borrador WHERE ID_Profe = ?");
        $stmt->bind_param("i", $idProfe);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function guardar($nombre, $fecha, $alumnos, $asunto, $idProfe) {
        $stmt = $this->conn->prepare("INSERT INTO Tarea_Borrador (Nombre, Fecha_Entrega, Alumnos, Asunto, ID_Profe)
                                      VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nombre, $fecha, $alumnos, $asunto, $idProfe);
        return $stmt->execute();
    }

    public function eliminar($id, $idProfe) {
        $stmt = $this->conn->prepare("DELETE FROM Tarea_Borrador WHERE ID_Borrador = ? AND ID_Profe = ?");
        $stmt->bind_param("ii", $id, $idProfe);
        return $stmt->execute();
    }
}
