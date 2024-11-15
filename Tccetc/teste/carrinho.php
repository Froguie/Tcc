<?php
session_start();

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<p class='text-center text-red-500 font-semibold'>Carrinho vazio.</p>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100">

    <!-- Container Principal -->
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-10">

        <!-- Título -->
        <h2 class="text-3xl font-bold text-center mb-6 text-orange-600">Seu Carrinho</h2>

        <!-- Lista de Itens -->
        <ul class="space-y-6">
            <?php
            $total = 0;
            foreach ($_SESSION['carrinho'] as $item) {
                echo "<li class='flex justify-between items-center p-4 bg-gray-50 rounded-lg border border-gray-200'>";
                echo "<div>";
                echo "<p class='text-lg font-semibold text-gray-800'>" . htmlspecialchars($item['nomeProduto']) . "</p>";
                echo "<p class='text-sm text-gray-500'>Preço: R$ " . number_format($item['precoProduto'], 2, ',', '.') . "</p>";
                echo "<p class='text-sm text-gray-500'>Quantidade: " . $item['quantidade'] . "</p>";
                echo "</div>";
                echo "<div class='text-right'>";
                echo "<p class='font-semibold text-lg text-gray-900'>R$ " . number_format($item['precoProduto'] * $item['quantidade'], 2, ',', '.') . "</p>";
                echo "</div>";
                echo "</li>";
                $total += $item['precoProduto'] * $item['quantidade'];
            }
            ?>
        </ul>

        <!-- Total -->
        <div class="mt-6 text-right">
            <p class='text-xl font-semibold text-gray-900'>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?>
            </p>
        </div>

        <!-- Botão Finalizar Pedido -->
        <div class="mt-8 text-center">
            <a href="finalizar_pedido.php"
                class="bg-orange-300 text-white py-2 px-6 rounded-lg hover:bg-orange-500 transition duration-300">Finalizar
                Pedido</a>
        </div>

    </div>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>