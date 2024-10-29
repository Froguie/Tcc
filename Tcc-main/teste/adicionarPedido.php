<?php
$numeroMesa = $_GET['numero'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Pedido - Mesa <?= $numeroMesa ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-300">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold">Adicionar Pedido - Mesa <?= $numeroMesa ?></h1>
        <form action="processarPedido.php" method="POST">
            <input type="hidden" name="numero_mesa" value="<?= $numeroMesa ?>">
            <div class="mb-4">
                <label>Item:</label>
                <input type="text" name="item" required class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label>Quantidade:</label>
                <input type="number" name="quantidade" required class="border p-2 w-full">
            </div>
            <div class="mb-4">
                <label>Preço:</label>
                <input type="number" step="0.01" name="preco" required class="border p-2 w-full">
            </div>
            <button type="submit" class="bg-black text-white px-4 py-2 rounded-md">Adicionar</button>
        </form>
    </div>
</body>
</html>
