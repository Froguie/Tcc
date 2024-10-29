<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa - Restaurante</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <h1 class="text-3xl font-bold text-center mb-6">Caixa - Pagamento</h1>

    <div class="max-w-4xl mx-auto">
        <?php
        // Conectar ao banco de dados
        $conexao = new PDO("mysql:host=localhost;dbname=tastXbd", "root", "");

        // Selecionar pedidos prontos e não pagos
        $sql = "SELECT p.id, m.numero AS mesa, p.total 
                FROM pedido p
                JOIN mesa m ON p.mesa_id = m.id
                WHERE p.status = 'pronto' 
                AND p.id NOT IN (SELECT pedido_id FROM caixa)";
        $pedidos = $conexao->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        if ($pedidos):
            foreach ($pedidos as $pedido): ?>
                <div class="bg-white p-4 mb-4 shadow rounded-lg">
                    <p><strong>Mesa:</strong> <?= $pedido['mesa'] ?></p>
                    <p><strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>

                    <form action="registrar_pagamento.php" method="POST" class="mt-2">
                        <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                        <label for="metodo" class="block font-semibold mb-1">Método de Pagamento:</label>
                        <select name="metodo_pagamento" id="metodo" class="w-full p-2 border rounded-lg">
                            <option value="dinheiro">Dinheiro</option>
                            <option value="cartao">Cartão</option>
                            <option value="pix">Pix</option>
                        </select>

                        <button type="submit" 
                                class="mt-4 w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg font-bold">
                            Registrar Pagamento
                        </button>
                    </form>
                </div>
            <?php endforeach; 
        else: ?>
            <p class="text-center text-xl">Nenhum pedido pronto para pagamento.</p>
        <?php endif; ?>
    </div>
</body>
</html>
