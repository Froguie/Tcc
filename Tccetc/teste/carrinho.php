<?php
session_start();

// Verificar se o carrinho existe
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Lógica para manipulação do carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['atualizarCarrinho'])) {
        // Atualizar as quantidades
        foreach ($_POST['quantidades'] as $index => $novaQuantidade) {
            $_SESSION['carrinho'][$index]['quantidade'] = max(1, intval($novaQuantidade));
        }
        header("Location: carrinho.php");
        exit;
    } elseif (isset($_POST['limparCarrinho'])) {
        // Limpar o carrinho
        $_SESSION['carrinho'] = [];
        
        // Redirecionar para a página cardapio.php após limpar a comanda
        header("Location: cardapio.php");
        exit;
    }
}

// Calcular o total do carrinho
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['precoProduto'] * $item['quantidade'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Container Principal -->
    <div class="max-w-4xl mx-auto p-8 bg-white shadow-lg rounded-lg mt-10">

        <!-- Título -->
        <h2 class="text-3xl font-bold text-center mb-6 text-orange-600">Sua Comanda</h2>

        <form method="POST">

            <!-- Exibição do Carrinho -->
            <ul class="space-y-6">
                <?php foreach ($_SESSION['carrinho'] as $index => $item): ?>
                    <li
                        class="flex justify-between items-center p-4 bg-gray-50 rounded-lg border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 ease-in-out">
                        <div class="flex items-center space-x-4">
                            <div>
                                <p class="text-lg font-semibold text-gray-800">
                                    <?php echo htmlspecialchars($item['nomeProduto']); ?></p>
                                <p class="text-sm text-gray-500">Preço: R$
                                    <?php echo number_format($item['precoProduto'], 2, ',', '.'); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <input type="number" name="quantidades[<?php echo $index; ?>]"
                                value="<?php echo $item['quantidade']; ?>" min="1"
                                class="border w-20 text-center rounded-md py-1 focus:ring-2 focus:ring-blue-500 transition-all duration-300" />
                            <p class="text-lg font-semibold text-gray-800">R$
                                <?php echo number_format($item['precoProduto'] * $item['quantidade'], 2, ',', '.'); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="mt-6 text-right">
                <p class="text-xl font-semibold text-gray-900">Total: R$
                    <?php echo number_format($total, 2, ',', '.'); ?></p>
            </div>

            <!-- Botões -->
            <div class="mt-8 flex justify-between items-center">
                <button type="submit" name="atualizarCarrinho"
                    class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-all focus:ring-4 focus:ring-blue-200 active:scale-95">
                    Atualizar Comanda
                </button>
                <button type="submit" name="limparCarrinho"
                    class="bg-red-500 text-white py-2 px-6 rounded-lg hover:bg-red-700 transition-all focus:ring-4 focus:ring-red-200 active:scale-95">
                    Limpar Comanda
                </button>
                <a href="finalizar_pedido.php"
                    class="bg-orange-500 text-white py-2 px-6 rounded-lg hover:bg-orange-700 transition-all focus:ring-4 focus:ring-orange-200 active:scale-95">
                    Finalizar Compra
                </a>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>
