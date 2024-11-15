<?php
// index.php
include("backend/conexao.php");

// Consultar os itens do cardápio no banco de dados
$sql = "SELECT nome, preco FROM Cardapio"; // Ajuste a tabela e colunas de acordo com seu banco de dados
$result = $conexao->query($sql);
$cardapio = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante - Mesa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center mb-4">Cardápio</h1>
        
        <!-- Lista de Itens do Cardápio -->
        <form action="pedido.php" method="POST" class="space-y-4">
            <?php foreach ($cardapio as $item): ?>
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="font-medium"><?= htmlspecialchars($item['nome']) ?></span>
                    <span class="text-gray-500">R$ <?= number_format($item['preco'], 2, ',', '.') ?></span>
                    <input type="checkbox" name="pedido[]" value="<?= htmlspecialchars($item['nome']) ?>" class="ml-2">
                </div>
            <?php endforeach; ?>

            <!-- Botão de Enviar Pedido -->
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg font-bold">
                Fazer Pedido
            </button>
        </form>
    </div>
</body>
</html>
