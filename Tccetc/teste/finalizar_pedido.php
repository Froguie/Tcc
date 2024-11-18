<?php
session_start();
include("../backend/conexao.php");

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<p style='color: red;'>Carrinho vazio. Não é possível finalizar o pedido.</p>";
    exit;
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar os dados do formulário com validação básica
    $nome = trim($_POST['nome']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);

    if (empty($nome) || empty($endereco) || empty($telefone)) {
        echo "<p style='color: red;'>Por favor, preencha todos os campos obrigatórios.</p>";
        exit;
    }

    // Calcular o total do pedido
    $total = 0;
    foreach ($_SESSION['carrinho'] as $item) {
        $total += $item['precoProduto'] * $item['quantidade'];
    }

    // Simular pagamento fictício (simulação de sucesso)
    $pagamentoSimulado = 1; // Forçando pagamento bem-sucedido
    if ($pagamentoSimulado == 0) {
        echo "<p style='color: red;'>Pagamento falhou. Tente novamente.</p>";
        exit;
    }

    // Inserir o pedido no banco de dados
    $sqlPedido = "INSERT INTO pedidosfinal (nome, endereco, telefone, total) VALUES (?, ?, ?, ?)";
    $stmtPedido = $conexao->prepare($sqlPedido);
    $stmtPedido->bind_param("sssd", $nome, $endereco, $telefone, $total);

    if ($stmtPedido->execute()) {
        $pedidoId = $stmtPedido->insert_id;

        // Inserir os itens do pedido
        foreach ($_SESSION['carrinho'] as $item) {
            $sqlItem = "INSERT INTO itenspedidosfinal (pedido_id, produto_id, nomeProduto, precoProduto, quantidade) 
                        VALUES (?, ?, ?, ?, ?)";
            $stmtItem = $conexao->prepare($sqlItem);
            $stmtItem->bind_param("iisdi", $pedidoId, $item['codProduto'], $item['nomeProduto'], $item['precoProduto'], $item['quantidade']);
            $stmtItem->execute();
        }

        // Limpar o carrinho após o pedido ser concluído
        unset($_SESSION['carrinho']);

        // Redirecionar para a página de confirmação
        header("Location: pedido_concluido.php?id=$pedidoId");
        exit;
    } else {
        echo "<p style='color: red;'>Erro ao processar o pedido. Tente novamente.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-200">

<div class="max-w-4xl mx-auto p-6 bg-white shadow-md mt-10 rounded">
    <h2 class="text-3xl font-bold text-center mb-6">Finalizar Pedido</h2>

    <!-- Mostrar itens do carrinho -->
    <div class="mb-6">
        <h3 class="text-xl font-semibold">Itens no Carrinho</h3>
        <ul class="space-y-4">
            <?php
            $total = 0;
            foreach ($_SESSION['carrinho'] as $item) {
                echo "<li>";
                echo "<p><strong>" . htmlspecialchars($item['nomeProduto']) . "</strong> - R$ " . number_format($item['precoProduto'], 2, ',', '.') . " (Quantidade: " . $item['quantidade'] . ")</p>";
                $total += $item['precoProduto'] * $item['quantidade'];
                echo "</li>";
            }
            ?>
        </ul>

        <p class="mt-4 font-semibold">Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
    </div>

    <!-- Formulário para endereço -->
    <form method="POST" class="space-y-4">
        <div>
            <label for="nome" class="block text-gray-700 font-semibold">Nome Completo</label>
            <input type="text" name="nome" id="nome" required class="w-full p-3 border border-gray-300 rounded mt-2">
        </div>

        <div>
            <label for="numero da mesa" class="block text-gray-700 font-semibold">Número da Mesa</label>
            <input type="text" name="numero da mesa" id="numero da mesa" required class="w-full p-3 border border-gray-300 rounded mt-2">
        </div>

        <div>
    <label for="pagamento" class="block text-gray-700 font-semibold">Forma de Pagamento</label>
    <select name="pagamento" id="pagamento" required class="w-full p-3 border border-gray-300 rounded mt-2 bg-gray-50 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500">
        <option value="credito">Cartão de Crédito</option>
        <option value="debito">Cartão de Débito</option>
        <option value="dinheiro">Dinheiro</option>
        <option value="pix">PIX</option>
    </select>
</div>


        <button type="submit" class="w-full bg-orange-600 text-white py-3 rounded font-semibold hover:bg-orange-700">Confirmar Pedido</button>
    </form>
</div>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>
