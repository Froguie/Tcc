<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


$numeroMesa = $_POST['numero_mesa'];
$item = $_POST['item'];
$quantidade = $_POST['quantidade'];
$preco = $_POST['preco'];

// Adicionar pedido ao banco de dados
$sql = "INSERT INTO pedidos (mesa_id, item, quantidade, preco) VALUES ('$numeroMesa', '$item', '$quantidade', '$preco')";
if ($conn->query($sql) === TRUE) {
    $_SESSION['mensagem'] = "Pedido adicionado com sucesso!";
} else {
    $_SESSION['mensagem'] = "Erro ao adicionar pedido: " . $conn->error;
}

// Atualizar status da mesa para 'Ocupada'
$conn->query("UPDATE mesas SET status = 'Ocupada' WHERE id = '$numeroMesa'");

// Redirecionar para a página da mesa
header("Location: mesa.php?numero=$numeroMesa");
exit();
?>
