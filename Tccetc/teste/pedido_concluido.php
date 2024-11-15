<?php
session_start();
include("../backend/conexao.php");

if (!isset($_GET['id'])) {
    echo "Pedido não encontrado.";
    exit;
}

$pedidoId = $_GET['id'];

// Buscar os detalhes do pedido
$sqlPedido = "SELECT * FROM pedidosfinal WHERE id = ?";
$stmtPedido = $conexao->prepare($sqlPedido);
$stmtPedido->bind_param("i", $pedidoId);
$stmtPedido->execute();
$result = $stmtPedido->get_result();

if ($result->num_rows === 0) {
    echo "Pedido não encontrado.";
    exit;
}

$pedido = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Concluído</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200">

<div class="max-w-4xl mx-auto p-6 bg-white shadow-md mt-10 rounded">
    <h2 class="text-3xl font-bold text-center mb-6">Pedido Concluído</h2>

    <h3 class="text-xl font-semibold">Obrigado, <?php echo htmlspecialchars($pedido['nome']); ?>!</h3>
    <p class="mt-4">Seu pedido foi realizado com sucesso. Abaixo estão os detalhes do seu pedido:</p>

    <div class="mt-6">
        <p><strong>Endereço:</strong> <?php echo htmlspecialchars($pedido['endereco']); ?></p>
        <p><strong>Telefone:</strong> <?php echo htmlspecialchars($pedido['telefone']); ?></p>
        <p><strong>Total:</strong> R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>
        <p><strong>Status do pagamento:</strong> Pago com sucesso!</p>
    </div>

    <div class="mt-6">
        <h4 class="text-lg font-semibold">Itens do Pedido:</h4>
        <ul>
            <?php
            // Buscar os itens do pedido
            $sqlItens = "SELECT * FROM itenspedidosfinal WHERE pedido_id = ?";
            $stmtItens = $conexao->prepare($sqlItens);
            $stmtItens->bind_param("i", $pedidoId);
            $stmtItens->execute();
            $resultItens = $stmtItens->get_result();

            while ($item = $resultItens->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($item['nomeProduto']) . " - R$ " . number_format($item['precoProduto'], 2, ',', '.') . " (Quantidade: " . $item['quantidade'] . ")</li>";
            }
            ?>
        </ul>
    </div>

    <div class="mt-6">
        <a href="../index.php" class="bg-orange-600 text-white py-2 px-4 rounded hover:bg-orange-700">Voltar para a loja</a>
    </div>
</div>

</body>
</html>
