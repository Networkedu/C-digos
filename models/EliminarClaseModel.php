<?php
require_once 'config.php';

class EliminarClaseModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function eliminarAsignaciones($id_clase) {
        $stmt = $this->conn->prepare("DELETE FROM Alumno_Clase WHERE ID_Clase = ?");
        $stmt->bind_param("i", $id_clase);
        return $stmt->execute();
    }

    public function eliminarClase($id_clase) {
        $stmt = $this->conn->prepare("DELETE FROM Clase WHERE ID_Clase = ?");
        $stmt->bind_param("i", $id_clase);
        return $stmt->execute();
    }
}
?>
