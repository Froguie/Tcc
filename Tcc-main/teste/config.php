<?php

$host = "localhost"; 
$user = "root";      
$password = "";      
$database = "tastXbd";

// cria a conexão
$conn = new mysqli($host, $user, $password, $database);

// verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
