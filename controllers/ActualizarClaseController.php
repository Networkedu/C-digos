<?php
session_start();
require_once '../conexion.php';

if (!isset($_SESSION['ID_Profe'])) {
    header("Location: ../login.php");
    exit;
}

$id_clase = $_POST['id_clase'] ?? null;
$nuevo_nombre = $_POST['nombre_clase'] ?? '';
$alumnos = $_POST['alumnos'] ?? [];

if (!$id_clase || !$nuevo_nombre) {
    die("Datos incompletos.");
}

$stmt_ver = $conn->prepare("SELECT * FROM clases2 WHERE ID_Clase = ? AND ID_Profe = ?");
$stmt_ver->bind_param("ii", $id_clase, $_SESSION['ID_Profe']);
$stmt_ver->execute();
$res_ver = $stmt_ver->get_result();
$clase = $res_ver->fetch_assoc();

if (!$clase) {
    die("Clase no encontrada o sin permiso.");
}
$archivo_nombre = $clase['Archivo'];
if (!empty($_FILES['archivo']['name']) && $_FILES['archivo']['error'] === 0) {
    $permitidos = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'];
    $extension = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));

   
    $archivo_nombre = time() . '_' . basename($_FILES['archivo']['name']);
    move_uploaded_file($_FILES['archivo']['tmp_name'], "../uploads/" . $archivo_nombre);
}


$stmt_upd = $conn->prepare("UPDATE clases2 SET Nombre_Clase = ?, Archivo = ? WHERE ID_Clase = ?");
$stmt_upd->bind_param("ssi", $nuevo_nombre, $archivo_nombre, $id_clase);
$stmt_upd->execute();


$conn->query("DELETE FROM clase_alumno WHERE ID_Clase = $id_clase");
if (!empty($alumnos)) {
    $stmt_ins = $conn->prepare("INSERT INTO clase_alumno (ID_Clase, ID_Alumno) VALUES (?, ?)");
    foreach ($alumnos as $id_alumno) {
        $stmt_ins->bind_param("ii", $id_clase, $id_alumno);
        $stmt_ins->execute();
    }
}


header("Location: ../vistas/Clase_Profesor.php?nueva_clase=$id_clase");
exit;


