<!-- index.php -->
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
            <?php
            // Simulando dados do cardápio - você pode substituir por consulta ao banco de dados
            $cardapio = [
                ['nome' => 'Pizza Margherita', 'preco' => 35.00],
                ['nome' => 'Lasanha', 'preco' => 45.00],
                ['nome' => 'Suco de Laranja', 'preco' => 8.00],
                ['nome' => 'Café', 'preco' => 5.00],
            ];

            foreach ($cardapio as $item) {
                echo "
                <div class='flex justify-between items-center border-b pb-2'>
                    <span class='font-medium'>{$item['nome']}</span>
                    <span class='text-gray-500'>R$ {$item['preco']}</span>
                    <input type='checkbox' name='pedido[]' value='{$item['nome']}' class='ml-2'>
                </div>
                ";
            }
            ?>

            <!-- Botão de Enviar Pedido -->
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg font-bold">
                Fazer Pedido
            </button>
        </form>
    </div>
</body>
</html>
