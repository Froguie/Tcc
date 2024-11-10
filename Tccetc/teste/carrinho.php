<?php
session_start();

// Verifica se o carrinho possui itens
$carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
$total = array_reduce($carrinho, fn($sum, $item) => $sum + $item['preco'], 0);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrinho</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-100">
  <div class="container mx-auto p-8">
    <h2 class="text-3xl font-semibold text-center mb-8">Carrinho de Compras</h2>
    
    <?php if (!empty($carrinho)): ?>
      <ul class="space-y-4">
        <?php foreach ($carrinho as $item): ?>
          <li class="bg-white p-4 rounded-lg shadow-md flex justify-between items-center">
            <span><?php echo $item['nome']; ?></span>
            <span>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Total -->
      <div class="mt-6 text-xl font-semibold flex justify-between">
        <span>Total:</span>
        <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
      </div>

      <!-- Botão de Finalizar Compra -->
      <div class="text-center mt-8">
        <button class="bg-green-500 text-white py-3 px-6 rounded-full text-lg hover:bg-green-600">Finalizar Compra</button>
      </div>
    <?php else: ?>
      <p class="text-center text-xl">Seu carrinho está vazio.</p>
    <?php endif; ?>
  </div>
</body>
</html>
