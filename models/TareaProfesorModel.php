<?php
require_once 'conexion.php';

class TareaProfesorModel {
    private $conn;

    public function __construct() {
        $this->conn = $GLOBALS['conn'];
    }

    public function obtenerPorProfesor($idProfe) {
        $stmt = $this->conn->prepare("SELECT * FROM Tarea WHERE ID_Profe = ?");
        $stmt->bind_param("i", $idProfe);
        $stmt->execute();
        return $stmt->get_result();
    }
}
