<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$numeroMesa = $_GET['numero'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Pedido - Mesa <?= $numeroMesa ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Adicionar Pedido - Mesa <?= $numeroMesa ?></h1>

        <form action="processar_pedido.php" method="POST">
            <input type="hidden" name="numero_mesa" value="<?= $numeroMesa ?>">
            <div class="mb-4">
                <label for="item" class="block text-sm font-semibold">Item:</label>
                <input type="text" id="item" name="item" class="w-full p-2 border rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="quantidade" class="block text-sm font-semibold">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" class="w-full p-2 border rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="preco" class="block text-sm font-semibold">Preço:</label>
                <input type="number" step="0.01" id="preco" name="preco" class="w-full p-2 border rounded-md" required>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Adicionar</button>
        </form>
    </div>
</body>
</html>
