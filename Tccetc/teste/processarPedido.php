<?php
$numeroMesa = $_POST['numero_mesa'];
$item = $_POST['item'];
$quantidade = $_POST['quantidade'];
$preco = $_POST['preco'];

// verificando se os dados do produto (item) existem ou n
$sqlProduto = "SELECT codProduto FROM produto WHERE nomeProduto = ?";
$stmt = $conn->prepare($sqlProduto);
$stmt->bind_param("s", $item);
$stmt->execute();
$stmt->bind_result($codProduto);
$stmt->fetch();

if ($codProduto) {
    // se o produto existe, inserimos o pedido
    $sqlPedido = "INSERT INTO pedido (codMesa, observacaoPedido, statusPedido) VALUES (?, ?, ?)";
    $statusPedido = 'Em aberto';  // Status do pedido, pode ser 'Em aberto', 'Finalizado', etc.
    $observacaoPedido = "Pedido de $quantidade x $item";

    //inserindo
    $stmtPedido = $conn->prepare($sqlPedido);
    $stmtPedido->bind_param("iss", $numeroMesa, $observacaoPedido, $statusPedido);
    $stmtPedido->execute();
    
    // pegando o ID do pedido inserido
    $codPedido = $stmtPedido->insert_id;
    
    // inserindo o item do pedido na tabela 'produtoPedido'
    $sqlProdutoPedido = "INSERT INTO produtoPedido (codProduto, codPedido, quantidade) VALUES (?, ?, ?)";
    $stmtProdutoPedido = $conn->prepare($sqlProdutoPedido);
    $stmtProdutoPedido->bind_param("iii", $codProduto, $codPedido, $quantidade);
    $stmtProdutoPedido->execute();

    // Após inserir, redireciona para a página da mesa
    header("Location: mesa.php?numero=$numeroMesa");
    exit();
} else {
    echo "Produto não encontrado.";
}

// Aqui você salvaria os dados no banco ou em um array
header("Location: mesa.php?numero=$numeroMesa");
exit();
?>
