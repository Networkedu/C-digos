<?php
require_once 'config.php';

class EntregaTareaModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function guardarEntrega($id_tarea, $id_alumno, $archivo) {
        $stmt = $this->conn->prepare("
            INSERT INTO Entrega_Tarea (ID_Tarea, ID_Alumno, Archivo_Respuesta)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE Archivo_Respuesta = VALUES(Archivo_Respuesta)
        ");
        $stmt->bind_param("iis", $id_tarea, $id_alumno, $archivo);
        return $stmt->execute();
    }
}
?>
