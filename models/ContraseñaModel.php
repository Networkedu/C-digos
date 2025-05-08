<?php
require_once 'config.php';

class ContraseñaModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function obtenerHashActual($id, $tipo) {
        if ($tipo === 'alumno') {
            $stmt = $this->conn->prepare("SELECT Contrasena FROM Alumno WHERE ID_Alumno = ?");
        } else {
            $stmt = $this->conn->prepare("SELECT Contrasena FROM Profesor WHERE ID_Profe = ?");
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['Contrasena'];
    }

    public function actualizar($id, $tipo, $nuevo_hash) {
        if ($tipo === 'alumno') {
            $stmt = $this->conn->prepare("UPDATE Alumno SET Contrasena = ? WHERE ID_Alumno = ?");
        } else {
            $stmt = $this->conn->prepare("UPDATE Profesor SET Contrasena = ? WHERE ID_Profe = ?");
        }
        $stmt->bind_param("si", $nuevo_hash, $id);
        return $stmt->execute();
    }
}
?>
