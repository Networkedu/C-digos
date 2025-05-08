<?php
session_start();
include 'conexion.php';

$id_profe = $_SESSION['ID_Profe'];
$sql = "SELECT * FROM Tarea WHERE ID_Profe = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_profe);
$stmt->execute();
$resultado = $stmt->get_result();

while ($fila = $resultado->fetch_assoc()) {
    echo "<p><strong>{$fila['Nombre_Tarea']}</strong> - Entrega: {$fila['Fecha_Entrega']}
          <a href='editar_tarea.php?id={$fila['ID_Tarea']}'>✏️</a>
          <a href='eliminar_tarea.php?id={$fila['ID_Tarea']}'>🗑️</a></p>";
}
?>