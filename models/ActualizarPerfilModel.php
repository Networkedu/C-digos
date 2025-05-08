<?php
require_once 'config.php';

class PerfilModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function actualizarPerfil($tipo, $nombre, $foto_ruta, $id) {
        if ($tipo === 'alumno') {
            $stmt = $this->conn->prepare("UPDATE Alumno SET Nombre = ?, FotoPerfil = ? WHERE ID_Alumno = ?");
        } else {
            $stmt = $this->conn->prepare("UPDATE Profesor SET Nombre_Profe = ?, FotoPerfil = ? WHERE ID_Profe = ?");
        }
        $stmt->bind_param("ssi", $nombre, $foto_ruta, $id);
        return $stmt->execute();
    }
}
?>
