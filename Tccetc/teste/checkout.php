<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Carrinho vazio!";
    exit;
}

$cart = json_decode($_SESSION['cart'], true);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-200">
  <div class="container mx-auto p-8">
    <h2 class="text-3xl font-semibold text-orange-700 mb-8">Finalizar Compra</h2>

    <h3 class="text-2xl font-semibold text-gray-700 mb-4">Produtos no Carrinho:</h3>
    <ul class="space-y-4">
      <?php foreach ($cart as $produto): ?>
        <li class="p-4 bg-white rounded-md shadow-md">
          <p class="font-semibold text-orange-700"><?php echo htmlspecialchars($produto['name']); ?></p>
          <p class="text-gray-600">Quantidade: <?php echo $produto['quantity']; ?></p>
        </li>
      <?php endforeach; ?>
    </ul>

    <form action="processa_compra.php" method="POST" class="mt-8">
      <h3 class="text-2xl font-semibold text-gray-700 mb-4">Endereço de entrega</h3>
      <label for="endereco" class="block text-gray-600">Endereço:</label>
      <input type="text" id="endereco" name="endereco" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-orange-500" required>
      
      <button type="submit" class="bg-orange-500 text-white py-3 mt-8 rounded text-lg hover:bg-orange-600">
        Finalizar Pedido
      </button>
    </form>
  </div>
</body>
</html>
