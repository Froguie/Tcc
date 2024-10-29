<?php
$numeroMesa = $_GET['numero'];
$status = $_GET['status'];

// Aqui você atualizaria o status no banco de dados
header("Location: telaMesas.php");
exit();
?>
