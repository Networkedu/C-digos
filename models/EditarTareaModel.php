<?php
require_once 'config.php';

class EditarTareaModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function obtenerTarea($id_tarea, $id_profe) {
        $stmt = $this->conn->prepare("SELECT * FROM Tarea WHERE ID_Tarea = ? AND ID_Profe = ?");
        $stmt->bind_param("ii", $id_tarea, $id_profe);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
