<?php
$numeroMesa = $_POST['numero_mesa'];
$item = $_POST['item'];
$quantidade = $_POST['quantidade'];
$preco = $_POST['preco'];

// Aqui você salvaria os dados no banco ou em um array
header("Location: mesa.php?numero=$numeroMesa");
exit();
?>
