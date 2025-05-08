<?php
require_once 'config.php';

class LoginModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function obtenerProfesorPorCorreo($correo) {
        $stmt = $this->conn->prepare("SELECT ID_Profe, Nombre_Profe, Contraseña_Profe FROM Profesor WHERE Correo_Profe = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerAlumnoPorCorreo($correo) {
        $stmt = $this->conn->prepare("SELECT ID_Alumno, Nombre, Contraseña FROM Alumno WHERE Correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
