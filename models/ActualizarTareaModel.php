<?php
require_once 'config.php';

class TareaModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function actualizarTarea($id_tarea, $nombre, $fecha, $id_profe) {
        $stmt = $this->conn->prepare("
            UPDATE Tarea SET Nombre_Tarea = ?, Fecha_Entrega = ? 
            WHERE ID_Tarea = ? AND ID_Profe = ?
        ");
        $stmt->bind_param("ssii", $nombre, $fecha, $id_tarea, $id_profe);
        return $stmt->execute();
    }
}
?>
