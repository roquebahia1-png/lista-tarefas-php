<?php
$host = "localhost";
$usuario = "root"; // padrão XAMPP
$senha = "";       // padrão XAMPP
$banco = "tarefas_db";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
